<?php
declare(strict_types=1);

namespace Api\Models;

interface ModelInterface
{
    public const DATE_TIME_FORMAT = 'Y-m-d H:i:s';
    public const DATE_FORMAT      = 'Y-m-d';
    public const TIME_FORMAT      = 'H:i:s';
    public const ID_TABLE         = 'id';
    public const CREATED_AT_TABLE = 'created_at';
    public const UPDATED_AT_TABLE = 'updated_at';


    public function getId(): ?int;


    public function exchangeArray(array $data): ModelInterface;


    public function toArray(): array;

}