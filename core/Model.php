<?php

namespace core;

abstract class Model
{
    /**
     * @var array
     */
    private $attributes = [];

    public function loadingData(array $data): void
    {
        foreach ($data as $property => $val){

            if(is_array($val)){
                continue;
            }

            $this->checkPropertyRule($property);
            $this->attributes[$property] = $val;
        }
    }

    public function __get(string $name)
    {
        $this->checkPropertyRule($name);
        return $this->attributes[$name] ?? null;
    }

    public function __set(string $name, $value): void
    {
        $this->checkPropertyRule($name);
        $this->attributes[$name] = $value;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }
    public function __isset(string $name)
    {
        return method_exists($this, 'get' . ucfirst($name));
    }

    protected static function trace($text, $name, string $notice = null): void
    {
        $trace = debug_backtrace();
        $in_line = $trace[1]['line'];
        $in_file = $trace[1]['file'];
        $in_file = explode('\\', $in_file);
        array_push($in_file, '<b>' . array_pop($in_file) . '</b>');
        $in_file = implode('\\', $in_file);
        trigger_error(
            "
                <p>$text: <b>\"$name\"</b> $notice</p>
                <p>in file $in_file on line <b>$in_line</b></p>
                </br>
                </br>
                </br>
                <h4><b>Trace:</b><h5>
                ");
        exit;
    }

    protected function checkPropertyRule(string $propertyName): bool
    {
        if(!method_exists($this, 'get' . ucfirst($propertyName))){
            self::trace("Undefined property", $propertyName);
        }

        return true;
    }
}
