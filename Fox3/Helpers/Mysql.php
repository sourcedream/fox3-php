<?php

namespace App\Fox3\Helpers;

use mysqli;

class Mysql {
  
    /**
     * Singleton instance
     * @var Mysql
     */
    private static $instance;

    /**
     * MySQL Connection
     * @var mysqli
     */
    public $connection;

    private function __construct() {
        $this->connection = new mysqli(getenv('DBHOST') ?? 'localhost', getenv('DBUSER') ?? 'root', getenv('DBPASS') ?? '', getenv('DBNAME') ?? '');
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $this->connection->set_charset(getenv("DBCHARSET") ?? "utf8mb4");
    }

    public static function getInstance() : Mysql {
        if (is_null(Mysql::$instance)) {
            Mysql::$instance = new Mysql();
        }

        return Mysql::$instance;
    }

    public static function conn() : mysqli {
        return Mysql::getInstance()->connection;
    }
}
