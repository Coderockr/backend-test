<?php
declare(strict_types=1);

namespace Api\Filters;

use Laminas\Db\Sql\Where;
use Psr\Http\Message\ServerRequestInterface;

class AbstractFilter implements FilterInterface
{
    protected array $filterNames = [];

    /**
     * @var array
     *
     * With disposition:
     *           [
     *              <filter_name_in_query> => <column_name_in_db>
     *           ]
     */
    protected array $filterNamesToDbColumn = [];

    /**
     * @var array
     *
     * With disposition:
     *           [
     *              <filter_name_in_query> => <value_type> (int, float, bool, string)
     *           ]
     */
    protected array $filterTypes = [];

    protected Where $where;


    public function __construct(Where $where)
    {
        $this->where = $where;
    }


    public function addFilters(ServerRequestInterface $request): FilterInterface
    {
        $queryParams = $request->getQueryParams();

        foreach ($this->filterNamesToDbColumn as $filterName => $dbColumn) {
            if (isset($queryParams[$filterName]) && in_array($filterName, $this->filterNames)) {
                $this->where->equalTo($dbColumn, $queryParams[$filterName]);
            }
        }

        return $this;
    }


    public function getFilterForDb(?ServerRequestInterface $request = null): array|Where
    {
        return $this->where;
    }


    public function getFilterValue(string $filterName, ServerRequestInterface $request): int|bool|string|float|null
    {
        $queryParams = $request->getQueryParams();

        if (isset($queryParams[$filterName])) {
            $cast = settype($queryParams[$filterName], $this->filterTypes[$filterName]);

            return $cast ? $queryParams[$filterName] : null;
        }

        return null;
    }

}