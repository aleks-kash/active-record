<?php

namespace core;

use conf\Config;

class App
{
    /** @var array */
    private static $data = [];

    public function __construct()
    {
        self::$data = [
            'config' => new Config(),
        ];
    }

    public static function config(): ? Config
    {
        return self::$data['config'];
    }

    public static function currentDateTime(): ? \DateTime
    {
        return (new \DateTime('now', new \DateTimeZone(self::config()->getParams('date_time_zone'))));
    }
}
