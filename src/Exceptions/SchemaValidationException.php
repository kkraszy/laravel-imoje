<?php

declare(strict_types=1);

namespace Routegroup\Imoje\Payment\Exceptions;

use Exception;

class SchemaValidationException extends Exception
{
    /** @var array */
    public $errors;

    public function __construct(array $errors)
    {
        $this->errors = $errors;
        parent::__construct('Given data doesnt fulfill JSON schema requirements.', 422);
    }

    public function context(): array
    {
        return [
            'errors' => $this->errors,
        ];
    }
}
