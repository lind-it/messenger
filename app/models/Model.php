<?php

namespace App\Models;

use App\Services\Database\DBWorker;

class Model
{
    protected static $tableName = '';

    public static function table()
    {
        return new DBWorker(static::$tableName, static::class);
    }
}
