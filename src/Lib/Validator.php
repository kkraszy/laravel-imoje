<?php

declare(strict_types=1);

namespace Routegroup\Imoje\Payment\Lib;

use Illuminate\Http\Request;
use JsonSchema\Validator as JsonSchemaValidator;
use Routegroup\Imoje\Payment\Exceptions\InvalidSignatureException;
use Routegroup\Imoje\Payment\Exceptions\SchemaValidationException;
use Routegroup\Imoje\Payment\Types\Currency;
use Routegroup\Imoje\Payment\Types\HashMethod;
use Routegroup\Imoje\Payment\Types\TransactionStatus;
use Routegroup\Imoje\Payment\Types\TransactionType;

class Validator
{
    /** @var JsonSchemaValidator */
    protected $jsonValidator;
    
    /** @var Utils */
    protected $utils;

    public function __construct(JsonSchemaValidator $jsonValidator, Utils $utils)
    {
        $this->jsonValidator = $jsonValidator;
        $this->utils = $utils;
    }

    /**
     * @throws SchemaValidationException
     */
    public function fromNotification(array $data): bool
    {
        $schema = [
            'type' => 'object',
            'properties' => [
                'transaction' => [
                    'type' => 'object',
                    'properties' => [
                        'amount' => [
                            'type' => 'integer',
                            'minimum' => 0,
                            'exclusiveMinimum' => true,
                        ],
                        'currency' => [
                            'type' => 'string',
                            'enum' => array_values(Currency::toArray()),
                        ],
                        'status' => [
                            'type' => 'string',
                            'enum' => array_values(TransactionStatus::toArray()),
                        ],
                        'orderId' => [
                            'type' => 'string',
                        ],
                        'serviceId' => [
                            'type' => 'string',
                        ],
                        'type' => [
                            'type' => 'string',
                            'enum' => array_values(TransactionType::toArray()),
                        ],
                    ],
                    'required' => [
                        'amount',
                        'currency',
                        'status',
                        'orderId',
                        'serviceId',
                        'type',
                    ],
                ],
                'payment' => [
                    'type' => 'object',
                ],
            ],
        ];

        return $this->validate($data, $schema, 'notification');
    }

    /**
     * @throws SchemaValidationException
     */
    private function validate(
        array $data,
        array $schema,
        string $schemaType
    ): bool {
        $objectData = json_decode(json_encode($data));
        $objectSchema = json_decode(json_encode($schema));

        $this->jsonValidator->validate($objectData, $objectSchema);

        if ($this->jsonValidator->isValid()) {
            return true;
        }

        $errors = [
            'schema' => $schemaType,
        ];

        foreach ($this->jsonValidator->getErrors() as $error) {
            $errors[$error['property']] = $error['message'];
        }

        throw new SchemaValidationException($errors);
    }

    /** @throws InvalidSignatureException */
    public function verifySignature(Request $request): void
    {
        $header = [];

        parse_str(
            str_replace(';', '&', $request->headers->get('x-imoje-signature', '')),
            $header
        );

        $hashMethod = new HashMethod($header['alg'] ?? 'sha256');

        $result = $this->utils->verifySignature(
            $header['signature'] ?? '',
            $request->toArray(),
            $hashMethod
        );

        if (! $result) {
            throw new InvalidSignatureException;
        }
    }
}
