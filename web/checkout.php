<?php
session_start();

// Verificar carrito
if (empty($_SESSION['carrito'])) {
    header("Location: carrito.php");
    exit;
}

// Conexión BD
$conn = new mysqli("localhost", "root", "", "TiendaPlus");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$usuario_id = $_SESSION['usuario_id'] ?? null;

// Si se envía el formulario
if (isset($_POST['finalizar'])) {
    $cliente = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $direccion = trim($_POST['direccion']);
    $ciudad = trim($_POST['ciudad']);
    $metodo_pago = $_POST['metodo_pago'];
    $estado = 'pendiente';
    $total = 0;

    foreach ($_SESSION['carrito'] as $item) {
        $total += $item['precio'] * $item['cantidad'];
    }

    // Insertar pedido
    $stmt = $conn->prepare("
        INSERT INTO pedidos (usuario_id, total, estado, cliente, email, direccion, ciudad, metodo_pago)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("idssssss", $usuario_id, $total, $estado, $cliente, $email, $direccion, $ciudad, $metodo_pago);
    $stmt->execute();
    $pedido_id = $stmt->insert_id;
    $stmt->close();

    // ✅ Insertar detalle de productos (se ejecuta antes de redirigir)
    $stmt_det = $conn->prepare("
        INSERT INTO detalle_pedido (pedido_id, producto_id, cantidad, precio)
        VALUES (?, ?, ?, ?)
    ");

    foreach ($_SESSION['carrito'] as $producto_id => $item) {
        $stmt_det->bind_param("iiid", $pedido_id, $producto_id, $item['cantidad'], $item['precio']);
        $stmt_det->execute();

        // Mensaje opcional (solo para verificar que se insertó correctamente)
        if ($stmt_det->error) {
            echo "<p style='color:red;'>❌ Error al insertar detalle: " . $stmt_det->error . "</p>";
        }
    }
    $stmt_det->close();

    // Guardar info para mostrar después
    $_SESSION['ultimo_pedido'] = [
        'id' => $pedido_id,
        'cliente' => $cliente,
        'email' => $email,
        'direccion' => $direccion,
        'ciudad' => $ciudad,
        'metodo_pago' => $metodo_pago,
        'total' => $total,
        'productos' => $_SESSION['carrito']
    ];

    // Vaciar carrito
    $_SESSION['carrito'] = [];

    // 🔹 Redirigir después de insertar todo correctamente
    header("Location: pedido_exitoso.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Checkout - Tienda Plus</title>
<style>
body { font-family: Arial, sans-serif; background: #fafafa; }
form {
  background: #fff;
  max-width: 800px;
  margin: 40px auto;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
h1 { text-align: center; color: #ff69b4; }
label { display: block; margin-top: 10px; }
input, select {
  width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 5px;
}
button {
  background: #ff69b4; color: white; border: none;
  padding: 10px 20px; margin-top: 20px;
  border-radius: 5px; cursor: pointer;
}
button:hover { background: #ff85c1; }
table { width: 100%; border-collapse: collapse; margin-top: 15px; }
th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
th { background: #ffe5f0; }
</style>
</head>
<body>
<h1>Finalizar Compra 🛒</h1>

<form method="POST">
  <label>Nombre completo</label>
  <input type="text" name="nombre" required>

  <label>Correo electrónico</label>
  <input type="email" name="email" required>

  <label>Dirección</label>
  <input type="text" name="direccion" required>

  <label>Ciudad</label>
  <input type="text" name="ciudad" required>

  <label>Método de pago</label>
  <select name="metodo_pago" required>
    <option value="Efectivo">Efectivo</option>
    <option value="Tarjeta">Tarjeta</option>
    <option value="Transferencia">Transferencia</option>
  </select>

  <h3>Resumen del pedido</h3>
  <table>
    <tr><th>Producto</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th></tr>
    <?php
    $total = 0;
    foreach ($_SESSION['carrito'] as $item):
        $subtotal = $item['precio'] * $item['cantidad'];
        $total += $subtotal;
    ?>
    <tr>
      <td><?= htmlspecialchars($item['nombre']) ?></td>
      <td><?= $item['cantidad'] ?></td>
      <td>$<?= number_format($item['precio'], 0, ',', '.') ?></td>
      <td>$<?= number_format($subtotal, 0, ',', '.') ?></td>
    </tr>
    <?php endforeach; ?>
    <tr><td colspan="3"><strong>Total a pagar:</strong></td><td><strong>$<?= number_format($total, 0, ',', '.') ?></strong></td></tr>
  </table>

  <button type="submit" name="finalizar">✅ Confirmar y Finalizar Compra</button>
</form>
</body>
</html>
