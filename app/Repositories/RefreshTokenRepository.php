<?php

namespace App\Repositories;

use App\Models\RefreshToken;
use App\Repositories\Contracts\RefreshTokenRepositoryInterface;

class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    public function findValidByHash(string $tokenHash): ?RefreshToken
    {
        return RefreshToken::where('token_hash', $tokenHash)
            ->whereNull('revoked_at')
            ->where('expires_at', '>', now())
            ->first();
    }

    public function create(int $userId, string $tokenHash, \DateTimeInterface $expiresAt): RefreshToken
    {
        return RefreshToken::create([
            'user_id' => $userId,
            'token_hash' => $tokenHash,
            'expires_at' => $expiresAt,
        ]);
    }

    public function revoke(RefreshToken $token): void
    {
        $token->revoke();
    }

    public function findValidByHashForUpdate(string $tokenHash): ?RefreshToken
    {
        return RefreshToken::where('token_hash', $tokenHash)
            ->whereNull('revoked_at')
            ->where('expires_at', '>', now())
            ->lockForUpdate()
            ->first();
    }

    public function revokeByHash(string $tokenHash): int
    {
        return RefreshToken::where('token_hash', $tokenHash)
            ->whereNull('revoked_at')
            ->update(['revoked_at' => now()]);
    }

    public function pruneExpiredAndRevoked(): int
    {
        return RefreshToken::where(function ($q) {
            $q->where('expires_at', '<', now())
                ->orWhereNotNull('revoked_at');
        })
        ->delete();
    }
}
