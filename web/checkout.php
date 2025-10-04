<?php
session_start();
if(empty($_SESSION['carrito'])) {
    header("Location: carrito.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Finalizar Compra</title>
  <link rel="stylesheet" href="estilos.css">
</head>
<body>
  <h1 class="titulo">Finalizar Compra</h1>
  <div class="contenedor-form">
    <form method="post" action="confirmacion.php">
      <input type="text" name="nombre" placeholder="Nombre completo" required>
      <input type="text" name="direccion" placeholder="Dirección de envío" required>
      <select name="metodo_pago" required>
        <option value="Efectivo">Efectivo</option>
        <option value="Tarjeta">Tarjeta</option>
      </select>
      <button type="submit" class="btn">Confirmar Pedido</button>
    </form>
  </div>
</body>
</html>

