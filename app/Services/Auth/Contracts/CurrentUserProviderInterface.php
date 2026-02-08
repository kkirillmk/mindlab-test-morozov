<?php

namespace App\Services\Auth\Contracts;

use Illuminate\Contracts\Auth\Authenticatable;

interface CurrentUserProviderInterface
{
    public function user(): ?Authenticatable;
}
