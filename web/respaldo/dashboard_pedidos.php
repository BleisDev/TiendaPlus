<?php
session_start();
include_once('../backend/conexion.php');

// Si viene el parámetro "vista=lista", mostramos los clientes
$vista = $_GET['vista'] ?? 'pedidos';

if ($vista === 'lista') {
    // Mostrar lista de clientes con sus pedidos
    $sql = "SELECT p.id AS pedido_id, p.fecha, p.total, u.nombre, u.email
            FROM pedidos p
            INNER JOIN usuarios u ON p.usuario_id = u.id
            ORDER BY u.nombre, p.fecha DESC";
    $res = $conn->query($sql);
    ?>
    <div class="container mt-5">
        <h2>📋 Lista de Clientes con sus Pedidos</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Email</th>
                    <th>ID Pedido</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $res->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['nombre']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td>#<?= $row['pedido_id'] ?></td>
                    <td><?= $row['fecha'] ?></td>
                    <td>$<?= number_format($row['total'], 0, ',', '.') ?></td>
                    <td>
                        <a href="detalle_pedido.php?id=<?= $row['pedido_id'] ?>" class="btn btn-primary btn-sm">Ver detalle</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="dashboard_pedidos.php" class="btn btn-secondary">⬅️ Volver a Pedidos</a>
    </div>
    <?php
    exit;
}

// -------------------- Vista normal de pedidos --------------------
$sql = "SELECT id, fecha, total FROM pedidos ORDER BY fecha DESC";
$res = $conn->query($sql);
?>
<div class="container mt-5">
    <h2>📦 Dashboard Pedidos</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Pedido</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($pedido = $res->fetch_assoc()): ?>
            <tr>
                <td>#<?= $pedido['id'] ?></td>
                <td><?= $pedido['fecha'] ?></td>
                <td>$<?= number_format($pedido['total'], 0, ',', '.') ?></td>
                <td>
                    <a href="dashboard_pedidos.php?vista=lista" class="btn btn-info btn-sm">Ver lista de clientes</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
