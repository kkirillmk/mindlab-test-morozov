<?php

namespace App\Services\Auth\Exceptions;

use App\Exceptions\DomainException;
use App\Exceptions\ErrorCode;
use Symfony\Component\HttpFoundation\Response;

final class AuthException extends DomainException
{
    public static function invalidCredentials(string $message = 'Неверный email или пароль'): self
    {
        return new self(
            Response::HTTP_UNAUTHORIZED,
            $message,
            ErrorCode::INVALID_CREDENTIALS
        );
    }

    public static function invalidToken(string $message = 'Недействительный токен'): self
    {
        return new self(
            Response::HTTP_UNAUTHORIZED,
            $message,
            ErrorCode::INVALID_TOKEN
        );
    }

    public static function forbidden(string $message = 'Доступ запрещён'): self
    {
        return new self(
            Response::HTTP_FORBIDDEN,
            $message,
            ErrorCode::FORBIDDEN
        );
    }

    public static function accountDeactivated(string $message = 'Ваш аккаунт деактивирован'): self
    {
        return new self(
            Response::HTTP_FORBIDDEN,
            $message,
            ErrorCode::ACCOUNT_DEACTIVATED
        );
    }
}
