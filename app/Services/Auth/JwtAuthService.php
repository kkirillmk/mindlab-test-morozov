<?php

namespace App\Services\Auth;

use App\Repositories\Contracts\RefreshTokenRepositoryInterface;
use App\Services\Auth\Contracts\AuthServiceInterface;
use App\Services\Auth\DTO\Credentials;
use App\Services\Auth\DTO\TokenPair;
use App\Services\Auth\Exceptions\AuthException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

readonly class JwtAuthService implements AuthServiceInterface
{
    private const int REFRESH_TOKEN_LENGTH = 60;
    private const string HASH_ALGORITHM = 'sha256';
    private const string TOKEN_TYPE = 'Bearer';

    public function __construct(
        private RefreshTokenRepositoryInterface $refreshTokenRepository
    ) {}

    public function login(Credentials $credentials): TokenPair
    {
        try {
            $accessToken = JWTAuth::attempt($credentials->toArray());
        } catch (JWTException $e) {
            throw AuthException::invalidCredentials($e->getMessage());
        }

        if (!$accessToken) {
            throw AuthException::invalidCredentials();
        }

        try {
            $user = JWTAuth::setToken($accessToken)->authenticate();
        } catch (JWTException $e) {
            throw AuthException::invalidCredentials($e->getMessage());
        }

        return $this->respondWithTokens($accessToken, $user);
    }

    public function refresh(string $refreshToken): TokenPair
    {
        return DB::transaction(function () use ($refreshToken) {
            $tokenHash = $this->hashToken($refreshToken);
            $dbRefreshToken = $this->refreshTokenRepository->findValidByHashForUpdate($tokenHash);

            if (!$dbRefreshToken) {
                throw AuthException::invalidToken();
            }

            $user = $dbRefreshToken->user;

            try {
                $newAccessToken = JWTAuth::fromUser($user);
            } catch (JWTException $e) {
                throw AuthException::invalidToken($e->getMessage());
            }

            $this->refreshTokenRepository->revoke($dbRefreshToken);

            return $this->respondWithTokens($newAccessToken, $user);
        });
    }

    public function logout(string $accessToken, string $refreshToken): void
    {
        try {
            JWTAuth::setToken($accessToken)->invalidate();
        } catch (JWTException $e) {
            logger()->debug('Не удалось аннулировать access токен при выходе', [
                'exception' => $e->getMessage(),
            ]);
        }

        $tokenHash = $this->hashToken($refreshToken);
        $this->refreshTokenRepository->revokeByHash($tokenHash);
    }

    private function respondWithTokens(string $accessToken, Authenticatable $user): TokenPair
    {
        $refreshTokenString = Str::random(self::REFRESH_TOKEN_LENGTH);
        $tokenHash = $this->hashToken($refreshTokenString);

        $this->refreshTokenRepository->create(
            userId: $user->getAuthIdentifier(),
            tokenHash: $tokenHash,
            expiresAt: now()->addMinutes((int) config('jwt.refresh_ttl'))
        );

        return new TokenPair(
            accessToken: $accessToken,
            tokenType: self::TOKEN_TYPE,
            expiresIn: JWTAuth::factory()->getTTL() * 60,
            refreshToken: $refreshTokenString,
        );
    }

    private function hashToken(string $token): string
    {
        return hash(self::HASH_ALGORITHM, $token);
    }
}

