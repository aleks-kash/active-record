<?php

namespace core\repository\traits;

use core\repository\EntityModel;
use core\query\UpdateQueryBuilder;

trait queryBuilderUpdate
{
    use queryBuilder;

    public function getUpdateQueryBuilder(EntityModel $entityModel)
    {
        return (new UpdateQueryBuilder($this->repository, $this->dataBase, $entityModel))
            ->sendQuery()
        ;
    }
}
