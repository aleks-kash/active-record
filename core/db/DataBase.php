<?php

namespace core\db;


interface DataBase
{
    public function query(string $query);

    public function queryAll(string $query);

    public function queryOne(string $query);
}
