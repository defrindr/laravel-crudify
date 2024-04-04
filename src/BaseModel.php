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
}
