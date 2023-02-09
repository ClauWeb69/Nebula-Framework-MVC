<?php
namespace Helper\Connection\Database;

use \PDO; 

class PDO_Helper {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $password = DB_PASSW;
    private $dbname = DB_DATABASE;
    private $charset = DB_CHARSET;

    private static $pdo = false;
    private $stmt;
    private $error;

    public function __construct() {
        if(static::$pdo == false){
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->dbname . ";charset=" . $this->charset;
            $options = [
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT
            ];

            try {
                static::$pdo = new PDO($dsn, $this->user, $this->password, $options);
            } catch (\PDOException) {
            }
        }
    }

    public function query($sql) {
        $this->stmt = static::$pdo->prepare($sql);
    }

    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    public function execute() {
        return $this->stmt->execute();
    }

    public function results() {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function single() {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    public function rowCount() {
        return $this->stmt->rowCount();
    }
}

?>