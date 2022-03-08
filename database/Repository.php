<?php

namespace Database;

use Exception;

class Repository
{
    // Table name to manipulate
    private $table;

    public function __construct($table = null)
    {
        $this->table = $table;
    }


    public function execute($query, $params = [])
    {
        if ($conn = Transaction::get()) 
        {
            $statement = $conn->prepare($query);
            $statement->execute($params);

            return $statement;
        }
        else 
        {
            throw new Exception('Does not active transaction!');
        }
    }


    public function select($where = null, $order = null, $limit = null, $fields = '*')
    {
        $where = strlen($where) ? "WHERE {$where}" : '';
        $order = strlen($order) ? "ORDER BY {$order}" : '';
        $limit = strlen($limit) ? "LIMIT {$limit}" : '';

        $query = "SELECT {$fields} FROM {$this->table} {$where} {$order} {$limit}";

        return $this->execute($query);
    }


    public function insert($values)
    {
        $fields = array_keys($values);
        $binds = array_pad([], count($fields), '?');

        $query = "INSERT INTO {$this->table} (" . implode(',', $fields) . ") VALUES ("
            . implode(',', $binds) . ")";

        $this->execute($query, array_values($values));

        return Transaction::get()->lastInsertId();
    }


    public function update($where, $values)
    {
        $fields = array_keys($values);

        $query = "UPDATE {$this->table} SET " . implode('=?,', $fields) . "=? WHERE {$where}";

        return $this->execute($query, array_values($values))
            ? true
            : false;
    }


    public function delete($where)
    {
        $query = "DELETE FROM {$this->table} WHERE {$where}";

        return $this->execute($query)
            ? true
            : false;
    }
}