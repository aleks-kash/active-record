<?php

namespace core\query;

use core\db\DataBase;
use core\repository\EntityModel;
use core\repository\Repository;

class InsertQueryBuilder implements QueryBuilder
{
    /** @var DataBase  */
    private $dataBase;

    /** @var string */
    private $tableName;

    /** @var EntityModel */
    private $entityModel;

    public function __construct(Repository $repository, DataBase $dataBase, EntityModel $entityModel)
    {
        $this->dataBase = $dataBase;
        $this->tableName = $repository->getTableName();
        $this->entityModel = $entityModel;
    }

    public function makeQuery(): string
    {
        $properties = $this->entityModel->getProperties();

        return str_replace(
            [
                ':properties',
                ':values',
            ],
            [
                implode('`, `', array_keys($properties)),
                implode("', '", $properties),
            ],
            implode(' ', [
                'INSERT',
                'INTO',
                $this->tableName,
                '(`:properties`)',
                'VALUES',
                "(':values')",
            ])
        );
    }

    public function sendQuery()
    {
        return $this->dataBase->query($this->makeQuery());
    }
}
