<?php

namespace core\repository\traits;

use core\query\InsertQueryBuilder;
use core\repository\EntityModel;

trait queryBuilderInsert
{
    use queryBuilder;

    public function getInsertQueryBuilder(EntityModel $entityModel)
    {
        return (new InsertQueryBuilder($this->repository, $this->dataBase, $entityModel))
            ->sendQuery()
        ;
    }
}
