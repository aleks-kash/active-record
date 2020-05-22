<?php

namespace core\repository;

use core\db\DataBase;
use core\db\DataBaseMySQL;
use core\db\DataBaseSQLite;
use core\repository\traits\queryBuilderInsert;
use core\repository\traits\queryBuilderSelect;
use core\repository\traits\queryBuilderUpdate;

class ActiveRecord
{
    const DATA_BASE_MYSQL = DataBaseMySQL::class;
    const DATA_BASE_SQLITE = DataBaseSQLite::class;

    use queryBuilderInsert;
    use queryBuilderSelect;
    use queryBuilderUpdate;

    public function __construct(Repository $repository)
    {
        static $dataBases = [
            self::DATA_BASE_SQLITE,
            self::DATA_BASE_MYSQL,
        ];

        if(is_bool($schemasKey = array_search($repository->getDbSchemas(), $dataBases))) {
            $schemasKey = 0;
        }

        $this->dataBase = new $dataBases[$schemasKey]();
        $this->repository = $repository;
    }

    /**
     * @return DataBase
     */
    public function getDataBase()
    {
        return $this->dataBase;
    }
}
