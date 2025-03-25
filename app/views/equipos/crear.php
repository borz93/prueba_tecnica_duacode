<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear nuevo Equipo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .required-label:after {
            content: " *";
            color: #dc3545;
        }
    </style>
</head>
<body class="bg-light">
<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
if (isset($_SESSION['message'])): ?>
    <div class="alert alert-<?= $_SESSION['message_type'] ?> alert-dismissible fade show m-3" role="alert">
        <?= $_SESSION['message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
endif; ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="h5 mb-0"><i class="bi bi-plus-circle me-2"></i>Crear nuevo equipo</h2>
                        <a href="/equipos" class="btn btn-sm btn-light">
                            <i class="bi bi-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <h5 class="alert-heading"><i class="bi bi-exclamation-triangle-fill"></i> Errores</h5>
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="/equipos/crear" class="needs-validation" novalidate>
                        <div class="row g-3">
                            <!-- Campo: Nombre -->
                            <div class="col-md-6">
                                <label for="nombre" class="form-label required-label">Nombre del equipo</label>
                                <input type="text" class="form-control <?= isset($errors['nombre']) ? 'is-invalid' : '' ?>"
                                       id="nombre" name="nombre" value="<?= htmlspecialchars($datos['nombre'] ?? '') ?>"
                                       required pattern=".{3,50}" title="Mínimo 3 caracteres">
                                <div class="invalid-feedback">
                                    Por favor ingrese un nombre válido (3-50 caracteres).
                                </div>
                            </div>

                            <!-- Campo: Deporte -->
                            <div class="col-md-6">
                                <label for="deporte" class="form-label required-label">Deporte</label>
                                <select class="form-select <?= isset($errors['deporte']) ? 'is-invalid' : '' ?>"
                                        id="deporte" name="deporte" required>
                                    <option value="" disabled selected>Seleccione un deporte...</option>
                                    <option value="Fútbol" <?= ($data['deporte'] ?? '') === 'Fútbol' ? 'selected' : '' ?>>Fútbol</option>
                                    <option value="Baloncesto" <?= ($data['deporte'] ?? '') === 'Baloncesto' ? 'selected' : '' ?>>Baloncesto</option>
                                    <option value="Tenis" <?= ($data['deporte'] ?? '') === 'Tenis' ? 'selected' : '' ?>>Tenis</option>
                                </select>
                                <div class="invalid-feedback">
                                    Seleccione un deporte válido.
                                </div>
                            </div>

                            <!-- Campo: Ciudad -->
                            <div class="col-md-6">
                                <label for="ciudad" class="form-label">Ciudad</label>
                                <input type="text" class="form-control" id="ciudad" name="ciudad"
                                       value="<?= htmlspecialchars($data['ciudad'] ?? '') ?>"
                                       required pattern=".{3,50}" title="Mínimo 3 caracteres">
                                <div class="invalid-feedback">
                                    Por favor ingrese un nombre válido (3-50 caracteres).
                                </div>
                            </div>

                            <!-- Campo: Fecha Fundación -->
                            <div class="col-md-6">
                                <label for="fundacion" class="form-label">Fecha de Fundación</label>
                                <input type="date" class="form-control" id="fundacion" name="fundacion" required
                                       max="<?= date('Y-m-d') ?>" value="<?= htmlspecialchars($data['fundacion'] ?? '') ?>">
                            </div>

                            <div class="col-12 mt-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="reset" class="btn btn-outline-secondary">
                                        <i class="bi bi-eraser"></i> Limpiar
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save"></i> Guardar Equipo
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    (() => {
        'use strict'
        const forms = document.querySelectorAll('.needs-validation')

        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })()
</script>
</body>
</html>