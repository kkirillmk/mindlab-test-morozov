<?php

namespace App\Services\User\DTO;

readonly class UpdateUser
{
    public function __construct(
        public ?string $name = null,
        public ?string $email = null,
        public ?string $password = null,
        public ?bool $isActive = null,
        public ?array $roleIds = null
    ) {}

    public function toArray(): array
    {
        $array = [];

        if ($this->name !== null) {
            $array['name'] = $this->name;
        }

        if ($this->email !== null) {
            $array['email'] = $this->email;
        }

        if ($this->password !== null) {
            $array['password'] = $this->password;
        }

        if ($this->isActive !== null) {
            $array['is_active'] = $this->isActive;
        }

        return $array;
    }

    public function hasRoleIds(): bool
    {
        return $this->roleIds !== null;
    }
}
