<?php

declare(strict_types=1);

namespace Routegroup\Imoje\Payment\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
    
    /** @var Config */
    protected $config;

    public function __construct(JsonSchemaValidator $jsonValidator, Utils $utils, Config $config)
    {
        $this->jsonValidator = $jsonValidator;
        $this->utils = $utils;
        $this->config = $config;
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

        // Use raw request content to preserve exact JSON format from imoje
        // If content is empty (e.g., in tests), fall back to json encoding the data
        $body = $request->getContent();
        if (empty($body)) {
            // Use same JSON flags as imoje uses: only JSON_UNESCAPED_SLASHES
            $body = json_encode($request->toArray(), JSON_UNESCAPED_SLASHES);
        }
        
        $expectedSignature = hash($hashMethod->getValue(), $body . $this->config->serviceKey);
        $receivedSignature = $header['signature'] ?? '';

        // Log signature mismatch for debugging
        if ($expectedSignature !== $receivedSignature) {
            Log::error('Imoje signature verification failed', [
                'expected' => $expectedSignature,
                'received' => $receivedSignature,
                'body_length' => strlen($body),
                'body_preview' => substr($body, 0, 200),
                'algorithm' => $hashMethod->getValue(),
                'service_key_length' => strlen($this->config->serviceKey),
                'service_key_prefix' => substr($this->config->serviceKey, 0, 5),
                'header_raw' => $request->headers->get('x-imoje-signature', ''),
            ]);
            throw new InvalidSignatureException;
        }
    }
}
