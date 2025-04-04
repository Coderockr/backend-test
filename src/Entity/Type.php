<?php

namespace App\Entity;

enum Type: string
{
    case WITHDRAWAL = 'Withdrawal';
    case ENTRY = 'Entry';
}
