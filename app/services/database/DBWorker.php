<?php

namespace App\Services\Database;

use PDO;

class DBWorker
{
    static private $connect;
    private $table = '';
    private $class;

    public function __construct(string $table, $class = null)
    {
        $this->table = $table;
        $this->class = $class;
    }

    /**
     * set connect with database
     *
     * @param PDO $connect
     *
     * @return void
     */
    public static function setConnect(PDO $connect)
    {
        self::$connect = $connect;
    }

    /**
     * make QueryBuilder object
     *
     * @return \App\Services\Database\QueryBuilder
     */
    public function query()
    {
        return new QueryBuilder($this->table, self::$connect, $this->class);
    }

    public function startTransaction()
    {
        self::$connect->beginTransaction();
    }

    public function commit()
    {
        self::$connect->commit();
    }

    public function rollback()
    {
        self::$connect->rollback();
    }

    /**
     * call QueryBuilder->createTable()
     *
     * given array should be like ['column_name' => ['SERIAL', 'PRIMARY KEY'], 'column_name2' => ['INT', 'NOT NULL']]
     *
     * @param string $table
     * @param array $columns
     *
     * @return void
     */
    public static function create(string $table, array $columns)
    {
        $qb = new QueryBuilder($table, self::$connect, $class = null);
        $qb->createTable($columns);
    }

}
