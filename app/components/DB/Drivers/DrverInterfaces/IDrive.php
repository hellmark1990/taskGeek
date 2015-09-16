<?php

namespace app\components\DB\Drivers\DrverInterfaces;

interface IDrive {
    public function connect(array $config);

    public function insert($table, array $data);

    public function select($table, array $where = [], array $columns = []);

    public function update($table, array $where = [], array $data);

    public function delete($table, array $where = []);

    public function findAll();

    public function findOne();
}