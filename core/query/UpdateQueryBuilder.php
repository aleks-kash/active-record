<?php

namespace core\query;

use core\db\DataBase;
use core\repository\EntityModel;
use core\repository\Repository;

class UpdateQueryBuilder implements QueryBuilder
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

        unset($properties['id']);

        $set = [];
        foreach ($properties as $propertyName => $propertyVal){
            $set[] = "`$propertyName` = '$propertyVal'";
        }

        return str_replace(
            [
                ':propertiesSet',
                ':propertyWhere',
            ],
            [
                implode(', ', $set),
                '`id` = ' .  $this->entityModel->id,
            ],
            implode(' ', [
                'UPDATE',
                $this->tableName,
                'SET',
                ':propertiesSet',
                "WHERE",
                ':propertyWhere',
            ])
        );
    }

    public function sendQuery()
    {
        return $this->dataBase->query($this->makeQuery());
    }
}
