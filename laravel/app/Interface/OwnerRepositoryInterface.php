<?php

namespace App\Interfaces;

interface OwnerRepositoryInterface 
{
    public function setOwner(int $ownerId, string $ownerName);
}