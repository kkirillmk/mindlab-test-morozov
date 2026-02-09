<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class DomainException extends HttpException
{
    protected ?ErrorCode $errorCode = null;

    public function __construct(
        int $statusCode,
        string $message,
        ?ErrorCode $errorCode = null,
        ?\Throwable $previous = null,
        array $headers = [],
        int $code = 0
    ) {
        parent::__construct($statusCode, $message, $previous, $headers, $code);
        $this->errorCode = $errorCode;
    }

    public function toArray(): array
    {
        return [
            'message' => $this->getMessage(),
            'code' => $this->errorCode?->value ?? ErrorCode::UNKNOWN_ERROR->value,
        ];
    }
}
