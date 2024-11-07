<?php

namespace App\Services\Database;

use PDO;
use PDOException;

class OldQueryBuilder
{
    private $query = '';
    private $where = '';
    private $limit = '';
    private $table = '';
    private $error = null;
    private $queryData = null;
    private $prepareParameters;
    private $connect;
    private $class;

    public function __construct(string $table, PDO $connect, $class)
    {
        $this->table = $table;
        $this->connect = $connect;
        $this->class = $class;
    }

    /**
     * execute constructed query
     *
     * @return \App\Services\Database\QueryBuilder
     */
    public function exec()
    {
        try
        {
            $query = $this->query . ' ' . $this->where. ' ' . $this->limit;
            $stmt = $this->connect->prepare($query);
            $stmt->execute($this->prepareParameters);

            if($stmt->errorCode() !== '00000')
            {
                $this->error['code'] = $stmt->errorInfo()[0];
                $this->error['message'] = $stmt->errorInfo()[2];


            }

            else
            {
                if (is_null($this->class))
                {
                    $stmt->setFetchMode(PDO::FETCH_ASSOC);
                }

                $stmt->setFetchMode(PDO::FETCH_CLASS, $this->class);

                $this->queryData = $stmt;
            }
        }

        catch (PDOException $e)
        {
            $this->error = $e;
        }

        return $this;
    }

    /**
     * make SELECT query
     *
     * @param array $columns = ['*']
     *
     * @return \App\Services\Database\QueryBuilder
     */
    public function select(array $columns = ['*'])
    {
        $columns = implode(', ', $columns);

        $this->query = 'SELECT ' . $columns . ' FROM ' . $this->table;

        return $this;
    }

    /**
     * make INSERT query
     *
     * $data should be like [$key => $value, $key2 => $value2...]
     *
     * @param array $data
     *
     * @return \App\Services\Database\QueryBuilder
     */
    public function insert(array $data)
    {
        $columns = '';
        $values = '';

        foreach ($data as $key => $value)
        {
            $columns .= $key . ', ';
            $values .= ':'. $key . ', ';

            $this->prepareParameters[$key] = $value;
        }

        $columns = rtrim($columns, ', ');
        $values = rtrim($values, ', ');

        $this->query = 'INSERT INTO ' . $this->table . '(' . $columns . ') VALUES (' . $values . ')';

        return $this;
    }

    /**
     * make UPDATE query
     *
     * @param $columns array
     *
     * @return \App\Services\Database\QueryBuilder
     */
    public function update()
    {

        return $this;
    }

    /**
     * make DELETE query
     *
     * @param $columns array
     *
     * @return \App\Services\Database\QueryBuilder
     */
    public function delete()
    {

        return $this;
    }

    /**
     * add WHERE to query
     *
     * if there is two ore more called where() added AND WHERE
     *
     * ['id', '=', '1']
     *
     * @param array $parameters
     *
     * @return \App\Services\Database\QueryBuilder
     */
    public function where(array $parameters)
    {
        $string = $parameters[0] . ' ' . $parameters[1]. ' :' . $parameters[0];

        $this->prepareParameters[$parameters[0]] = $parameters[2];

        if ($this->where === '')
        {
            $this->where .= ' WHERE ';
        }

        else if ($this->where !== '')
        {
            $string = ' AND ' . $string;
        }

        $this->where .= $string;

        return $this;
    }

    /**
     * add OR WHERE to query
     *
     * ['id', '=', '1']
     *
     * @param array $parameters
     *
     * @return \App\Services\Database\QueryBuilder
     */
    public function orWhere(array $parameters)
    {
        $string = ' OR ' . $parameters[0] . ' ' . $parameters[1]. ' :' . $parameters[0];

        $this->prepareParameters[$parameters[0]] = $parameters[2];

        $this->where .= $string;

        return $this;
    }

    /**
     * add LIMIT
     *
     * @param int $limit
     *
     * @return \App\Services\Database\QueryBuilder
     */
    public function limit(int $limit)
    {
        $this->limit = 'LIMIT ' . $limit;

        return $this;
    }

    /**
     * return array that contains 2 elements: code and message
     *
     * @return array
     */
    public function error()
    {
        return $this->error;
    }

    /**
     * return array if $this->class = null
     *
     * return object of class if $this->class != null
     */
    public function get()
    {
        if(!is_null($this->error))
        {
            return $this->error();
        }

        return $this->queryData->fetch();
    }

    /**
     * return array if $this->class = null
     *
     * return array of object of class if $this->class != null
     */
    public function all()
    {
        if(!is_null($this->error))
        {
            return $this->error();
        }

        return $this->queryData->fetchAll();
    }

    /**
     * create table with given array of columns and their parameters
     *
     * given array should be like ['column_name' => ['param_1', 'param_2']]
     *
     * @param array $array
     *
     * @return \App\Services\Database\QueryBuilder
     */

}