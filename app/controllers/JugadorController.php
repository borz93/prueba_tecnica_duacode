<?php
declare(strict_types=1);

namespace app\controllers;

use app\core\Controller;
use app\models\Jugador;
use app\models\Equipo;
use PDOException;
use Exception;

class JugadorController extends Controller {

    public function create(array $params): void {
        $errors = [];
        $data = $this->initializeFormData($params);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->sanitizeInput($_POST, $params);
            $errors = $this->validateForm($data);

            if (empty($errors)) {
                try {
                    $jugador = new Jugador($data);

                    if ($jugador->save()) {
                        $this->setFlashMessage("Jugador creado exitosamente.", "success");
                        $this->redirect("/equipos/ver/{$data['equipo_id']}");
                    }
                } catch (PDOException $e) {
                    error_log("Database error: " . $e->getMessage());
                    $errors['general'] = $this->handleDatabaseError($e);
                } catch (Exception $e) {
                    error_log("Unexpected error: " . $e->getMessage());
                    $errors['general'] = "Unexpected error. Please contact the administrator.";
                }
            }
        }

        $this->render('jugadores/form', array_merge(['errors' => $errors], $data));
    }

    public function edit(array $params): void {
        $id = $this->validateId($params['id'] ?? null);

        if (!$id) {
            $this->setFlashMessage("Invalid request: Missing or incorrect ID.", "danger");
            $this->redirect('/equipos');
            return;
        }

        $jugador = Jugador::find($id);

        if (!$jugador) {
            $this->setFlashMessage("El jugador no existe.", "warning");
            $this->redirect('/equipos');
            return;
        }

        $errors = [];
        $data = (array)$jugador;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->sanitizeInput($_POST, ['equipo_id' => $jugador->equipo_id]);
            $errors = $this->validateForm($data);


            if (empty($errors)) {
                try {
                    $jugador->setAttributes($data);

                    if ($jugador->save()) {
                        $this->setFlashMessage("Jugador actualizado correctamente.", "success");
                        $this->redirect("/equipos/ver/{$jugador->equipo_id}");
                    }
                } catch (PDOException $e) {
                    error_log("Error updating player: " . $e->getMessage());
                    $errors['general'] = "Error al actualizar el jugador.";
                }
            }
        }

        $this->render('jugadores/form', array_merge(['errors' => $errors], $data));

    }

    public function delete(array $params): void {
        $id = $this->validateId($params['id'] ?? null);
        $redirectUrl = '/equipos';

        if (!$id) {
            $this->setFlashMessage("Solicitud inválida: ID faltante o incorrecto", "danger");
            $this->redirect($redirectUrl);
            return;
        }

        try {
            $jugador = Jugador::find($id);

            if (!$jugador) {
                $this->setFlashMessage("El jugador no existe", "warning");
            } elseif ($jugador->delete()) {
                $this->setFlashMessage("Jugador eliminado exitosamente", "success");
                $redirectUrl = "/equipos/ver/" . $jugador->equipo_id;
            } else {
                $this->setFlashMessage("Error al eliminar el jugador", "danger");
            }
        } catch (PDOException $e) {
            error_log("Error eliminando jugador: " . $e->getMessage());
            $this->setFlashMessage("Error al eliminar el jugador: " . $e->getMessage(), "danger");
        }

        $this->redirect($redirectUrl);
    }

    private function initializeFormData(array $params): array {
        return [
            'nombre' => '',
            'numero' => '',
            'equipo_id' => (int)($params['equipo_id'] ?? 0),
            'es_capitan' => false
        ];
    }

    private function sanitizeInput(array $input, array $params): array {
        return [
            'nombre' => trim($input['nombre'] ?? ''),
            'numero' => (int)($input['numero'] ?? 0),
            'equipo_id' => (int)($params['equipo_id'] ?? 0),
            'es_capitan' => (bool)($input['es_capitan'] ?? 0),
        ];
    }

    private function validateForm(array $data): array {
        $errors = [];

        if (empty($data['nombre']) || strlen($data['nombre']) < 3) {
            $errors['nombre'] = "The name must be at least 3 characters long.";
        }

        if ($data['numero'] < 1 || $data['numero'] > 99) {
            $errors['numero'] = "The number must be between 1 and 99.";
        }

        if (empty($data['equipo_id']) || !Equipo::find($data['equipo_id'])) {
            $errors['equipo'] = "The team is not valid.";
        }

        if ($data['es_capitan'] ?? false) {
            if (Jugador::checkCaptain($data['equipo_id'], $data['id'] ?? null)) {
                $errors['es_capitan'] = "Ya existe un capitán en este equipo";
            }
        }

        return $errors;
    }

}
