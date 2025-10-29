<?php
session_start();
include("../backend/conexion.php");

// 🔹 Tomar el ID del usuario desde la sesión
$usuario_id = $_SESSION['usuario_id'] ?? null;

if (!$usuario_id) {
    echo "⚠️ Debes iniciar sesión para ver tus pedidos.";
    exit;
}

// Consultar pedidos del usuario
$sql = "SELECT * FROM pedidos WHERE usuario_id = ? ORDER BY fecha DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$pedidos = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mis Pedidos</title>
  <style>
    body { font-family: Arial, sans-serif; margin:20px; }
    h1 { color:#ff4081; }
    table { width:100%; border-collapse: collapse; margin-top:20px; }
    th, td { border:1px solid #ddd; padding:8px; text-align:center; }
    th { background:#ff4081; color:white; }
    a { color:#ff4081; text-decoration:none; }
  </style>
</head>
<body>
  <h1>📋 Mis Pedidos</h1>
  <table>
    <tr>
      <th>ID</th>
      <th>Fecha</th>
      <th>Total</th>
      <th>Método de pago</th>
      <th>Detalle</th>
    </tr>
    <?php while($row = $pedidos->fetch_assoc()): ?>
    <tr>
      <td><?= $row['id'] ?></td>
      <td><?= $row['fecha'] ?></td>
      <td>$<?= number_format($row['total'],2) ?></td>
      <td><?= htmlspecialchars($row['metodo_pago']) ?></td>
      <td>
        <a href="pedido_exitoso.php?pedido_id=<?= $row['id'] ?>">Ver pedido</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </table>
</body>
</html>
