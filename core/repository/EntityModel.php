<?php

namespace core\repository;

use core\Model;

abstract class EntityModel extends Model
{
    abstract public function getId(): ? int;

    public function __set($name, $value): void
    {
        $name = str_replace('_', '', lcfirst(ucwords($name, '_')));
        parent::__set($name, $value);
    }

    public function getProperties(): array
    {
        $properties = [];
        $attributes = $this->getAttributes();
        foreach ($attributes as $propertyName => $val){
            preg_match_all(
                '/[A-Z][^A-Z]*?/Usu',
                ucfirst($propertyName),
                $incompletePropertyName
            );

            $propertyNameInTable = strtolower(implode('_', $incompletePropertyName[0]));
            $properties[$propertyNameInTable] = $val;
        }

        return $properties;
    }

    public static function repository(): MainRepository
    {
        static $repositories = [];

        if(get_class() == get_called_class()){
            self::trace('Cannot be used this mode', get_called_class(), 'Need to be called via late static binding!');
        }

        $modelName = explode('\\', get_called_class());

        $entityModelName = array_pop($modelName);

        if(!array_key_exists($entityModelName, $repositories)){
            $repositoryName = 'models\\repository\\' . $entityModelName . 'Repository';

            if(!class_exists($repositoryName)) {
                self::trace('Don`t found repository', $repositoryName);
            }

            $repositories[$entityModelName] = new $repositoryName;
        }

        return $repositories[$entityModelName];
    }
}
