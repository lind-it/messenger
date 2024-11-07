<?php

namespace App\Services\Database;

use PDO;
use PDOException;

class QueryBuilder
{
    private $table = '';
    private $connect;
    private $class;

    public function __construct(string $table, PDO $connect, $class = null)
    {
        $this->table = $table;
        $this->connect = $connect;
        $this->class = $class;
    }

    private function exec(string $query, array $data)
    {
        try
        {
            $stmt = $this->connect->prepare($query);
            $stmt->execute($data);

            if($stmt->errorCode() !== '00000')
            {
               throw new QueryBuilderError(
                    [
                        'code' => $stmt->errorInfo()[0],
                        'message' =>$stmt->errorInfo()[2]
                    ]
                );
            }

            else
            {
                return $stmt;
            }
        }

        catch (PDOException $e)
        {
            throw new QueryBuilderError(
                [
                    'code' => $e->getCode(),
                    'message' => $e->getMessage()
                ]);
        }
    }

    private function where(array $selectWhere)
    {
        $where = 'true';
        $prepareParameters = [];

        if (!is_null($selectWhere))
        {
            $where = '';

            for($i = 0; $i < count($selectWhere); $i++)
            {
                $prepareParameters[$selectWhere[$i][0]] = $selectWhere[$i][2];

                if ($where !== '')
                {
                    $where .= ' AND ' . $selectWhere[$i][0] . $selectWhere[$i][1] . ':' . $selectWhere[$i][0];
                    continue;
                }

                $where = $selectWhere[$i][0] . $selectWhere[$i][1] . ':' .  $selectWhere[$i][0];
            }
        }

        $data = [
            'where' => $where,
            'parameters' => $prepareParameters
        ];

        return $data;
    }

    /**
     * make SELECT WHERE query
     *
     * return 1 or more rows (depending on param $count) from table
     *
     * param array $where should be like [['column1', '=', '1'], ['column2', '>', '2']]
     *
     * @param array $where
     *
     * @param int $count = 1
     *
     * @return object if $this->class != null
     *
     * @return array if $this->class == null
     *
     * @return QueryBuilderError if error
     */
    public function find(array $where = null, int $count = 1)
    {
        $data = $this->where($where);

        $where = $data['where'];
        $prepareParameters = $data['parameters'];

        $query = "SELECT * FROM {$this->table} WHERE {$where} LIMIT {$count}";

        $stmt = $this->exec($query, $prepareParameters);

        if (is_null($this->class))
        {
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
        }
        else
        {
            $stmt->setFetchMode(PDO::FETCH_CLASS, $this->class);
        }

        return $stmt->fetch();
    }

    /**
     * make SELECT WHERE query
     *
     * return all rows from table
     *
     * param array $where should be like [['column1', '=', '1'], ['column2', '>', '2']]
     *
     * @param array $selectWhere = null
     *
     * @return array of objects if $this->class != null
     *
     * @return array if $this->class == null
     *
     * @return QueryBuilderError if error
     */
    public function findAll(array $where = null)
    {
        $data = $this->where($where);

        $where = $data['where'];
        $prepareParameters = $data['parameters'];

        $query = "SELECT * FROM {$this->table} WHERE {$where}";

        $stmt = $this->exec($query, $prepareParameters);

        if (is_null($this->class))
        {
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
        }
        else
        {
            $stmt->setFetchMode(PDO::FETCH_CLASS, $this->class);
        }

        return $stmt->fetchAll();
    }

    /**
     * make insert query
     *
     * return recently created row from table
     *
     * param array $data should be like [$key => $value, $key2 => $value2, ...]
     *
     * @param array $data
     *
     * @return object if $this->class != null
     *
     * @return array if $this->class == null
     *
     * @return QueryBuilderError if error
     */

    public function create(array $data)
    {
        $columns = '';
        $values = '';

        foreach ($data as $key => $value)
        {
            $columns .= $key . ', ';
            $values .= ':'. $key . ', ';

            $prepareData[$key] = $value;
        }

        $columns = rtrim($columns, ', ');
        $values = rtrim($values, ', ');

        $query = 'INSERT INTO ' . $this->table . '(' . $columns . ') VALUES (' . $values . ')';

        $this->exec($query, $prepareData);

        // преобразовываем входной массив в пригодный массив для функции $this->find
        foreach ($data as $key => $value)
        {
            $searchData[] = [$key, '=', $value];
        }
        
        return $this->find($searchData);
    }

    /**
     * make update query
     *
     * return recently updated row from table
     *
     * param array $data should be like [$key => $value, $key2 => $value2, ...]
     *
     * @param array $data
     *
     * @return object if $this->class != null
     *
     * @return array if $this->class == null
     *
     * @return QueryBuilderError if error
     */

    public function update(array $data, array $where)
    {
        foreach($data as $key => $value)
        {
            $set .= $key . ' = ' . ':'. $key . ', ';
            $prepareParameters[$key] = $value;
        }

        $set = rtrim($set, ', ');

        $whereData = $this->where($where);

        $where = $whereData['where'];
        $prepareParameters = array_merge($whereData['parameters'], $prepareParameters);

        $query = 'UPDATE ' . $this->table . ' SET ' . $set . ' WHERE ' . $where;

        $this->exec($query, $prepareParameters);

        // преобразовываем входной массив в пригодный массив для функции $this->find
        foreach ($data as $key => $value)
        {
            $searchData[] = [$key, '=', $value];
        }

        return $this->find($searchData);


    }

    /**
     * make custom query
     *
     * plug for not realised functions
     *
     * @param string $query
     *
     * @return QueryBuilderError if error
     */
    public function customQuery(string $query)
    {
        $stmt = $this->exec($query, []);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        return $stmt->fetchAll();
    }


    public function createTable(array $columns)
    {
        $columnsString = "\n";

        foreach ($columns as $column => $properties)
        {
            $columnsString .= $column . ' ' . implode(' ', $properties) . ",\n";
        }

        $columnsString = rtrim($columnsString, ",\n");

        $query = 'CREATE TABLE IF NOT EXISTS ' . $this->table . '(' . $columnsString . ')';

        try
        {
            $this->connect->query($query);

            if($this->connect->errorCode() !== '00000')
            {
                var_dump($this->connect->errorInfo());
            }

        }

        catch (PDOException $e)
        {
            echo $e;
        }

    }
}