<?php
declare(strict_types=1);

namespace app\models;

use app\core\BaseModel;

class Equipo extends BaseModel {
    protected static string $tabla = 'equipos';
    protected static array $campos = ['nombre', 'ciudad', 'deporte', 'fundacion'];

    // Public properties for the Equipo entity
    public ?int $id = null;
    public ?string $nombre = null;
    public ?string $ciudad = null;
    public ?string $deporte = null;
    public ?string $fundacion = null;
    public ?string $created_at = null;

    /**
     * Constructor for Equipo.
     *
     * @param array $datos Data to initialize the Equipo properties.
     */
    public function __construct(array $datos = []) {
        parent::__construct($datos);

        // Assign values to properties if they exist in the data array
        foreach ($datos as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * Retrieves all players associated with this team.
     *
     * @return Jugador[] Array of Jugador objects.
     */
    public function getJugadores(): array {
        $db = self::getDB();
        $stmt = $db->prepare("SELECT * FROM jugadores WHERE equipo_id = :equipo_id");
        $stmt->execute([':equipo_id' => $this->id]);
        $jugadoresData = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return array_map(fn($data) => new Jugador($data), $jugadoresData);
    }

    /**
     * Returns the team captain if set.
     *
     * @return Jugador|null
     */
    public function getCapitan(): ?Jugador
    {
        $db = self::getDB();
        $stmt = $db->prepare("SELECT * FROM jugadores WHERE equipo_id = :equipo_id AND es_capitan = 1 LIMIT 1");
        $stmt->execute([':equipo_id' => $this->id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $data ? new Jugador($data) : null;
    }

    /**
     * Magic method to provide a string representation of the Equipo object (debug).
     *
     * @return string
     */
    public function __toString(): string {
        return sprintf(
            "Equipo: %s, Ciudad: %s, Deporte: %s, Fundación: %s",
            $this->nombre ?? 'N/A',
            $this->ciudad ?? 'N/A',
            $this->deporte ?? 'N/A',
            $this->fundacion ?? 'N/A'
        );
    }

}
