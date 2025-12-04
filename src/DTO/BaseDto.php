<?php

declare(strict_types=1);

namespace Routegroup\Imoje\Payment\DTO;

use Illuminate\Support\Fluent;
use Routegroup\Imoje\Payment\Exceptions\ReadOnlyDtoException;
use Routegroup\Imoje\Payment\Lib\Utils;

abstract class BaseDto extends Fluent
{
    protected array $casts = [];

    protected bool $allowNull = false;

    public function __construct($attributes = [])
    {
        $attributes = $this->castAttributes($attributes);
        parent::__construct($attributes);
    }

    private function castAttributes(array $attributes): array
    {
        foreach ($this->casts as $attributeKey => $cast) {
            $attributes[$attributeKey] = $this->castAttribute($cast, $attributes[$attributeKey] ?? null);
        }

        if ($this->allowNull) {
            $attributes = array_filter($attributes);
        }

        return $attributes;
    }

    /**
     * @param string $castType
     * @param mixed $value
     * @return mixed
     */
    public function castAttribute(string $castType, $value)
    {
        if ($this->allowNull && empty($value)) {
            return null;
        }

        if (is_a($castType, BaseDto::class, true)) {
            return $value instanceof BaseDto
                ? $value
                : new $castType($value ?? []);
        }

        // MyCLabs\Enum support for PHP 7.3/7.4
        if (is_subclass_of($castType, '\\MyCLabs\\Enum\\Enum')) {
            if ($value instanceof $castType) {
                return $value;
            }
            return new $castType($value);
        }

        switch ($castType) {
            case 'int':
            case 'integer':
                return (int) $value;
            case 'real':
            case 'float':
            case 'double':
                return (float) $value;
            case 'string':
                return (string) $value;
            case 'bool':
            case 'boolean':
                return (bool) $value;
            default:
                return $value;
        }
    }

    public function toArray(): array
    {
        unset($this->attributes['getKey']);

        return app(Utils::class)->transformValues($this->attributes);
    }

    /** @throws ReadOnlyDtoException */
    public function offsetSet($offset, $value): void
    {
        throw new ReadOnlyDtoException;
    }
}
