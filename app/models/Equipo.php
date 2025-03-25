<?php
declare(strict_types=1);

namespace app\models;

use app\core\BaseModel;

class Equipo extends BaseModel {
    // Table name and fields for BaseModel operations
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
     * Magic method to provide a string representation of the Equipo object.
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
