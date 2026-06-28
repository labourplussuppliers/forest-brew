<?php
/**
 * ----------------------------------------------------------
 * Frost & Brew
 * Database Configuration
 * ----------------------------------------------------------
 * Author : Frost & Brew
 * Version: 1.0
 */

date_default_timezone_set('Asia/Karachi');

class Database
{
    private $host = "localhost";
    private $dbname = "frost_brew";
    private $username = "root";
    private $password = "";

    private static $connection = null;

    public function connect()
    {
        if (self::$connection === null) {

            try {

                self::$connection = new PDO(

                    "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4",

                    $this->username,

                    $this->password,

                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,

                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

                        PDO::ATTR_EMULATE_PREPARES => false
                    ]

                );

            } catch (PDOException $e) {

                die("Database Connection Failed : " . $e->getMessage());

            }

        }

        return self::$connection;
    }
}

$database = new Database();

$conn = $database->connect();