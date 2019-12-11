<?php

namespace models\repository;

use core\repository\ActiveRecord;
use core\repository\MainRepository;

class CategoryRepository extends MainRepository
{
    /**
     * @var string
     */
    protected $dbSchema = ActiveRecord::DATA_BASE_MYSQL;
}
