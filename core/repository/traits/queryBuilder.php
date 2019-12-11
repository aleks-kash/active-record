<?php

namespace core\repository\traits;

use core\db\DataBase;
use core\repository\Repository;

trait queryBuilder
{
    /** @var Repository  */
    private $repository;

    /** @var DataBase  */
    private $dataBase;
}
