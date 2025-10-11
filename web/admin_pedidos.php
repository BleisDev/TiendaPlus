<?php
session_start();
require_once '../backend/conn.php';

// Verifica si es admin
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Traer todos los pedidos con nombre de cliente y email
$sql = "SELECT p.*, u.nombre AS cliente, u.email 
        FROM pedidos p
        JOIN usuarios u ON p.usuario_id = u.id
        ORDER BY p.id DESC";

$resultado = $conn->query($sql);
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<div class="container mt-5">
    <h2>📦 Todos los Pedidos (Admin)</h2>
    <table class="table table-bordered table-striped mt-4">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Cliente</th>
                <th>Email</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($pedido = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?= $pedido['id'] ?></td>
                    <td><?= $pedido['fecha'] ?></td>
                    <td>$<?= number_format($pedido['total'], 0, ',', '.') ?></td>
                    <td><?= $pedido['estado'] ?></td>
                    <td><?= $pedido['cliente'] ?></td>
                    <td><?= $pedido['email'] ?></td>
                    <td>
                   <a href="admin_detalle_pedido.php?id=<?= $pedido['id'] ?>" class="btn btn-info btn-sm">Ver Detalle</a>


                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
