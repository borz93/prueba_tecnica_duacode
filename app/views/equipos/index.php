<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Equipos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light">
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Listado de Equipos</h1>
        <a href="/equipos/crear" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nuevo Equipo
        </a>
    </div>

    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-<?= $_SESSION['mensaje_tipo'] ?> alert-dismissible fade show">
            <?= htmlspecialchars($_SESSION['mensaje']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>

    <div class="card shadow">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>Nombre</th>
                        <th>Deporte</th>
                        <th>Ciudad</th>
                        <th>Fundación</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($equipos)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                No hay equipos registrados
                            </td>
                        </tr>
                    <?php else: ?>

                        <?php foreach ($equipos as $equipo): ?>
                            <tr>
                                <td><?= htmlspecialchars($equipo->nombre) ?></td>
                                <td><?= htmlspecialchars($equipo->deporte) ?></td>
                                <td><?= htmlspecialchars($equipo->ciudad) ?></td>
                                <td><?= date('d/m/Y', strtotime($equipo->fundacion)) ?></td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="/equipos/ver/<?= $equipo->id ?>"
                                           class="btn btn-sm btn-outline-primary"
                                           title="Ver detalle">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <form action="/equipos/eliminar/<?= $equipo->id ?>" method="POST" class="d-inline">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este equipo?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Eliminar mensaje flash después de 5 segundos
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