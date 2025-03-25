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
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
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
                            No se encontró información del equipo
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>