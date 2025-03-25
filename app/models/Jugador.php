<?php
declare(strict_types=1);

namespace app\models;

use app\core\BaseModel;

class Jugador extends BaseModel {
    protected static string $tabla = 'jugadores';
    protected static array $campos = ['nombre', 'numero', 'equipo_id', 'es_capitan'];

    // Public properties for the Jugador entity
    public ?int $id = null;
    public ?string $nombre = null;
    public ?int $numero = null;
    public ?int $equipo_id = null;
    public ?bool $es_capitan = false;
    public ?string $created_at = null;

    /**
     * Constructor for Jugador.
     *
     * @param array $data Data to initialize the Jugador.
     */
    public function __construct(array $data = []) {
        parent::__construct($data);
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = ($key === 'es_capitan') ? (bool)$value : $value;
            }
        }
    }

    public function save(): bool {
        $this->beforeSave();
        return parent::save();
    }

    protected function beforeSave(): void {
        if ($this->atributos['es_capitan'] ?? false) {
            $this->removeOtherCaptains();
        }
    }

    /**
     * Remove other captains if exits, for prevention
     */
    private function removeOtherCaptains(): void {
        $db = self::getDB();
        $stmt = $db->prepare("
            UPDATE jugadores 
            SET es_capitan = 0 
            WHERE equipo_id = :equipo_id 
            AND id != :id_exclude
        ");
        $stmt->execute([
            ':equipo_id' => $this->atributos['equipo_id'],
            ':id_exclude' => $this->atributos['id'] ?? 0
        ]);
    }

    /**
     * Check if a captain already exits
     */
    public static function checkCaptain(int $equipoId, ?int $idExcluir = null): bool {
        $db = self::getDB();
        $sql = "SELECT COUNT(*) FROM jugadores 
            WHERE equipo_id = :equipo_id 
            AND es_capitan = 1";

        if ($idExcluir) {
            $sql .= " AND id != :id_excluir";
        }

        $stmt = $db->prepare($sql);
        $params = [':equipo_id' => $equipoId];

        if ($idExcluir) {
            $params[':id_excluir'] = $idExcluir;
        }

        $stmt->execute($params);
        return (bool)$stmt->fetchColumn();
    }

}
