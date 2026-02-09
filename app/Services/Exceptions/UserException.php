<?php

namespace App\Services\Exceptions;

use App\Exceptions\DomainException;
use App\Exceptions\ErrorCode;
use Symfony\Component\HttpFoundation\Response;

final class UserException extends DomainException
{
    public static function invalidCurrentPassword(string $message = 'Неверный текущий пароль'): self
    {
        return new self(
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $message,
            ErrorCode::INVALID_CURRENT_PASSWORD
        );
    }
}
