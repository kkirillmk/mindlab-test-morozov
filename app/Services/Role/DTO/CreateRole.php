<?php

namespace App\Services\Role\DTO;

readonly class CreateRole
{
    public function __construct(
        public string $name,
        public ?string $slug = null,
        public ?string $description = null
    ) {}
}
