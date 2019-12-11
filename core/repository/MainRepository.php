<?php

namespace core\repository;

use core\query\SelectQueryBuilder;

abstract class MainRepository implements Repository
{
    /**
     * @var string
     */
    private $tableName;

    /**
     * @var string
     */
    private $entityModelName;

    /**
     * @var string
     */
    protected $dbSchema = ActiveRecord::DATA_BASE_SQLITE;

    /**
     * @var ActiveRecord
     */
    public $activeRecord;

    public function __construct()
    {
        $this->makeTableAndEntityName();
        $this->activeRecord = new ActiveRecord($this);
    }

    /**
     * @return SelectQueryBuilder
     */
    public function find(): SelectQueryBuilder
    {
        return $this->activeRecord
            ->getSelectQueryBuilder()
        ;
    }

    /**
     * @param int $id
     * @return EntityModel|null
     */
    public function findById(int $id): ? EntityModel
    {
        $queryBuilder = $this->activeRecord
            ->getSelectQueryBuilder()
            ->where('id = ' . (int) $id)
        ;

        return $queryBuilder->one();
    }

    /**
     * @param EntityModel $entity
     * @return EntityModel
     */
    public function push(EntityModel $entity): EntityModel
    {
        if($entity->id){
            $this->activeRecord->getUpdateQueryBuilder($entity);
        } else {
            $this->activeRecord->getInsertQueryBuilder($entity);
        }

        return $entity;
    }

    /**
     * @param EntityModel $entity
     */
    public function delete(EntityModel $entity)
    {
        if($entity->getId()){
            $query = "DELETE FROM `{$this->getTableName()}` WHERE `{$this->getTableName()}`.`id` = {$entity->getId()}";
            $this->activeRecord->getDataBase()->query($query);
        }
    }

    /**
     * @return string
     */
    public function getTableName(): string
    {
        return $this->tableName;
    }

    /**
     * @return string
     */
    public function getDbSchemas(): string
    {
        return $this->dbSchema;
    }

    /**
     * @return null|string
     */
    public function getEntityModelName(): ? string
    {
        return $this->entityModelName;
    }

    private function makeTableAndEntityName(): void
    {
        static $config = [];

        $repositoryName = get_called_class();

        if(!array_key_exists($repositoryName, $config)){

            $tableName = explode('\\', get_called_class());

            preg_match_all(
                '/[A-Z][^A-Z]*?/Usu',
                array_pop($tableName),
                $incompleteTableName
            );

            $incompleteTableName = array_slice($incompleteTableName[0], 0,-1);

            $config[$repositoryName] = [
                'entityModelName' => 'models\\entity\\' . implode('', $incompleteTableName),
                'tableName' => strtolower(
                    implode('_', $incompleteTableName)
                ),
            ];
        }

        $this->entityModelName = $config[$repositoryName]['entityModelName'];
        $this->tableName = $config[$repositoryName]['tableName'];
    }
}
