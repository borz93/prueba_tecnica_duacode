<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($data['id']) ? 'Editar Jugador' : 'Crear nuevo Jugador' ?></title>
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
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="h5 mb-0">
                            <i class="bi bi-<?= isset($data['id']) ? 'pencil' : 'plus-circle' ?> me-2"></i>
                            <?= isset($data['id']) ? 'Editar Jugador' : 'Crear Nuevo Jugador' ?>
                        </h2>
                        <a href="/equipos/ver/<?= htmlspecialchars((string)($data['equipo_id'] ?? 0)) ?>"
                           class="btn btn-sm btn-light">
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

                    <form method="POST" action="" class="needs-validation" novalidate>
                        <?php if (isset($data['id'])): ?>
                            <input type="hidden" name="id" value="<?= htmlspecialchars((string)($data['id'])) ?>">
                        <?php endif; ?>
                        <input type="hidden" name="equipo_id"
                               value="<?= htmlspecialchars((string)($data['equipo_id'] ?? 0)) ?>">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nombre" class="form-label required-label">Nombre del jugador</label>
                                <input type="text"
                                       class="form-control <?= isset($errors['nombre']) ? 'is-invalid' : '' ?>"
                                       id="nombre" name="nombre" value="<?= htmlspecialchars($data['nombre'] ?? '') ?>"
                                       required pattern=".{3,100}" title="Mínimo 3 caracteres">
                                <div class="invalid-feedback">
                                    Por favor ingrese un nombre válido (3-100 caracteres).
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="numero" class="form-label required-label">Número</label>
                                <input type="number"
                                       class="form-control <?= isset($errors['numero']) ? 'is-invalid' : '' ?>"
                                       id="numero" name="numero" min="1" max="99"
                                       value="<?= htmlspecialchars((string)($data['numero'] ?? '')) ?>" required>
                                <div class="invalid-feedback">
                                    Ingrese un número entre 1 y 99.
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-check mt-4">
                                    <input type="checkbox"
                                           class="form-check-input"
                                           id="es_capitan"
                                           name="es_capitan"
                                           value="1"
                                        <?= ($data['es_capitan'] ?? false) ? 'checked' : '' ?>>

                                    <label for="es_capitan" class="form-check-label">
                                        ¿Es Capitán?
                                        <?php if ($data['capitan_existente'] ?? false): ?>
                                            <small class="text-danger">(¡Ya hay un capitán asignado!)</small>
                                        <?php endif; ?>
                                    </label>
                                </div>
                            </div>

                            <div class="col-12 mt-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="reset" class="btn btn-outline-secondary">
                                        <i class="bi bi-eraser"></i> Limpiar
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save"></i> <?= isset($data['id']) ? 'Actualizar' : 'Guardar' ?>
                                        Jugador
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
