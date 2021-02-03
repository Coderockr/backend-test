<?php
declare(strict_types=1);

namespace Api\Tables;

use Api\Filters\FilterInterface;
use Api\Models\AddressModel;
use Api\Models\EventModel;
use Api\Models\ModelInterface;
use Api\Models\UserModel;
use Laminas\Db\Adapter\Driver\ResultInterface;
use Laminas\Db\ResultSet\ResultSetInterface;
use Laminas\Paginator\Paginator;

class EventTable extends AbstractTable
{
    public const DB_TABLE      = 'event';
    public const TABLE_GATEWAY = self::class . 'Gateway';

    protected ?string $modelClass = EventModel::class;


    public function fetchAll(
        array|FilterInterface $filter,
        bool $isPaginated = true
    ): ResultInterface|ResultSetInterface|Paginator
    {
        $select = ($this->tableHelper->newSelect())
            ->from(self::DB_TABLE)
            ->columns(
                [
                    EventModel::ID         => EventModel::ID_TABLE,
                    EventModel::NAME       => EventModel::NAME_TABLE,
                    EventModel::CREATED_AT => EventModel::CREATED_AT_TABLE,
                    EventModel::DATE_TABLE,
                    EventModel::TIME_TABLE,
                    EventModel::CANCELED_TABLE,
                ]
            )
            ->join(
                AddressTable::DB_TABLE,
                'event.event_address_id = address.id',
                [
                    AddressModel::EVENT_STATE => AddressModel::STATE,
                    AddressModel::EVENT_CITY  => AddressModel::CITY,
                ]
            )
            ->join(
                UserTable::DB_TABLE,
                'event.creator_id = user.id',
                [
                    UserModel::NAME => UserModel::NAME_TABLE,
                ]
            )
            ->where($filter->getFilterForDb())
            ->order([EventModel::CREATED_AT => 'DESC']);

        if ($isPaginated) {
            return $this->fetchPaginatedResults($select);
        }

        return $this->tableGateway->selectWith($select);
    }


    public function fetchOne(int|ModelInterface $idOrModel): ModelInterface|array
    {
        $id = $idOrModel instanceof ModelInterface ? $idOrModel->getId() : $idOrModel;

        $select = ($this->tableHelper->newSelect())
            ->from(self::DB_TABLE)
            ->columns(
                [
                    EventModel::ID         => EventModel::ID_TABLE,
                    EventModel::NAME       => EventModel::NAME_TABLE,
                    EventModel::CREATED_AT => EventModel::CREATED_AT_TABLE,
                    EventModel::DATE_TABLE,
                    EventModel::TIME_TABLE,
                    EventModel::CANCELED_TABLE,
                    EventModel::DESCRIPTION,
                ]
            )
            ->join(
                AddressTable::DB_TABLE,
                'event.event_address_id = address.id',
                [
                    AddressModel::EVENT_STATE => AddressModel::STATE,
                    AddressModel::EVENT_CITY  => AddressModel::CITY,
                    AddressModel::ADDRESS_LINE_1,
                    AddressModel::ADDRESS_LINE_2,
                    AddressModel::POSTAL_CODE,
                ]
            )
            ->join(
                UserTable::DB_TABLE,
                'event.creator_id = user.id',
                [
                    UserModel::NAME   => UserModel::NAME_TABLE,
                    UserModel::ACTIVE => UserModel::ACTIVE_TABLE,
                    UserModel::EMAIL,
                ]
            )
            ->where(['event.id' => $id]);

        return (array) $this->tableGateway->selectWith($select)->current();
    }

}