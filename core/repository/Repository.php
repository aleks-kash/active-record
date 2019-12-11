<?php

namespace core\repository;

interface Repository
{
    /**
     * @return string
     */
    public function getTableName(): string;

    /**
     * @return string
     */
    public function getDbSchemas(): string;

    /**
     * @return null|string
     */
    public function getEntityModelName(): ? string;
}
