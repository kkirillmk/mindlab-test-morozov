<?php

namespace App\Console\Commands;

use App\Repositories\Contracts\RefreshTokenRepositoryInterface;
use Illuminate\Console\Command;

class PruneExpiredRefreshTokens extends Command
{
    protected $signature = 'refresh-tokens:prune';

    protected $description = 'Удалить истёкшие и отозванные refresh токены';

    public function __construct(
        private readonly RefreshTokenRepositoryInterface $refreshTokenRepository
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $deleted = $this->refreshTokenRepository->pruneExpiredAndRevoked();

        $this->info("Удалено истёкших и отозванных refresh токенов: {$deleted}");

        return self::SUCCESS;
    }
}
