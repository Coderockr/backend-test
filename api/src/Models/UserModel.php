<?php
declare(strict_types=1);

namespace Api\Models;

class UserModel implements ModelInterface
{
    public const ID                       = 'user_id';
    public const NAME_TABLE               = 'name';
    public const NAME                     = 'user_name';
    public const EMAIL                    = 'email';
    public const PASSWORD                 = 'password';
    public const PASSWORD_FAILED_ATTEMPTS = 'password_failed_attempts';
    public const BIO                      = 'bio';
    public const ADDRESS                  = 'address';
    public const USER_ADDRESS_ID          = 'user_address_id';
    public const ACTIVE                   = 'user_active';
    public const ACTIVE_TABLE             = 'active';
    public const ACCOUNT_LOCKED_AT        = 'account_locked_at';
    public const LAST_LOGIN               = 'last_login';
    public const CREATED_AT               = 'user_created_at';
    public const UPDATED_AT               = 'user_updated_at';

    protected ?int $id;

    protected ?string $name;

    protected ?string $email;

    protected ?string $password;

    protected ?int $passwordFailedAttempts;

    protected ?string $bio;

    protected ?AddressModel $address;

    protected ?bool $active;

    protected ?\DateTime $accountLockedAt;

    protected ?\DateTime $lastLogin;

    protected ?\DateTime $createdAt;

    protected ?\DateTime $updatedAt;


    public function exchangeArray(array $data): ModelInterface
    {
        $id       = $data[self::ID] ?? $data[self::ID_TABLE] ?? null;
        $this->id = $id ? (int) $id : null;

        $name       = $data[self::NAME] ?? $data[self::NAME_TABLE] ?? null;
        $this->name = $id ? (int) $name : null;

        $createdAt       = $data[self::CREATED_AT] ?? $data[self::CREATED_AT_TABLE] ?? null;
        $this->createdAt = $createdAt ? new \DateTime($createdAt) : null;

        $updatedAt       = $data[self::UPDATED_AT] ?? $data[self::UPDATED_AT_TABLE] ?? null;
        $this->updatedAt = $updatedAt ? new \DateTime($updatedAt) : null;

        $this->passwordFailedAttempts = ($data[self::PASSWORD_FAILED_ATTEMPTS] ?? null)
            ? (int) $data[self::PASSWORD_FAILED_ATTEMPTS] : null;
        $this->accountLockedAt        = ($data[self::ACCOUNT_LOCKED_AT] ?? null)
            ? new \DateTime($data[self::ACCOUNT_LOCKED_AT]) : null;
        $this->lastLogin              = ($data[self::LAST_LOGIN] ?? null)
            ? new \DateTime($data[self::LAST_LOGIN]) : null;

        $this->email    = $data[self::EMAIL] ?? null;
        $this->password = $data[self::PASSWORD] ?? null;
        $this->bio      = $data[self::BIO] ?? null;
        $this->address  = $data[self::ADDRESS] ?? null;
        $this->active   = ($data[self::ACTIVE] ?? null) ? (bool) $data[self::ACTIVE] : null;

        return $this;
    }


    public function toArray(): array
    {
        return [
            self::ID_TABLE                 => $this->id,
            self::NAME_TABLE               => $this->name,
            self::EMAIL                    => $this->email,
            self::PASSWORD                 => $this->password,
            self::PASSWORD_FAILED_ATTEMPTS => $this->passwordFailedAttempts,
            self::BIO                      => $this->bio,
            self::ADDRESS                  => $this->address?->toArray(),
            self::ACTIVE                   => $this->active,
            self::ACCOUNT_LOCKED_AT        => $this->accountLockedAt?->format(self::DATE_TIME_FORMAT),
            self::LAST_LOGIN               => $this->lastLogin?->format(self::DATE_TIME_FORMAT),
        ];
    }

    // Getters and setters
    // @codeCoverageIgnoreStart

    public function getId(): ?int
    {
        return $this->id;
    }


    public function setId(?int $id): UserModel
    {
        $this->id = $id;

        return $this;
    }


    public function getName(): ?string
    {
        return $this->name;
    }


    public function setName(?string $name): UserModel
    {
        $this->name = $name;

        return $this;
    }


    public function getEmail(): ?string
    {
        return $this->email;
    }


    public function setEmail(?string $email): UserModel
    {
        $this->email = $email;

        return $this;
    }


    public function getPassword(): ?string
    {
        return $this->password;
    }


    public function setPassword(?string $password): UserModel
    {
        $this->password = $password;

        return $this;
    }


    public function getPasswordFailedAttempts(): ?int
    {
        return $this->passwordFailedAttempts;
    }


    public function setPasswordFailedAttempts(?int $passwordFailedAttempts): UserModel
    {
        $this->passwordFailedAttempts = $passwordFailedAttempts;

        return $this;
    }


    public function getBio(): ?string
    {
        return $this->bio;
    }


    public function setBio(?string $bio): UserModel
    {
        $this->bio = $bio;

        return $this;
    }


    public function getAddress(): ?AddressModel
    {
        return $this->address;
    }


    public function setAddress(?AddressModel $address): UserModel
    {
        $this->address = $address;

        return $this;
    }


    public function getActive(): ?bool
    {
        return $this->active;
    }


    public function setActive(?bool $active): UserModel
    {
        $this->active = $active;

        return $this;
    }


    public function getAccountLockedAt(): ?\DateTime
    {
        return $this->accountLockedAt;
    }


    public function setAccountLockedAt(?\DateTime $accountLockedAt): UserModel
    {
        $this->accountLockedAt = $accountLockedAt;

        return $this;
    }


    public function getLastLogin(): ?\DateTime
    {
        return $this->lastLogin;
    }


    public function setLastLogin(?\DateTime $lastLogin): UserModel
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }


    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }


    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

}