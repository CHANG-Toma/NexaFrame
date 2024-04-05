<?php

namespace App\Models;

use App\Core\DB;

class User extends DB
{
    private ?int $id = null;
    protected string $login;
    protected string $email;
    protected string $password;
    protected string $role;
    protected $updated_at;
    protected $deleted_at;
    protected $status;
    protected $validate;
    protected $validation_token;

    public function __construct()
    {
        $this->id = 0;
        $this->login = '';
        $this->email = '';
        $this->password = '';
        $this->role = '';
        $this->updated_at = null;
        $this->status = 0;
        $this->validate = false;
        $this->validation_token = null;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    public function getUpdated_at(): ?string
    {
        return $this->updated_at;
    }

    public function setUpdated_at(?string $updatedAt): void
    {
        $this->updated_at = $updatedAt;
    }

    public function getDeleted_at(): ?string
    {
        return $this->deleted_at;
    }

    public function setDeleted_at(?string $deletedAt): void
    {
        $this->deleted_at = $deletedAt;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function isValidate(): bool
    {
        return $this->validate;
    }

    public function setValidate(bool $validate): void
    {
        $this->validate = $validate;
    }

    public function getValidation_token(): ?string
    {
        return $this->validation_token;
    }

    public function setValidation_token(?string $validationToken): void
    {
        $this->validation_token = $validationToken;
    }
}