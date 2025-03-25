<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Equipo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
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
        <div class="col-lg-10">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="h5 mb-0"><i class="bi bi-info-circle me-2"></i>Detalles del Equipo</h2>
                        <a href="/equipos" class="btn btn-sm btn-light">
                            <i class="bi bi-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (isset($equipo)): ?>
                        <dl class="row mb-0">
                            <!-- Nombre -->
                            <dt class="col-sm-3 text-md-end">Nombre</dt>
                            <dd class="col-sm-9"><?= htmlspecialchars($equipo->nombre) ?></dd>

                            <!-- Deporte -->
                            <dt class="col-sm-3 text-md-end">Deporte</dt>
                            <dd class="col-sm-9"><?= htmlspecialchars($equipo->deporte) ?></dd>

                            <!-- Ciudad -->
                            <dt class="col-sm-3 text-md-end">Ciudad</dt>
                            <dd class="col-sm-9">
                                <?= !empty($equipo->ciudad) ? htmlspecialchars($equipo->ciudad) : 'N/A' ?>
                            </dd>

                            <!-- Fundación -->
                            <dt class="col-sm-3 text-md-end">Fundación</dt>
                            <dd class="col-sm-9">
                                <?= !empty($equipo->fundacion) ?
                                    date('d/m/Y', strtotime($equipo->fundacion)) :
                                    'Fecha desconocida' ?>
                            </dd>

                            <!-- ID -->
                            <dt class="col-sm-3 text-md-end">ID Registro</dt>
                            <dd class="col-sm-9 text-muted">#<?= $equipo->id ?></dd>
                        </dl>
                    <?php else: ?>
                        <div class="alert alert-warning mb-0">
                            <i class="bi bi-exclamation-triangle"></i>
                            No se encontró información del equipo.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h2 class="h6 mb-0"><i class="bi bi-star-fill me-2"></i>Información del Capitán</h2>
                </div>
                <div class="card-body">
                    <?php $capitan = $equipo->getCapitan(); ?>
                    <?php if ($capitan): ?>
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary text-white rounded-circle p-3">
                                <i class="bi bi-person-badge fs-4"></i>
                            </div>
                            <div>
                                <h3 class="h5 mb-1"><?= htmlspecialchars($capitan->nombre) ?></h3>
                                <p class="mb-0 text-muted">
                                    Número <?= htmlspecialchars((string)$capitan->numero) ?>
                                    <?php if ($capitan->created_at): ?>
                                        <span class="ms-2">• Miembro desde <?= date('m/Y', strtotime($capitan->created_at)) ?></span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning mb-0">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Este equipo no tiene capitán asignado
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php if (isset($equipo)): ?>
                <!-- Jugadores Section -->
                <?php $jugadores = $equipo->getJugadores(); ?>
                <div class="card shadow-sm">
                    <div class="card-header bg-secondary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="h6 mb-0">Jugadores del Equipo</h2>
                            <a href="/jugadores/crear/<?= $equipo->id ?>" class="btn btn-sm btn-light">
                                <i class="bi bi-plus-circle"></i> Añadir Jugador
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (empty($jugadores)): ?>
                            <p class="text-muted">No hay jugadores registrados para este equipo.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Número</th>
                                        <th>Capitán</th>
                                        <th>Acciones</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($jugadores as $jugador): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($jugador->nombre) ?></td>
                                            <td><?= htmlspecialchars((string)$jugador->numero) ?></td>
                                            <td><?= $jugador->es_capitan ? 'Sí' : 'No' ?></td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="/jugadores/editar/<?= $jugador->id ?>" class="btn btn-sm btn-outline-primary" title="Editar">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <form action="/jugadores/eliminar/<?= $jugador->id ?>" method="POST" class="d-inline">
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este jugador?')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Remove flash messages after 5 seconds
    setTimeout(() => {
        const alert = document.querySelector('.alert');
        if (alert) {
            alert.classList.add('fade');
            setTimeout(() => alert.remove(), 150);
        }
    }, 5000);
</script>
</body>
</html>
