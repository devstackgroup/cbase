<?php

namespace CBase\Query;

use CBase\Database\Mysql\Mysql;
use \Exception;

/**
* Query class
*/
class Query
{
    private $pdo = null;
    private $table = null;
    private $rowCount = 0;
    private $sqlQuery = null;
    private $sqlAttributes = [];

    private $pageNumbers = 0;
    private $currentPage = null;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = new Mysql($pdo);
    }

    public function create($fields)
    {
        $fieldColumn = [];
        $fieldValue  = [];

        foreach ($fields as $key => $value) {
            $fieldColumn[] = "$key = ?";
            $fieldValue[] = $value;
        }

        $fieldColumn = implode(',', $fieldColumn);
        $this->sqlQuery = "INSERT {$this->table} SET $fieldColumn";

        try {
            return $this->query($this->sqlQuery, $fieldValue, false);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function read($column = '*')
    {
        if (is_array($column)) {
            $column = implode(',', $column);
        }
        
        $table = "FROM {$this->table}";
        $this->sqlQuery = "SELECT {$column} {$table}";

        return $this;
    }

    public function update($fields)
    {
        $fieldColumn = [];
        $fieldValue  = [];

        foreach ($fields as $key => $value) {
            $fieldColumn[] = "$key = ?";
            $fieldValue[] = $value;
        }

        $fieldColumn = implode(',', $fieldColumn);
        $this->sqlQuery = "UPDATE {$this->table} SET $fieldColumn";
        $this->sqlAttributes = $fieldValue;

        return $this;
    }

    public function delete()
    {
        $this->sqlQuery = "DELETE FROM {$this->table}";

        return $this;
    }

    public function get(array $fetchAttributes = null, array $executeAttributes = null)
    {
        try {
            if (!empty($fetchAttributes)) {
                switch (count($fetchAttributes)) {
                    case 2:
                        return $this->query($this->sqlQuery, $executeAttributes, $fetchAttributes['all'], $fetchAttributes['fetch']);
                    case 1:
                        return $this->query($this->sqlQuery, $executeAttributes, $fetchAttributes['all']);
                    default:
                        return $this->query($this->sqlQuery);
                }
            }

            return $this->query($this->sqlQuery);
        } catch (Exception $e) {
            return [$e->getMessage()];
        }
    }

    public function exec()
    {
        try {
            return $this->query($this->sqlQuery, $this->sqlAttributes, false);
        } catch (Exception $e) {
            return [$e->getMessage()];
        }
    }

    public function limit($limit)
    {
        $perPage = $limit;

        $this->pageNumbers = $limit = ceil($this->rowCount / $perPage);
        $this->currentPage = (isset($_GET['page']) && ($_GET['page'] > 0) && ($_GET['page'] <= $limit)) ? $_GET['page'] : 1;
        $this->sqlQuery .= " LIMIT ".(($this->currentPage - 1) * $perPage)." , {$perPage}";

        return $this;
    }

    public function orderBy($orders)
    {
        foreach ($orders as $key => $value) {
            if (is_numeric($key)) {
                $queryOrders[] = $value;
            } else {
                $queryOrders[] = $key.' '.$value;
            }
        }

        $this->sqlQuery .=' ORDER BY '.implode(',', $queryOrders);

        return $this;
    }

    public function where($conditions, $or = false)
    {
        foreach ($conditions as $key => $value) {
            if (is_numeric($key)) {
                $fieldConditions[] = $value;
            } else {
                $fieldConditions[] = $key.' = '.$value;
            }
        }

        if (isset($or)) {
            $this->sqlQuery .= ' WHERE '.implode(' OR ', $fieldConditions);

            return $this;
        }

        $this->sqlQuery .= ' WHERE '.implode(' AND ', $fieldConditions);

        return $this;
    }

    public function setTable($table)
    {
        $this->table = $table;
    }

    public function query($statement, array $attributes = null, $fetchAll = true, $fetchMode = 'obj')
    {
        if (!$this->pdo->isConnected()) {
            throw new Exception('No connected to database');
        }

        if (!empty($attributes)) {
            $executeResponse = $this->pdo
                                    ->prepare($statement, $attributes, $fetchAll, $fetchMode);
        } else {
            $executeResponse = $this->pdo
                                    ->query($statement, $fetchAll, $fetchMode);
        }

        if (is_bool($executeResponse)) {
            return $executeResponse;
        }
        
        return $executeResponse->get();
    }

    public function rowCount()
    {
        return $this->pdo
                    ->getRowCount();
    }

    public function LastInsertId()
    {
        return $this->pdo
                    ->getLastInsertId();
    }

    public function close()
    {
        return $this->pdo->disconnect();
    }
}
