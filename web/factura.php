<?php
session_start();
$cliente = $_POST;
$carrito = $_SESSION["carrito"];
$total = 0;
foreach ($carrito as $item) {
  $total += $item["precio"] * $item["cantidad"];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Factura</title>
  <link rel="stylesheet" href="estilos.css">
</head>
<body>
  <h1 class="titulo-catalogo">Factura de Compra</h1>
  <p><strong>Cliente:</strong> <?= $cliente["nombre"] ?></p>
  <p><strong>Dirección:</strong> <?= $cliente["direccion"] ?></p>
  <p><strong>Método de pago:</strong> <?= $cliente["pago"] ?></p>

  <h3>Productos</h3>
  <ul>
    <?php foreach ($carrito as $item): ?>
      <li><?= $item["nombre"] ?> - $<?= $item["precio"] ?></li>
    <?php endforeach; ?>
  </ul>
  <h3>Total: $<?= $total ?></h3>

  <p><strong>Estado del pedido:</strong> 
    <select>
      <option>Pendiente</option>
      <option>En preparación</option>
      <option>Enviado</option>
      <option>Entregado</option>
    </select>
  </p>
</body>
</html>
