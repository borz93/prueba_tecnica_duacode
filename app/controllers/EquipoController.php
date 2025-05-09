<?php
namespace app\controllers;

use app\core\Controller;
use app\models\Equipo;
use DateTime;
use Exception;
use PDOException;

class EquipoController extends Controller {
    public function index() {
        $equipos = Equipo::all();
        $this->render('equipos/index', ['equipos' => $equipos]);
    }

    public function create(): void {
        $errors = [];
        $data = $this->initializeFormData();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->sanitizeInput($_POST);
            $errors = $this->validateForm($data);

            if (empty($errors)) {
                try {
                    $equipo = new Equipo($data);

                    if ($equipo->save()) {
                        $this->setFlashMessage("Equipo creado exitosamente", "success");
                        $this->redirect('/equipos');
                    }
                } catch (PDOException $e) {
                    error_log("Database error: " . $e->getMessage());
                    $errors['general'] = $this->handleDatabaseError($e);
                } catch (Exception $e) {
                    error_log("Unexpected error: " . $e->getMessage());
                    $errors['general'] = "Unexpected error";
                }
            }
        }

        $this->render('equipos/crear', [
            'errors' => $errors,
            'data' => $data
        ]);
    }

    public function view(array $params): void {
        $id = $this->validateId($params['id'] ?? null);

        if (!$id) {
            $this->setFlashMessage("Invalid request: ID incorrecto.", "danger");
            $this->redirect('/equipos');
            return;
        }

        $equipo = Equipo::find($id);

        if (!$equipo) {
            $this->setFlashMessage("El equipo no existe.", "warning");
            $this->redirect('/equipos');
            return;
        }

        $this->render('equipos/ver', ['equipo' => $equipo]);
    }

    public function delete(array $params): void {
        $id = $this->validateId($params['id'] ?? null);

        if (!$id) {
            $this->setFlashMessage("Invalid request: ID incorrecto.", "danger");
            $this->redirect('/equipos');
            return;
        }

        try {
            $equipo = Equipo::find($id);

            if (!$equipo) {
                $this->setFlashMessage("El equipo no existe.", "warning");
            } elseif ($equipo->delete()) {
                $this->setFlashMessage("Equipo eliminado exitosamente.", "success");
            } else {
                $this->setFlashMessage("Error mientras eliminación de equipo.", "danger");
            }
        } catch (PDOException $e) {
            error_log("Error deleting team: " . $e->getMessage());
            $this->setFlashMessage($this->handleDatabaseError($e), "danger");
        }

        $this->redirect('/equipos');
    }

    private function initializeFormData(): array {
        return [
            'nombre' => '',
            'ciudad' => '',
            'deporte' => '',
            'fundacion' => ''
        ];
    }

    private function sanitizeInput(array $input): array {
        return [
            'nombre' => trim($input['nombre'] ?? ''),
            'ciudad' => trim($input['ciudad'] ?? ''),
            'deporte' => trim($input['deporte'] ?? ''),
            'fundacion' => trim($input['fundacion'] ?? '')
        ];
    }

    private function validateForm(array $data): array {
        $errors = [];

        // Validate 'nombre' field
        if (empty($data['nombre']) || strlen($data['nombre']) < 3) {
            $errors['nombre'] = "El nombre debe tener 3 caracteres.";
        }

        // Validate 'deporte' field
        $validSports = ['Fútbol', 'Baloncesto', 'Tenis'];
        if (!in_array($data['deporte'], $validSports, true)) {
            $errors['deporte'] = "Selecciona un deporte.";
        }

        // Validate 'fundacion' field
        if (!empty($data['fundacion'])) {
            $date = DateTime::createFromFormat('Y-m-d', $data['fundacion']);
            if (!$date || $date->format('Y-m-d') !== $data['fundacion']) {
                $errors['fundacion'] = "Formato de fecha inválido (YYYY-MM-DD)";
            }
        }

        return $errors;
    }

}