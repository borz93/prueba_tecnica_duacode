<?php
declare(strict_types=1);

namespace app\core;

use PDO;
use PDOException;
use RuntimeException;

class Database {
    private static ?PDO $connection = null;

    private function __construct() {}

    /**
     * Returns a single PDO instance (Singleton).
     */
    public static function getConnection(): PDO {
        if (self::$connection === null) {
            try {
                $dsn = sprintf(
                    'mysql:host=%s;dbname=%s;charset=utf8mb4',
                    getenv('DB_HOST') ?: 'db',
                    getenv('DB_NAME') ?: 'equipos_db'
                );

                self::$connection = new PDO(
                    $dsn,
                    getenv('DB_USER') ?: 'root',
                    getenv('DB_PASS') ?: 'root',
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ]
                );
            } catch (PDOException $e) {
                error_log("[Database] Connection error: " . $e->getMessage());
                throw new RuntimeException("Database connection failed.");
            }
        }
        return self::$connection;
    }
}
