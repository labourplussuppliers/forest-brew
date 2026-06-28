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
    private $host;
    private $dbname;
    private $username;
    private $password;

    private static $connection = null;

    public function __construct()
    {
        $this->host = getenv('DB_HOST') ?: 'localhost';
        $this->dbname = getenv('DB_NAME') ?: 'frost_brew';
        $this->username = getenv('DB_USER') ?: 'root';
        $this->password = getenv('DB_PASS') ?: '';
    }

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

                $this->ensureSchema();
                $this->ensureAdminUser();

            } catch (PDOException $e) {

                if ($this->isDatabaseMissing($e)) {
                    $this->createDatabase();
                    return $this->connect();
                }

                die("Database Connection Failed : " . $e->getMessage());

            }

        }

        return self::$connection;
    }

    private function ensureAdminUser(): void
    {
        try {
            $check = self::$connection->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
            $check->execute(['admin@frostbrew.com']);

            if ($check->fetch()) {
                return;
            }

            $passwordHash = password_hash('Admin@123', PASSWORD_DEFAULT);

            $insert = self::$connection->prepare("
                INSERT INTO users (role_id, first_name, last_name, username, email, password, status, created_at)
                VALUES (:role_id, :first_name, :last_name, :username, :email, :password, :status, NOW())
            ");

            $insert->execute([
                ':role_id' => 2, // Admin role (created by schema)
                ':first_name' => 'Frost',
                ':last_name' => 'Brew',
                ':username' => 'admin',
                ':email' => 'admin@frostbrew.com',
                ':password' => $passwordHash,
                ':status' => 'Active'
            ]);

        } catch (PDOException $e) {
            // If users table doesn't exist yet or any error occurs, silently continue
        }
    }

    private function isDatabaseMissing(PDOException $e): bool
    {
        return str_contains($e->getMessage(), 'Unknown database') || str_contains($e->getMessage(), '1049');
    }

    private function createDatabase(): void
    {
        try {
            $connection = new PDO(
                "mysql:host={$this->host};charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );

            $connection->exec("CREATE DATABASE IF NOT EXISTS `{$this->dbname}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        } catch (PDOException $e) {
            die("Unable to create database `{$this->dbname}`: " . $e->getMessage());
        }
    }

    private function ensureSchema(): void
    {
        $schemaPath = __DIR__ . '/../database/database.sql';

        if (!file_exists($schemaPath)) {
            return;
        }

        $sql = file_get_contents($schemaPath);

        if ($sql === false) {
            return;
        }

        $queries = array_filter(array_map('trim', preg_split('/;\s*\r?\n/', $sql)));

        foreach ($queries as $query) {
            try {
                self::$connection->exec($query);
            } catch (PDOException $e) {
                continue;
            }
        }
    }
}

$database = new Database();

$conn = $database->connect();