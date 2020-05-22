<?php

namespace core\query;

use core\db\DataBase;
use core\repository\EntityModel;
use core\repository\Repository;

class SelectQueryBuilder implements QueryBuilder
{
    /** @var SelectQueryModel  */
    private $queryModel;

    /** @var DataBase  */
    private $dataBase;

    /** @var string */
    private $tableName;

    /** @var string */
    private $entityModelName;

    /** @var bool */
    private $asArray = false;

    public function __construct(Repository $repository, DataBase $dataBase)
    {
        $this->dataBase = $dataBase;
        $this->tableName = $repository->getTableName();
        $this->entityModelName = $repository->getEntityModelName();
        $this->queryModel = new SelectQueryModel();
    }

    /**
     * @param mixed $select The query restrictions.
     * @return $this
     */
    public function select()
    {
        return $this->addMultiple('select', func_get_args());
    }

    /**
     * @param mixed $where The query restrictions.
     * @return $this
     */
    public function where(): self
    {
        $this->queryModel->setWhere();

        if(func_num_args() == 0){
            return $this;
        }

        $args  = func_get_args();

        if(is_array($args[0])){
            foreach ($args as $key => $arg){
                $this->queryModel->setWhere(is_string($key) ? ($key . ' = ' . $arg) : $arg);
            }
        } else {
            $this->queryModel->setWhere($args[0]);
        }

        return $this;
    }

    /**
     * @param mixed $where The query restrictions.
     * @return $this
     */
    public function andWhere()
    {
        if(func_num_args() == 0){
            return $this;
        }

        $args  = func_get_args();

        if(is_array($args[0])){
            foreach ($args as $key => $arg){
                $this->queryModel->setWhere(
                    $this->queryModel->getWhere() ? ' AND ' : ''
                        . (is_array($key) ? ($key . ' = ' . $arg) : $arg))
                ;
            }
        } else {
            $this->queryModel->setWhere(($this->queryModel->getWhere() ? ' AND ' : '') . $args[0]);
        }

        return $this;
    }

    public function limit(int $limit)
    {
        $this->queryModel->setLimit($limit);

        return $this;
    }

    /**
     * @return $this
     */
    public function orderBy()
    {
        return $this->addMultiple('orderBy', func_get_args());
    }

    /**
     * @param mixed $groupBy The query restrictions.
     * @return $this
     */
    public function groupBy()
    {
        return $this->addMultiple('groupBy', func_get_args());
    }

    public function asArray()
    {
        $this->asArray = true;

        return $this;
    }

    /**
     * @return array|EntityModel|null
     */
    public function one()
    {

        $response = $this->dataBase->queryOne($this->makeQuery());

        if($this->asArray){
            return $response;
        }

        return $response ? $this->makeEntityModel($response) : null;
    }

    /**
     * @return array[EntityModel]|null
     */
    public function all()
    {
        $response = $this->dataBase->queryAll($this->makeQuery());

        if($this->asArray){
            return $response;
        }

        $entityModels = [];
        if ($response) {
            foreach($response as $value){
                $entityModels[$value['id']] = $this->makeEntityModel($value);
            }
        }

        return $entityModels;
    }

    public function makeQuery(): string
    {
        return implode(' ', [
            $this->queryModel->getSelect(),
            'FROM',
            $this->tableName,
            $this->queryModel->getWhere(),
            $this->queryModel->getOrderBy(),
            $this->queryModel->getGroupBy(),
            $this->queryModel->getLimit(),
        ]);
    }

    private function addMultiple(string $queryPartName, $args)
    {
        $DQLPartSet = 'set' . ucfirst($queryPartName);

        if(!$args
            || !method_exists($this->queryModel, $DQLPartSet)
        ) {

            return $this;
        }

        if(is_array($args)){
            foreach ($args as $arg){
                $this->queryModel->$DQLPartSet($arg);
            }
        } else {
            $this->queryModel->$DQLPartSet($args);
        }

        return $this;
    }

    private function makeEntityModel(array $data): ? EntityModel
    {
        $properties = [];
        foreach ($data as $propertyKey => $propertyVal){
            $property = ucwords($propertyKey, '_');
            $property[0] = strtolower($property[0]);
            $properties[str_replace('_', '', $property)] = $propertyVal;
        }

        /** @var EntityModel $entity */
        $entity = new $this->entityModelName;
        $entity->loadingData($properties);

        return $entity;
    }
}
