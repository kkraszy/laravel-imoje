<?php

declare(strict_types=1);

namespace Routegroup\Imoje\Payment\Lib;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Routegroup\Imoje\Payment\DTO\BaseDto;
use Routegroup\Imoje\Payment\Types\HashMethod;

class Utils
{
    /** @var Config */
    protected $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function createSignature(
        array $orderData,
        HashMethod $hashMethod = null
    ): string {
        if ($hashMethod === null) {
            $hashMethod = HashMethod::SHA256();
        }
        $hashValue = $hashMethod->getValue();
        $hash = hash($hashValue, $this->buildQuery($orderData).$this->config->serviceKey);

        return "{$hash};{$hashValue}";
    }

    public function verifySignature(
        string $signature,
        array $body,
        HashMethod $hashMethod
    ): bool {
        // Use same JSON flags as imoje uses: only JSON_UNESCAPED_SLASHES
        $body = json_encode($body, JSON_UNESCAPED_SLASHES);

        return $signature === hash($hashMethod->getValue(), $body.$this->config->serviceKey);
    }

    public function buildQuery(array $orderData): string
    {
        ksort($orderData);

        $orderData = $this->transformValues($orderData);
        $data = [];

        foreach ($orderData as $key => $value) {
            $data[] = $key.'='.$value;
        }

        return implode('&', $data);
    }

    public function transformValues(array $values): array
    {
        $computed = [];

        foreach ($values as $key => $value) {
            $result = $value;

            if ($result instanceof Arrayable) {
                $result = $result->toArray();
            }

            if (is_object($result) && $result instanceof \MyCLabs\Enum\Enum) {
                $result = $result->getValue();
            }

            $computed[$key] = $result;
        }

        return $computed;
    }

    public function mockHeaders(
        BaseDto $dto,
        HashMethod $hashMethod = null
    ): array {
        if ($hashMethod === null) {
            $hashMethod = HashMethod::SHA256();
        }
        $body = json_encode($dto->toArray(), JSON_UNESCAPED_SLASHES);
        $hashValue = $hashMethod->getValue();
        $signature = hash($hashValue, $body.$this->config->serviceKey);

        $value = "merchantid={$this->config->merchantId};";
        $value .= "serviceid={$this->config->serviceId};";
        $value .= "signature=$signature;";
        $value .= "alg=$hashValue";

        return ['x-imoje-signature' => $value];
    }

    public function hasStructure(array $data, array $structure): bool
    {
        $structure = Arr::dot($structure);
        $data = Arr::dot($data);

        return count($structure) === count(array_intersect($structure, $data));
    }
}
