<?php

namespace App\Services\User\DTO;

readonly class CreateUser
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public bool $isActive = true,
        public array $roleIds = []
    ) {}
}
