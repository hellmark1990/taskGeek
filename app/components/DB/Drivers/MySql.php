<?php

namespace app\components\DB\Drivers;

use app\components\DB\Drivers\DrverInterfaces\IDrive;

class MySql implements IDrive{

    private $configKeys = [
        'hostname',
        'username',
        'database',
    ];

    private $connection;
    private $pdoObject;
    private $whereConectors = ['AND', 'OR'];

    public function __construct(array $config) {
        $this->connect($config);
    }

    public function connect(array $config) {
        $this->validateConfig($config);

        $dbName = $config['database'];
        $dbHost = $config['host'];
        $dbUsername = $config['username'];
        $dbpPassword = $config['password'] ? $config['password'] : '';

        $dsn = "mysql:dbname=$dbName;host=$dbHost";

        try {
            $this->connection = new \PDO($dsn, $dbUsername, $dbpPassword);
        } catch (\PDOException $e) {
            echo 'Can not connect to DB: ' . $e->getMessage();
        }
    }

    private function validateConfig($config) {
        foreach ($this->configKeys as $key => $keyName) {
            if (!$config[$keyName]) {
                throw new \Exception("Not valid configuration array. Key $keyName not exists.");
            }
        }
    }

    public function insert($table, array $data) {
        $columns = implode(', ', array_keys($data));
        $values = array_map(function ($column) {
            return ":$column";
        }, array_keys($data));

        $insertData = array_combine($values, array_values($data));
        $values = implode(', ', $values);

        $this->pdoObject = $this->connection->prepare("INSERT INTO $table ($columns) VALUES ($values)");
        $this->pdoObject->execute($insertData);

        return $this->connection->lastInsertId();
    }

    public function select($table, array $where = [], array $columns = []) {

        if (is_array($columns) && $columns) {
            $columns = array_map(function ($column) {
                return "`$column`";
            }, $columns);

            $columns = implode(', ', $columns);
        } else {
            $columns = '*';
        }

        $whereExpression = $this->prepereWhereExpresion($where);

        $query = trim("SELECT $columns FROM `$table` $whereExpression");
        $this->pdoObject = $this->connection->prepare($query);
        $this->pdoObject->execute();

        return $this;
    }

    public function update($table, array $where = [], array $data) {
        $setExpression = [];
        foreach ($data as $columnName => $value) {
            $setExpression[] = "`$columnName`='$value'";
        }
        $setExpression = implode(', ', $setExpression);

        $whereExpression = $this->prepereWhereExpresion($where);

        $query = trim("UPDATE `$table` SET $setExpression $whereExpression");

        $this->pdoObject = $this->connection->prepare($query);
        $this->pdoObject->execute();

        return $this->pdoObject->rowCount();
    }

    public function delete($table, array $where = []) {
        $whereExpression = $this->prepereWhereExpresion($where);
        $query = trim("DELETE FROM `$table` $whereExpression");

        $this->pdoObject = $this->connection->prepare($query);
        $this->pdoObject->execute();
        return $this->pdoObject->rowCount();
    }

    protected function prepereWhereExpresion(array $where = []) {
        $whereExpression = '';;
        foreach ($where as $columnName => $value) {
            if (in_array($value, $this->whereConectors)) {
                $whereExpression .= " $value ";
                continue;
            }
            $whereExpression .= " `$columnName` $value";
        }
        return $whereExpression ? " WHERE $whereExpression" : '';
    }

    public function findAll() {
        return $this->pdoObject->fetchAll();
    }

    public function findOne() {
        return $this->pdoObject->fetch(\PDO::FETCH_ASSOC);
    }
}