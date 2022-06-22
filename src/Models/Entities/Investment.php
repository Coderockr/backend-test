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
     * @Column(type="date")
     */
    private \DateTime $created;

    /**
     * @Column(type="date", nullable=true)
     */
    private ?\DateTime $withdrawalDate = null;

    /**
     * @Column(type="string")
     */
    private string $owner = '';

    /**
     * @Column(type="float")
     */
    private float $initialValue = 0;

    /**
     * @Column(type="float", nullable=true)
     */
    private ?float $tax = null;

    /**
     * @Column(type="float", nullable=true)
     */
    private ?float $profit = null;


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

    public function getWithdrawalDate(): ?\DateTime
    {
        return $this->withdrawalDate;
    }

    public function setWithdrawalDate(?\DateTime $withdrawalDate): Investment
    {
        $this->withdrawalDate = $withdrawalDate;
        return $this;
    }

    public function getTax(): ?float
    {
        return $this->tax;
    }

    public function setTax(?float $tax): Investment
    {
        $this->tax = $tax;
        return $this;
    }

    public function getProfit(): ?float
    {
        return $this->profit;
    }

    public function setProfit(?float $profit): Investment
    {
        $this->profit = $profit;
        return $this;
    }

    public function getWithdrawValue(): float
    {
        return $this->initialValue + $this->profit - $this->tax;
    }


}