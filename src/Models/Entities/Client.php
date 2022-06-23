<?php

namespace App\Models\Entities;

/**
 * @Entity @Table(name="clients")
 * @ORM @Entity(repositoryClass="App\Models\Repository\ClientRepository")
 */
class Client
{
    /**
     * @Id @GeneratedValue @Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @Column(type="string")
     */
    private string $name = '';


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Client
    {
        $this->name = $name;
        return $this;
    }

}
