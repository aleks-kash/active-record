<?php

namespace core\query;

use core\db\DataBase;
use core\repository\Repository;

interface QueryBuilder
{
    public function makeQuery(): string;
}
