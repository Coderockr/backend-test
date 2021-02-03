<?php
declare(strict_types=1);

namespace Api\Models;

class EventModel implements ModelInterface
{
    public const ID               = 'event_id';
    public const NAME             = 'event_name';
    public const NAME_TABLE       = 'name';
    public const DESCRIPTION      = 'description';
    public const DATE             = 'event_date';
    public const DATE_TABLE       = 'date';
    public const TIME             = 'event_time';
    public const TIME_TABLE       = 'time';
    public const ADDRESS          = 'address';
    public const EVENT_ADDRESS_ID = 'event_address_id';
    public const USER             = 'user';
    public const USER_ID          = 'creator_id';
    public const CANCELED_TABLE   = 'canceled';
    public const CREATED_AT       = 'event_created_at';
    public const UPDATED_AT       = 'event_updated_at';

    protected ?int $id = null;

    protected ?string $name = null;

    protected ?string $description = null;

    protected ?\DateTime $date = null;

    protected ?\DateTime $time = null;

    protected ?UserModel $user = null;

    protected ?AddressModel $address = null;

    protected ?bool $canceled = null;

    protected ?\DateTime $createdAt = null;

    protected ?\DateTime $updatedAt = null;


    public function exchangeArray(array $data): ModelInterface
    {
        $id       = $data[self::ID] ?? $data[self::ID_TABLE] ?? null;
        $this->id = $id ? (int) $id : null;

        $name       = $data[self::NAME] ?? $data[self::NAME_TABLE] ?? null;
        $this->name = $id ? (int) $name : null;

        $date       = $data[self::DATE] ?? $data[self::DATE_TABLE] ?? null;
        $this->date = $date ? new \DateTime($date) : null;

        $time       = $data[self::TIME] ?? $data[self::TIME_TABLE] ?? null;
        $this->time = $time ? new \DateTime($time) : null;

        $createdAt       = $data[self::CREATED_AT] ?? $data[self::CREATED_AT_TABLE] ?? null;
        $this->createdAt = $createdAt ? new \DateTime($createdAt) : null;

        $updatedAt       = $data[self::UPDATED_AT] ?? $data[self::UPDATED_AT_TABLE] ?? null;
        $this->updatedAt = $updatedAt ? new \DateTime($updatedAt) : null;

        $this->description = $data[self::DESCRIPTION] ?? null;
        $this->user        = $data[self::USER] ?? null;
        $this->address     = $data[self::ADDRESS] ?? null;
        $this->canceled    = $data[self::CANCELED_TABLE] ?? null;

        return $this;
    }


    public function toArray(): array
    {
        return [
            self::ID_TABLE       => $this->id,
            self::NAME_TABLE     => $this->name,
            self::DESCRIPTION    => $this->description,
            self::DATE_TABLE     => $this->date?->format(self::DATE_FORMAT),
            self::TIME_TABLE     => $this->time?->format(self::TIME_FORMAT),
            self::ADDRESS        => $this->address?->toArray(),
            self::USER           => $this->user?->toArray(),
            self::CANCELED_TABLE => $this->canceled,
        ];
    }

    // Getters and setters
    // @codeCoverageIgnoreStart

    public function getId(): ?int
    {
        return $this->id;
    }


    public function setId(?int $id): EventModel
    {
        $this->id = $id;

        return $this;
    }


    public function getName(): ?string
    {
        return $this->name;
    }


    public function setName(?string $name): EventModel
    {
        $this->name = $name;

        return $this;
    }


    public function getDescription(): ?string
    {
        return $this->description;
    }


    public function setDescription(?string $description): EventModel
    {
        $this->description = $description;

        return $this;
    }


    public function getDate(): ?\DateTime
    {
        return $this->date;
    }


    public function setDate(?\DateTime $date): EventModel
    {
        $this->date = $date;

        return $this;
    }


    public function getTime(): ?\DateTime
    {
        return $this->time;
    }


    public function setTime(?\DateTime $time): EventModel
    {
        $this->time = $time;

        return $this;
    }


    public function getUser(): ?UserModel
    {
        return $this->user;
    }


    public function setUser(?UserModel $user): EventModel
    {
        $this->user = $user;

        return $this;
    }


    public function getAddress(): ?AddressModel
    {
        return $this->address;
    }


    public function setAddress(?AddressModel $address): EventModel
    {
        $this->address = $address;

        return $this;
    }


    public function getCanceled(): ?bool
    {
        return $this->canceled;
    }


    public function setCanceled(?bool $canceled): EventModel
    {
        $this->canceled = $canceled;

        return $this;
    }


    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }


    public function setCreatedAt(?\DateTime $createdAt): EventModel
    {
        $this->createdAt = $createdAt;

        return $this;
    }


    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }


    public function setUpdatedAt(?\DateTime $updatedAt): EventModel
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

}