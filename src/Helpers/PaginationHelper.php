<?php

namespace Defrindr\Crudify\Helpers;

use Illuminate\Http\Request;

class PaginationHelper
{
    /**
     * Parameter
     */
    const PARAM_PERPAGE = 'per-page';

    const PARAM_SORT = 'sort';

    /**
     * Internal variable
     */
    const SORT_ASC = 'asc';

    const SORT_DESC = 'desc';

    const SORTING_CONDITIONS = ['asc', 'desc'];

    /**
     * Mendapatkan custom per-page dari inputan pengguna
     */
    public static function perPage(Request $request, int $defaultPerPage = 10): int
    {
        $customPerPage = $request->post(self::PARAM_PERPAGE) ?? $request->get(self::PARAM_PERPAGE);

        return intval($customPerPage ?? $defaultPerPage);
    }

    /**
     * Mendapatkan custom per-page dari inputan pengguna
     *
     * @param  int  $defaultPerPage
     * @return int
     */
    public static function sortCondition(Request $request, string $defaultSortCondition = 'desc'): string
    {
        return in_array($request->get(self::PARAM_SORT), self::SORTING_CONDITIONS) ? $request->get(self::PARAM_SORT) : $defaultSortCondition;
    }
}
