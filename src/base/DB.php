<?php
namespace Base;
class DB
{
    private static $db;
    /**
     * 
     * @return \PDO
     */
    public static function getInstance()
    {
        if(self::$db) {
            return self::$db;
        }
        self::$db = new \PDO(
            getenv('DB_ADAPTER').':host='.getenv('DB_HOST').';port='.getenv('DB_PORT').';dbname='.getenv('DB_NAME').'',
            getenv('DB_USER'),
            getenv('DB_PASSWORD')
        );
        return self::$db;
    }
}
