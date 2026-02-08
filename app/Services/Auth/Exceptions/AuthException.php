<?php

namespace App\Services\Auth\Exceptions;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class AuthException extends HttpException
{
    public static function invalidCredentials(string $message = 'Неверный email или пароль'): self
    {
        return new self(Response::HTTP_UNAUTHORIZED, $message);
    }

    public static function invalidToken(string $message = 'Недействительный токен'): self
    {
        return new self(Response::HTTP_UNAUTHORIZED, $message);
    }
}
