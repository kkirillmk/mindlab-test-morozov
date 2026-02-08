<?php

namespace App\Repositories\Contracts;

use App\Models\RefreshToken;

interface RefreshTokenRepositoryInterface
{
    public function findValidByHash(string $tokenHash): ?RefreshToken;

    public function findValidByHashForUpdate(string $tokenHash): ?RefreshToken;

    public function create(int $userId, string $tokenHash, \DateTimeInterface $expiresAt): RefreshToken;

    public function revoke(RefreshToken $token): void;

    public function revokeByHash(string $tokenHash): int;

    public function pruneExpiredAndRevoked(): int;
}
