<?php

spl_autoload_register(function ($class_name) {
    $path = 'application/';
    $path .= str_replace('\\', "/", "{$class_name}.php");
    if(file_exists($path)){
        include $path;
    }
});
