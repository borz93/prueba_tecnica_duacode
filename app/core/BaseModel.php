<?php
declare(strict_types=1);

namespace app\core;

use PDO;
use PDOException;

abstract class BaseModel {
    protected static string $tabla;
    protected static array $campos = [];
    protected array $atributos = [];

    /**
     * Constructor to initialize model attributes.
     *
     * @param array $data Associative array of model attributes.
     */
    public function __construct(array $data = []) {
        $this->atributos = $data;
    }

    /**
     * Saves the model: Inserts if new, updates if existing.
     *
     * @return bool True on success, false on failure.
     */
    public function save(): bool {
        try {
            return empty($this->atributos['id'])
                ? $this->insert()
                : $this->update();
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Sets the model attributes from an associative array.
     *
     * @param array $data
     * @return void
     */
    public function setAttributes(array $data): void {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
            $this->atributos[$key] = $value;
        }
    }

    /**
     * Inserts a new record into the database.
     *
     * @return bool True on success, false on failure.
     */
    protected function insert(): bool {
        $db = self::getDB();
        $columns = implode(', ', static::$campos);
        $placeholders = implode(', ', array_map(fn($c) => ":$c", static::$campos));

        $sql = "INSERT INTO " . static::$tabla . " ($columns) VALUES ($placeholders)";
        $stmt = $db->prepare($sql);

        foreach (static::$campos as $campo) {
            $value = $this->atributos[$campo] ?? null;

            if (is_bool($value)) {
                $stmt->bindValue(":$campo", $value, PDO::PARAM_BOOL);
            } else {
                $stmt->bindValue(":$campo", $value);
            }
        }

        $success = $stmt->execute();
        if ($success) {
            $this->atributos['id'] = (int) $db->lastInsertId();
        }

        return $success;
    }

    /**
     * Updates an existing record in the database.
     *
     * @return bool True on success, false on failure.
     */
    protected function update(): bool {
        if (empty($this->atributos['id'])) {
            return false;
        }

        $db = self::getDB();
        $set = implode(', ', array_map(fn($c) => "$c = :$c", static::$campos));
        $sql = "UPDATE " . static::$tabla . " SET $set WHERE id = :id";

        $stmt = $db->prepare($sql);
        foreach (static::$campos as $campo) {
            $value = $this->atributos[$campo] ?? null;

            if (is_bool($value)) {
                $stmt->bindValue(":$campo", $value, PDO::PARAM_BOOL);
            } else {
                $stmt->bindValue(":$campo", $value);
            }
        }
        $stmt->bindValue(':id', $this->atributos['id'], PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Deletes the current record from the database.
     *
     * @return bool True on success, false on failure.
     */
    public function delete(): bool {
        if (empty($this->atributos['id'])) {
            return false;
        }

        try {
            $stmt = self::getDB()->prepare("DELETE FROM " . static::$tabla . " WHERE id = :id");
            return $stmt->execute([':id' => $this->atributos['id']]);
        } catch (PDOException $e) {
            error_log("Error deleting record: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Finds a record by ID.
     *
     * @param int $id The ID of the record.
     * @return static|null The found record as an object, or null if not found.
     */
    public static function find(int $id): ?static {
        if ($id <= 0) {
            return null;
        }

        $stmt = self::getDB()->prepare("SELECT * FROM " . static::$tabla . " WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);

        return ($data = $stmt->fetch(PDO::FETCH_ASSOC)) ? new static($data) : null;
    }

    /**
     * Retrieves all records from the database.
     *
     * @return static[] An array of model instances.
     */
    public static function all(): array {
        $stmt = self::getDB()->query("SELECT * FROM " . static::$tabla);
        return array_map(fn($data) => new static($data), $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    /**
     * Provides the database connection.
     *
     * @return PDO The database connection instance.
     */
    protected static function getDB(): PDO {
        return Database::getConnection();
    }
}
