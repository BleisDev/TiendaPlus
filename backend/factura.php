<?php
session_start();

// 🔹 Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "TiendaPlus");
if ($conn->connect_error) {
    die("❌ Conexión fallida: " . $conn->connect_error);
}

// 🔹 Verificar ID de pedido
if (!isset($_GET['id'])) {
    die("⚠️ Pedido no especificado.");
}

$id_pedido = intval($_GET['id']);

// 🔹 Consultar datos del pedido
$sql_pedido = "SELECT * FROM pedidos WHERE id = $id_pedido";
$result_pedido = $conn->query($sql_pedido);
if ($result_pedido->num_rows === 0) {
    die("⚠️ Pedido no encontrado.");
}
$pedido = $result_pedido->fetch_assoc();

// 🔹 Consultar productos del pedido
$sql_detalle = "
    SELECT dp.cantidad, dp.precio, p.nombre
    FROM detalle_pedido dp
    INNER JOIN productos p ON dp.producto_id = p.id
    WHERE dp.pedido_id = $id_pedido
";
$result_detalle = $conn->query($sql_detalle);
$productos = [];
while ($row = $result_detalle->fetch_assoc()) {
    $productos[] = $row;
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Factura - Pedido #<?= $pedido['id'] ?></title>
<style>
body {
  font-family: 'Poppins', sans-serif;
  background: #f6f6f6;
  margin: 0;
  padding: 0;
}
.factura {
  background: white;
  width: 800px;
  margin: 40px auto;
  padding: 30px;
  border-radius: 12px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
h1 {
  text-align: center;
  color: #ff69b4;
}
.info {
  margin-top: 20px;
  line-height: 1.6;
}
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}
th, td {
  border: 1px solid #ddd;
  padding: 10px;
  text-align: center;
}
th {
  background: #ffe0ef;
}
.total {
  text-align: right;
  margin-top: 20px;
  font-size: 18px;
}
button {
  background: #ff69b4;
  border: none;
  color: white;
  padding: 10px 20px;
  border-radius: 6px;
  cursor: pointer;
  margin-top: 25px;
}
button:hover {
  background: #ff85c1;
}
</style>
</head>
<body>

<div class="factura">
  <h1>🧾 Factura TiendaPlus</h1>
  <div class="info">
    <strong>Cliente:</strong> <?= htmlspecialchars($pedido['cliente']) ?><br>
    <strong>Correo:</strong> <?= htmlspecialchars($pedido['email']) ?><br>
    <strong>Dirección:</strong> <?= htmlspecialchars($pedido['direccion']) ?><br>
    <strong>Ciudad:</strong> <?= htmlspecialchars($pedido['ciudad']) ?><br>
    <strong>Método de Pago:</strong> <?= htmlspecialchars($pedido['metodo_pago']) ?><br>
    <strong>Fecha:</strong> <?= $pedido['fecha'] ?><br>
  </div>

  <h3>Detalle del Pedido #<?= $pedido['id'] ?></h3>
  <table>
    <tr>
      <th>Producto</th>
      <th>Cantidad</th>
      <th>Precio</th>
      <th>Subtotal</th>
    </tr>
    <?php foreach ($productos as $item): ?>
    <tr>
      <td><?= htmlspecialchars($item['nombre']) ?></td>
      <td><?= $item['cantidad'] ?></td>
      <td>$<?= number_format($item['precio'], 0, ',', '.') ?></td>
      <td>$<?= number_format($item['precio'] * $item['cantidad'], 0, ',', '.') ?></td>
    </tr>
    <?php endforeach; ?>
  </table>

  <div class="total">
    <strong>Total: $<?= number_format($pedido['total'], 0, ',', '.') ?></strong>
  </div>

  <div style="text-align:center;">
    <button onclick="window.print()">🖨️ Imprimir / Guardar como PDF</button>
    <a href="../web/catalogo.php"><button>🏠 Volver al Catálogo</button></a>
  </div>
</div>

</body>
</html>
