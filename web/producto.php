<?php
session_start();

// Procesar agregar al carrito
if (isset($_POST['agregar'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];

    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }

    if (isset($_SESSION['carrito'][$id])) {
        $_SESSION['carrito'][$id]['cantidad'] += $cantidad;
    } else {
        $_SESSION['carrito'][$id] = [
            'nombre' => $nombre,
            'precio' => $precio,
            'cantidad' => $cantidad
        ];
    }

    header("Location: carrito.php");
    exit();
}

// Procesar eliminar del carrito
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    unset($_SESSION['carrito'][$id]);
    header("Location: carrito.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Carrito - Tienda Plus</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <h1 class="mb-4">Tu Carrito 🛒</h1>

  <?php if (!empty($_SESSION['carrito'])): ?>
    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>Producto</th>
          <th>Precio</th>
          <th>Cantidad</th>
          <th>Total</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $granTotal = 0;
        foreach ($_SESSION['carrito'] as $id => $item):
          $subtotal = $item['precio'] * $item['cantidad'];
          $granTotal += $subtotal;
        ?>
        <tr>
          <td><?php echo $item['nombre']; ?></td>
          <td>$<?php echo number_format($item['precio'], 2); ?></td>
          <td><?php echo $item['cantidad']; ?></td>
          <td>$<?php echo number_format($subtotal, 2); ?></td>
          <td><a href="carrito.php?eliminar=<?php echo $id; ?>" class="btn btn-danger btn-sm">Eliminar</a></td>
        </tr>
        <?php endforeach; ?>
        <tr>
          <td colspan="3" class="text-end"><strong>Total</strong></td>
          <td colspan="2"><strong>$<?php echo number_format($granTotal, 2); ?></strong></td>
        </tr>
      </tbody>
    </table>
    <a href="checkout.php" class="btn btn-success">Finalizar Compra ✅</a>
  <?php else: ?>
    <p>Tu carrito está vacío 🛒</p>
  <?php endif; ?>
</div>
</body>
</html>
