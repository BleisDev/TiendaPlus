<?php
session_start();
include_once('../backend/conexion.php');

// Verificar sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$rol = $_SESSION['rol'] ?? 'cliente'; // rol puede ser 'admin' o 'cliente'

// Si es admin, ver todos los pedidos; si no, solo los del usuario
if ($rol === 'admin') {
    $sql = "SELECT p.id, p.fecha, p.total, u.nombre AS cliente, u.email 
            FROM pedidos p 
            INNER JOIN usuarios u ON p.usuario_id = u.id
            ORDER BY p.fecha DESC";
} else {
    $sql = "SELECT id, fecha, total 
            FROM pedidos 
            WHERE usuario_id = $usuario_id
            ORDER BY fecha DESC";
}

$res = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>📦 Mis pedidos - Tienda Plus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f9fafb; padding: 40px; font-family: Arial, sans-serif; }
        .container { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        h2 { margin-bottom: 20px; font-weight: bold; color: #333; }
        table th { background-color: #e9ecef; text-align: center; }
        table td { text-align: center; }
        .btn-ver { border-radius: 8px; }
        .alert { margin-top: 20px; }
    </style>
</head>
<body>
<div class="container">
    <h2>📦 <?php echo ($rol === 'admin') ? 'Pedidos de Clientes' : 'Mis Pedidos'; ?></h2>

    <?php if ($res->num_rows > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Pedido</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <?php if ($rol === 'admin'): ?>
                        <th>Cliente</th>
                        <th>Email</th>
                    <?php endif; ?>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($pedido = $res->fetch_assoc()): ?>
                    <tr>
                        <td>#<?= $pedido['id'] ?></td>
                        <td><?= $pedido['fecha'] ?></td>
                        <td>$<?= number_format($pedido['total'], 0, ',', '.') ?></td>

                        <?php if ($rol === 'admin'): ?>
                            <td><?= htmlspecialchars($pedido['cliente']) ?></td>
                            <td><?= htmlspecialchars($pedido['email']) ?></td>
                        <?php endif; ?>

                        <td>
                            <?php if ($rol === 'admin'): ?>
                                <a href="../web/detalle_pedido_admin.php?id=<?= $pedido['id'] ?>" class="btn btn-primary btn-sm">Ver pedido</a>
                            <?php else: ?>
                                <a href="../web/detalle_pedido.php?id=<?= $pedido['id'] ?>" class="btn btn-primary btn-sm">Ver pedido</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info text-center">
            😕 No hay pedidos registrados.
        </div>
    <?php endif; ?>
</div>
</body>
</html>
