<?php

Namespace App\Repositories;

use App\Models\Owner;

class OwnerRepository
{
    public function setOwner(int $ownerId, string $ownerName): int
    {
        $owner = new Owner();
        $owner->id = $ownerId;
        $owner->owner_name = $ownerName;
        $owner->save();
        return $ownerId;
    }

}
