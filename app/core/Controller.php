<?php
declare(strict_types=1);

namespace app\core;

use JetBrains\PhpStorm\NoReturn;
use PDOException;
use RuntimeException;

abstract class Controller {
    /**
     * Render a view file with optional data.
     */
    protected function render(string $view, array $data = []): void {
        extract($data, EXTR_SKIP);
        $viewPath = realpath(__DIR__ . "/../views/{$view}.php");

        if (!$viewPath || !str_starts_with($viewPath, realpath(__DIR__ . '/../views'))) {
            throw new RuntimeException("View not found: {$view}");
        }

        require $viewPath;
    }

    /**
     * Set a flash message in the session.
     */
    protected function setFlashMessage(string $message, string $type = "info"): void {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $_SESSION['message'] = $message;
        $_SESSION['message_type'] = $type;
    }

    /**
     * Redirect to a specific URL and stop execution.
     */
    #[NoReturn]
    protected function redirect(string $url): void {
        header("Location: " . filter_var($url, FILTER_SANITIZE_URL));
        exit;
    }

    /**
     * Handle databases errors.
     */
    protected function handleDatabaseError(PDOException $e): string {
        return "Database error. Please try again later: " . $e->getMessage();
    }

    /**
     * Validates ID field.
     */
    protected function validateId(mixed $id): ?int {
        return (isset($id) && is_numeric($id) && $id > 0) ? (int) $id : null;
    }
}
