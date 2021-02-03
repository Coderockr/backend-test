<?php
declare(strict_types=1);

namespace Api\Filters;

use Api\Models\AddressModel;
use Api\Models\EventModel;
use Api\Tables\AddressTable;
use Api\Tables\EventTable;

class EventFilter extends AbstractFilter
{
    public const FILTER_STATE = 'state';
    public const FILTER_DATE  = 'date';

    protected array $filterNames = [
        self::FILTER_STATE,
        self::FILTER_DATE,
    ];

    /**
     * @inheritdoc
     */
    protected array $filterNamesToDbColumn = [
        self::FILTER_STATE => AddressTable::DB_TABLE . '.' . AddressModel::STATE,
        self::FILTER_DATE  => EventTable::DB_TABLE . '.' . EventModel::DATE_TABLE,
    ];

    /**
     * @inheritdoc
     */
    protected array $filterTypes = [
        self::FILTER_STATE => 'string',
        self::FILTER_DATE  => 'string',
    ];

}