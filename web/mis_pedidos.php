<?php
include("../backend/conexion.php");

$correo = "cliente@ejemplo.com"; // 🔴 CAMBIA ESTO: en realidad debería tomarse del login del usuario

$sql = "SELECT * FROM pedidos WHERE correo = '$correo' ORDER BY fecha DESC";
$pedidos = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mis pedidos</title>
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
      <th>Factura</th>
    </tr>
    <?php while($row=$pedidos->fetch_assoc()): ?>
    <tr>
      <td><?php echo $row['id']; ?></td>
      <td><?php echo $row['fecha']; ?></td>
      <td>$<?php echo number_format($row['total'],2); ?></td>
      <td><?php echo $row['metodo_pago']; ?></td>
      <td><a href="factura.php?id=<?php echo $row['id']; ?>">Ver factura</a></td>
    </tr>
    <?php endwhile; ?>
  </table>
</body>
</html>
