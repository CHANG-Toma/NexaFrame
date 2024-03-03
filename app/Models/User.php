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

    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
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

    public function getValidationToken(): ?string
    {
        return $this->validationToken;
    }

    public function setValidationToken(?string $validationToken): void
    {
        $this->validationToken = $validationToken;
    }
}