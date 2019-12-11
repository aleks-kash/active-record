<?php
/*
* SQLite3 Class
* based on the code of miquelcamps
* @see http://7devs.com/code/view.php?id=67
*/

namespace core\db;

use core\App;

class DataBaseSQLite implements DataBase{

    /** @var \SQLite3  */
    private $sqlite;

    /** @var int  */
    private $mode;

    /**
     * ActiveQuerySQLite constructor.
     * @param $filename
     * @param int $mode
     */
    public function __construct()
    {
        $this->mode = SQLITE3_ASSOC;
        $this->sqlite = new \SQLite3(App::config()->getParams('app_patch')
            . App::config()->getParams('db_patch')
        );
    }

    public function __destruct()
    {
        @$this->sqlite->close();
    }

    public function clean(string $str): string
    {
        return $this->sqlite->escapeString($str);
    }

    public function query(string $query)
    {
        $res = $this->sqlite->query($query);
        if ( !$res ){
            new \Exception($this->sqlite->lastErrorMsg());
        }
        return $res;
    }

    /**
     * @param $query
     * @return array
     * @throws \Exception
     */
    public function queryRow(string $query)
    {
        $res = $this->query($query);
        $row = $res->fetchArray($this->mode);
        return $row;
    }

    public function queryOne(string $query): array
    {
        return $this->sqlite->querySingle($query, true);
    }

    public function queryAll(string $query)
    {
        $rows = array();
        if( $res = $this->query($query) ){
            while($row = $res->fetchArray($this->mode)){
                $rows[] = $row;
            }
        }
        return $rows;
    }

    /**
     * @return int
     */
    public function getLastInsertId(): int
    {
        return $this->sqlite->lastInsertRowID();
    }
}
