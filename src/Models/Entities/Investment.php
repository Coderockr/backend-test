<?php

namespace App\Models\Entities;

/**
 * @Entity @Table(name="investments")
 * @ORM @Entity(repositoryClass="App\Models\Repository\InvestmentRepository")
 */
class Investment
{
    /**
     * @Id @GeneratedValue @Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @Column(type="datetime")
     */
    private \DateTime $created;

    /**
     * @Column(type="string")
     */
    private string $owner = '';

    /**
     * @Column(type="float")
     */
    private float $initialValue = 0;


    public function __construct()
    {
        $this->created = new \DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCreated(): \DateTime
    {
        return $this->created;
    }

    public function setCreated(\DateTime $created): Investment
    {
        $this->created = $created;
        return $this;
    }

    public function getOwner(): string
    {
        return $this->owner;
    }

    public function setOwner(string $owner): Investment
    {
        $this->owner = $owner;
        return $this;
    }

    public function getInitialValue(): float
    {
        return $this->initialValue;
    }

    public function setInitialValue(float $initialValue)
    {
        $this->initialValue = $initialValue;
        return $this;
    }

}