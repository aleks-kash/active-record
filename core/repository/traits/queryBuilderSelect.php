<?php

namespace core\repository\traits;

use core\query\SelectQueryBuilder;

trait queryBuilderSelect
{
    use queryBuilder;

    public function getSelectQueryBuilder(): SelectQueryBuilder
    {
        return new SelectQueryBuilder($this->repository, $this->dataBase);
    }
}
