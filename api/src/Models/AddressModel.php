<?php
declare(strict_types=1);

namespace Api\Models;

class AddressModel implements ModelInterface
{
    public const ID             = 'address_id';
    public const ADDRESS_LINE_1 = 'address_line_1';
    public const ADDRESS_LINE_2 = 'address_line_2';
    public const STATE          = 'state';
    public const EVENT_STATE    = 'event_state';
    public const USER_STATE     = 'user_state';
    public const EVENT_CITY     = 'city';
    public const CITY           = 'city';
    public const POSTAL_CODE    = 'postal_code';
    public const CREATED_AT     = 'address_created_at';
    public const UPDATED_AT     = 'address_updated_at';

    protected ?int $id;

    protected ?string $addressLine1;

    protected ?string $addressLine2;

    protected ?string $state;

    protected ?string $city;

    protected ?string $postalCode;

    protected ?\DateTime $createdAt;

    protected ?\DateTime $updatedAt;


    public function exchangeArray(array $data): ModelInterface
    {
        $id       = $data[self::ID] ?? $data[self::ID_TABLE] ?? null;
        $this->id = $id ? (int) $id : null;

        $createdAt       = $data[self::CREATED_AT] ?? $data[self::CREATED_AT_TABLE] ?? null;
        $this->createdAt = $createdAt ? new \DateTime($createdAt) : null;

        $updatedAt       = $data[self::UPDATED_AT] ?? $data[self::UPDATED_AT_TABLE] ?? null;
        $this->updatedAt = $updatedAt ? new \DateTime($updatedAt) : null;

        $this->addressLine1 = $data[self::ADDRESS_LINE_1] ?? null;
        $this->addressLine2 = $data[self::ADDRESS_LINE_2] ?? null;
        $this->state        = $data[self::STATE] ?? null;
        $this->city         = $data[self::CITY] ?? null;
        $this->postalCode   = $data[self::POSTAL_CODE] ?? null;

        return $this;
    }


    public function toArray(): array
    {
        return [
            self::ID_TABLE       => $this->id,
            self::ADDRESS_LINE_1 => $this->addressLine1,
            self::ADDRESS_LINE_2 => $this->addressLine2,
            self::STATE          => $this->state,
            self::CITY           => $this->city,
            self::POSTAL_CODE    => $this->postalCode,
        ];
    }


    // Getters and setters
    // @codeCoverageIgnoreStart

    public function getId(): ?int
    {
        return $this->id;
    }


    public function setId(?int $id): AddressModel
    {
        $this->id = $id;

        return $this;
    }


    public function getAddressLine1(): ?string
    {
        return $this->addressLine1;
    }


    public function setAddressLine1(?string $addressLine1): AddressModel
    {
        $this->addressLine1 = $addressLine1;

        return $this;
    }


    public function getAddressLine2(): ?string
    {
        return $this->addressLine2;
    }


    public function setAddressLine2(?string $addressLine2): AddressModel
    {
        $this->addressLine2 = $addressLine2;

        return $this;
    }


    public function getState(): ?string
    {
        return $this->state;
    }


    public function setState(?string $state): AddressModel
    {
        $this->state = $state;

        return $this;
    }


    public function getCity(): ?string
    {
        return $this->city;
    }


    public function setCity(?string $city): AddressModel
    {
        $this->city = $city;

        return $this;
    }


    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }


    public function setPostalCode(?string $postalCode): AddressModel
    {
        $this->postalCode = $postalCode;

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