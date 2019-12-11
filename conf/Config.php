<?php

namespace conf;

class Config
{
    private $params;

    public function __construct()
    {
        $path = 'conf/params.php';
        $this->params = include $path;
    }

    public function getParams($paramKey)
    {
           return $this->params[$paramKey];
    }
}
