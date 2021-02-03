<?php
declare(strict_types=1);

namespace Api\Tests\Unit\Tables;

use Api\Filters\EventFilter;
use Api\Helpers\TableHelper;
use Api\Models\AddressModel;
use Api\Models\EventModel;
use Api\Models\UserModel;
use Api\Tables\AddressTable;
use Api\Tables\EventTable;
use Api\Tables\UserTable;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Where;
use Laminas\Db\TableGateway\AbstractTableGateway;
use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Paginator\Paginator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class EventTableTest extends TestCase
{
    private MockObject|AbstractTableGateway $tableGateway;

    private TableHelper|MockObject $tableHelper;

    private EventTable $table;


    protected function setUp(): void
    {
        parent::setUp();

        $this->tableGateway = $this->createMock(AbstractTableGateway::class);
        $this->tableHelper  = $this->createMock(TableHelper::class);

        $this->table = new EventTable($this->tableGateway, $this->tableHelper);
    }


    public function testFetchAllWithPagination(): void
    {
        $filter = $this->createMock(EventFilter::class);

        $select = $this->createMock(Select::class);

        $this->tableHelper->expects(static::once())
                          ->method('newSelect')
                          ->willReturn($select);

        $select->expects(static::once())
               ->method('from')
               ->with(EventTable::DB_TABLE)
               ->willReturnSelf();

        $select->expects(static::once())
               ->method('columns')
               ->with(
                   [
                       EventModel::ID         => EventModel::ID_TABLE,
                       EventModel::NAME       => EventModel::NAME_TABLE,
                       EventModel::CREATED_AT => EventModel::CREATED_AT_TABLE,
                       EventModel::DATE_TABLE,
                       EventModel::TIME_TABLE,
                       EventModel::CANCELED_TABLE,
                   ]
               )
               ->willReturnSelf();

        $select->expects(static::exactly(2))
               ->method('join')
               ->withConsecutive(
                   [
                       AddressTable::DB_TABLE,
                       'event.event_address_id = address.id',
                       [
                           AddressModel::EVENT_STATE => AddressModel::STATE,
                           AddressModel::EVENT_CITY  => AddressModel::CITY,
                       ],
                   ],
                   [
                       UserTable::DB_TABLE,
                       'event.creator_id = user.id',
                       [
                           UserModel::NAME => UserModel::NAME_TABLE,
                       ],
                   ]
               )
               ->willReturnSelf();

        $where = new Where();

        $filter->expects(static::once())
               ->method('getFilterForDb')
               ->willReturn($where);

        $select->expects(static::once())
               ->method('where')
               ->with($where)
               ->willReturnSelf();

        $select->expects(static::once())
               ->method('order')
               ->with([EventModel::CREATED_AT => 'DESC'])
               ->willReturnSelf();

        $this->tableGateway->expects(static::never())->method('selectWith');

        $adapter = $this->createMock(Adapter::class);

        $this->tableGateway->expects(static::once())
                           ->method('getAdapter')
                           ->willReturn($adapter);

        $dbSelect = $this->createMock(DbSelect::class);

        $this->tableHelper->expects(static::once())
                          ->method('newDbSelect')
                          ->with($select, $adapter)
                          ->willReturn($dbSelect);

        $paginator = $this->createMock(Paginator::class);

        $this->tableHelper->expects(static::once())
                          ->method('newPaginator')
                          ->with($dbSelect)
                          ->willReturn($paginator);

        static::assertSame($paginator, $this->table->fetchAll($filter));
    }


    public function testFetchAllWithoutPagination(): void
    {
        $filter = $this->createMock(EventFilter::class);

        $select = $this->createMock(Select::class);

        $this->tableHelper->expects(static::once())
                          ->method('newSelect')
                          ->willReturn($select);

        $select->expects(static::once())
               ->method('from')
               ->with(EventTable::DB_TABLE)
               ->willReturnSelf();

        $select->expects(static::once())
               ->method('columns')
               ->with(
                   [
                       EventModel::ID         => EventModel::ID_TABLE,
                       EventModel::NAME       => EventModel::NAME_TABLE,
                       EventModel::CREATED_AT => EventModel::CREATED_AT_TABLE,
                       EventModel::DATE_TABLE,
                       EventModel::TIME_TABLE,
                       EventModel::CANCELED_TABLE,
                   ]
               )
               ->willReturnSelf();

        $select->expects(static::exactly(2))
               ->method('join')
               ->withConsecutive(
                   [
                       AddressTable::DB_TABLE,
                       'event.event_address_id = address.id',
                       [
                           AddressModel::EVENT_STATE => AddressModel::STATE,
                           AddressModel::EVENT_CITY  => AddressModel::CITY,
                       ],
                   ],
                   [
                       UserTable::DB_TABLE,
                       'event.creator_id = user.id',
                       [
                           UserModel::NAME => UserModel::NAME_TABLE,
                       ],
                   ]
               )
               ->willReturnSelf();

        $where = new Where();

        $filter->expects(static::once())
               ->method('getFilterForDb')
               ->willReturn($where);

        $select->expects(static::once())
               ->method('where')
               ->with($where)
               ->willReturnSelf();

        $select->expects(static::once())
               ->method('order')
               ->with([EventModel::CREATED_AT => 'DESC'])
               ->willReturnSelf();

        $resultSet = $this->createMock(ResultSet::class);

        $this->tableGateway->expects(static::once())
                           ->method('selectWith')
                           ->with($select)
                           ->willReturn($resultSet);

        $this->tableGateway->expects(static::never())->method('getAdapter');
        $this->tableHelper->expects(static::never())->method('newDbSelect');
        $this->tableHelper->expects(static::never())->method('newPaginator');

        static::assertSame($resultSet, $this->table->fetchAll($filter, false));
    }


    public function testFetchOneWithModel(): void
    {
        $model = $this->createMock(EventModel::class);

        $id = 1;

        $model->expects(static::once())
              ->method('getId')
              ->willReturn($id);

        $select = $this->createMock(Select::class);

        $this->tableHelper->expects(static::once())
                          ->method('newSelect')
                          ->willReturn($select);

        $select->expects(static::once())
               ->method('from')
               ->with(EventTable::DB_TABLE)
               ->willReturnSelf();

        $select->expects(static::once())
               ->method('columns')
               ->with(
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
               ->willReturnSelf();

        $select->expects(static::exactly(2))
               ->method('join')
               ->withConsecutive(
                   [
                       AddressTable::DB_TABLE,
                       'event.event_address_id = address.id',
                       [
                           AddressModel::EVENT_STATE => AddressModel::STATE,
                           AddressModel::EVENT_CITY  => AddressModel::CITY,
                           AddressModel::ADDRESS_LINE_1,
                           AddressModel::ADDRESS_LINE_2,
                           AddressModel::POSTAL_CODE,
                       ],
                   ],
                   [
                       UserTable::DB_TABLE,
                       'event.creator_id = user.id',
                       [
                           UserModel::NAME   => UserModel::NAME_TABLE,
                           UserModel::ACTIVE => UserModel::ACTIVE_TABLE,
                           UserModel::EMAIL,
                       ],
                   ]
               )
               ->willReturnSelf();

        $select->expects(static::once())
               ->method('where')
               ->with(['event.id' => $id])
               ->willReturnSelf();

        $resultSet = $this->createMock(ResultSet::class);

        $this->tableGateway->expects(static::once())
                           ->method('selectWith')
                           ->with($select)
                           ->willReturn($resultSet);

        $resultArray = [
            EventModel::ID               => 1,
            EventModel::NAME             => 'Event 1',
            EventModel::CREATED_AT       => '2012-01-01 05:20:22',
            EventModel::DATE_TABLE       => '2010-01-15',
            EventModel::TIME_TABLE       => '12:00:00',
            EventModel::CANCELED_TABLE   => 0,
            EventModel::DESCRIPTION      => 'This is an event\'s description',
            AddressModel::EVENT_STATE    => 'RN',
            AddressModel::EVENT_CITY     => 'Natal',
            AddressModel::ADDRESS_LINE_1 => 'Rua Fake',
            AddressModel::ADDRESS_LINE_2 => 'n. 999',
            AddressModel::POSTAL_CODE    => '59012000',
            UserModel::NAME              => 'John Doe',
            UserModel::ACTIVE            => 1,
            UserModel::EMAIL             => 'john.doe@email.com',
        ];

        $resultSet->expects(static::once())
                  ->method('current')
                  ->willReturn($resultArray);

        static::assertSame($resultArray, $this->table->fetchOne($model));
    }


    public function testFetchOneWithId(): void
    {
        $id = 1;

        $select = $this->createMock(Select::class);

        $this->tableHelper->expects(static::once())
                          ->method('newSelect')
                          ->willReturn($select);

        $select->expects(static::once())
               ->method('from')
               ->with(EventTable::DB_TABLE)
               ->willReturnSelf();

        $select->expects(static::once())
               ->method('columns')
               ->with(
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
               ->willReturnSelf();

        $select->expects(static::exactly(2))
               ->method('join')
               ->withConsecutive(
                   [
                       AddressTable::DB_TABLE,
                       'event.event_address_id = address.id',
                       [
                           AddressModel::EVENT_STATE => AddressModel::STATE,
                           AddressModel::EVENT_CITY  => AddressModel::CITY,
                           AddressModel::ADDRESS_LINE_1,
                           AddressModel::ADDRESS_LINE_2,
                           AddressModel::POSTAL_CODE,
                       ],
                   ],
                   [
                       UserTable::DB_TABLE,
                       'event.creator_id = user.id',
                       [
                           UserModel::NAME   => UserModel::NAME_TABLE,
                           UserModel::ACTIVE => UserModel::ACTIVE_TABLE,
                           UserModel::EMAIL,
                       ],
                   ]
               )
               ->willReturnSelf();

        $select->expects(static::once())
               ->method('where')
               ->with(['event.id' => $id])
               ->willReturnSelf();

        $resultSet = $this->createMock(ResultSet::class);

        $this->tableGateway->expects(static::once())
                           ->method('selectWith')
                           ->with($select)
                           ->willReturn($resultSet);

        $resultArray = [
            EventModel::ID               => 1,
            EventModel::NAME             => 'Event 1',
            EventModel::CREATED_AT       => '2012-01-01 05:20:22',
            EventModel::DATE_TABLE       => '2010-01-15',
            EventModel::TIME_TABLE       => '12:00:00',
            EventModel::CANCELED_TABLE   => 0,
            EventModel::DESCRIPTION      => 'This is an event\'s description',
            AddressModel::EVENT_STATE    => 'RN',
            AddressModel::EVENT_CITY     => 'Natal',
            AddressModel::ADDRESS_LINE_1 => 'Rua Fake',
            AddressModel::ADDRESS_LINE_2 => 'n. 999',
            AddressModel::POSTAL_CODE    => '59012000',
            UserModel::NAME              => 'John Doe',
            UserModel::ACTIVE            => 1,
            UserModel::EMAIL             => 'john.doe@email.com',
        ];

        $resultArrayObj = new \ArrayObject($resultArray);

        $resultSet->expects(static::once())
                  ->method('current')
                  ->willReturn($resultArrayObj);

        static::assertSame($resultArray, $this->table->fetchOne($id));
    }


}