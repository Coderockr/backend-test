<?php
declare(strict_types=1);

namespace Api\Tables;

use Api\Filters\FilterInterface;
use Api\Helpers\TableHelper;
use Api\Models\ModelInterface;
use Laminas\Db\Adapter\Driver\ResultInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\ResultSet\ResultSetInterface;
use Laminas\Db\Sql\Select;
use Laminas\Db\TableGateway\AbstractTableGateway;
use Laminas\Paginator\Paginator;

abstract class AbstractTable implements TableInterface
{
    protected ?string $modelClass = null;

    protected AbstractTableGateway $tableGateway;

    protected TableHelper $tableHelper;


    public function __construct(
        AbstractTableGateway $tableGateway,
        TableHelper $tableHelper
    )
    {
        $this->tableGateway = $tableGateway;
        $this->tableHelper  = $tableHelper;
    }


    public function fetchAll(
        array|FilterInterface $filter,
        bool $isPaginated = true
    ): ResultInterface|ResultSetInterface|Paginator
    {
        if ($isPaginated) {
            return $this->fetchPaginatedResults();
        }

        return $this->tableGateway->select();
    }


    public function fetchOne(int|ModelInterface $idOrModel): ModelInterface|array
    {
        $id = $idOrModel instanceof ModelInterface ? $idOrModel->getId() : $idOrModel;

        /** @var ResultSet $rowSet */
        $rowSet = $this->tableGateway->select(['id' => $id]);
        $row    = $rowSet->current();

        return $this->getModelFromRow((array) $row);
    }


    public function saveOne(ModelInterface $model): bool
    {
        $data = $this->getRowFromModel($model);

        $id = $model->getId();

        if (!$id) {
            return $this->tableGateway->insert($data) === 1;
        }

        try {
            $this->fetchOne($id);
        } catch (\RuntimeException $e) {
            throw new \RuntimeException(
                sprintf(
                    'Cannot update %s with identifier %d; does not exist',
                    self::class,
                    $id
                )
            );
        }

        return $this->tableGateway->update($data, ['id' => $id]) === 1;
    }


    public function deleteOne(int|ModelInterface $idOrModel): bool
    {
        $id = $idOrModel instanceof ModelInterface ? $idOrModel->getId() : $idOrModel;

        return $this->tableGateway->delete(['id' => $id]) === 1;
    }


    protected function fetchPaginatedResults(?Select $select = null): Paginator
    {
        if ($select === null) {
            $select = $this->tableHelper->newSelect($this->tableGateway->getTable());
        }

        $paginatorAdapter = $this->tableHelper->newDbSelect($select, $this->tableGateway->getAdapter());

        return $this->tableHelper->newPaginator($paginatorAdapter);
    }


    protected function getModelFromRow(?array $row): ModelInterface
    {
        if ($row === null) {
            throw new \RuntimeException('Could not find row for corresponding identifier');
        }

        if ($this->modelClass === null) {
            throw new \RuntimeException('Model class needs to be set before fetching records.');
        }

        /** @var ModelInterface $model */
        $model = new $this->modelClass;

        return $model->exchangeArray($row);
    }


    protected function getRowFromModel(ModelInterface $model): array
    {
        return $model->toArray();
    }

}