<?php

namespace Defrindr\Crudify\Helpers;

class PaginationHelper
{
    /**
     * Parameter
     */
    // private $queryPage = 'page';
    private $queryLimit = 'limit';

    private $querySortDirection = 'direction';

    private $queryGlobalSearch = 'search';

    private $querySortColumn = 'sort';

    // private $defaultPage = 1;
    private $defaultSortColumn = 'id';

    private $defaultSortDirection = 'desc';

    private $defaultLimit = 10;

    /**
     * Internal variable
     */
    const SORT_ASC = 'asc';

    const SORT_DESC = 'desc';

    const SORTING_CONDITIONS = ['asc', 'desc'];

    public function setAttribute(string $attribute, string $param)
    {
        $this->$attribute = $param;
    }

    /**
     * Mendapatkan custom per-page dari inputan pengguna
     */
    public function resolveLimit(array $request): int
    {
        if (isset($request[$this->queryLimit])) {
            return intval($request[$this->queryLimit]);
        }

        return $this->defaultLimit;
    }

    /**
     * Mendapatkan custom global search dari inputan pengguna
     */
    public function resolveGlobalSearch(array $request): string
    {
        if (isset($request[$this->queryGlobalSearch])) {
            return $request[$this->queryGlobalSearch];
        }

        return '';
    }

    /**
     * Mendapatkan custom per-page dari inputan pengguna
     *
     * @param  int  $defaultPerPage
     * @return int
     */
    public function resolveSortDirection(array $request): string
    {
        if (isset($request[$this->querySortDirection])) {
            return in_array($request[$this->querySortDirection], self::SORTING_CONDITIONS) ? $request[$this->querySortDirection] : $this->defaultSortDirection;
        }

        return $this->defaultSortDirection;
    }

    /**
     * Mendapatkan custom per-page dari inputan pengguna
     *
     * @param  int  $defaultPerPage
     * @return int
     */
    public function resolveSortColumn(array $request, array $availableColumns): string
    {
        if (isset($request[$this->querySortColumn])) {
            $column = $request[$this->querySortColumn];
            if (in_array($column, $availableColumns)) return $column;
        }

        return $this->defaultSortColumn;
    }
}
