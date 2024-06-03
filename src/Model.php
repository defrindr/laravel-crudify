<?php

namespace Defrindr\Crudify;

use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    /**
     * Mendapatkan nama tabel
     *
     * @return string
     */
    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    public static function getTableColumns()
    {
        $class = with(new static);
        return $class->getConnection()
            ->getSchemaBuilder()
            ->getColumnListing($class->getTable());
    }
}
