<?php

spl_autoload_register(function ($class_name) {
    $path = './';
    $path .= str_replace('\\', "/", "{$class_name}.php");
    if(file_exists($path)){
        include $path;
    }
});


$categoriesInArray = \models\entity\Category::repository()->find()
    ->select('*')
    ->where('id = 1')
    ->andWhere("name = 'test'")
    ->asArray()
    ->all()
;
