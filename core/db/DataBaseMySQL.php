<?php
/*
* SQLite3 Class
* based on the code of miquelcamps
* @see http://7devs.com/code/view.php?id=67
*/

namespace core\db;

use core\App;
use models\entity\PredictionCategory;

class DataBaseMySQL implements DataBase
{
    /** @var \mysqli  */
    private $mysqliConnect;

    /**
     * DataBaseMySQL constructor.
     */
    public function __construct()
    {
        $config = App::config()->getParams('db')['my-sql'];
        $this->mysqliConnect = new \mysqli($config['host'], $config['user'], $config['pass'], $config['schemas']);

        if ($this->mysqliConnect->connect_errno) {
            new \Exception("Не удалось подключиться: \n" . $this->mysqliConnect->connect_error);
        }
    }

    public function __destruct()
    {
        @mysqli_close($this->mysqliConnect);
    }

    public function query(string $query)
    {
        if (!$result = mysqli_query($this->mysqliConnect, $query)) {
            new \Exception("Ошибка: " . mysqli_error($this->mysqliConnect));
        }

        return $result;
    }

    public function queryAll(string $query)
    {
        $result = $this->query($query);

        $results = $result->fetch_all(MYSQLI_ASSOC);

        mysqli_free_result($result);

        return $results;
    }

    public function queryOne(string $query): ? array
    {
        $result = $this->query($query);

        $results = [];
        if ($result) {
            $results = $result->fetch_assoc();
            mysqli_free_result($result);
        }

        return $results;
    }
}
