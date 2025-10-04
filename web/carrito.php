<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Inicializar carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Si llega producto desde catálogo
if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];

    // Conexión a la base
    $conn = new mysqli("localhost", "root", "", "TiendaPlus");
    if ($conn->connect_error) {
        die("❌ Conexión fallida: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT id, nombre, precio FROM productos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $producto = $res->fetch_assoc();

        // Si ya está en el carrito, aumentar cantidad
        if (isset($_SESSION['carrito'][$id])) {
            $_SESSION['carrito'][$id]['cantidad']++;
        } else {
            $_SESSION['carrito'][$id] = [
                'id' => $producto['id'],
                'nombre' => $producto['nombre'],
                'precio' => $producto['precio'],
                'cantidad' => 1
            ];
        }
    }
    $conn->close();
}

// Calcular total
$total = 0;
foreach ($_SESSION['carrito'] as $item) {
    $total += $item['precio'] * $item['cantidad'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Carrito - Tienda Plus</title>
<style>
body { font-family: Arial, sans-serif; margin:0; padding:0; background:#fafafa; }
h1 { text-align:center; margin:30px 0; }

.carrito {
  max-width: 800px;
  margin: auto;
  background:#fff;
  padding:20px;
  border-radius:12px;
  box-shadow:0 2px 8px rgba(0,0,0,0.1);
}

.item {
  display:flex;
  justify-content:space-between;
  align-items:center;
  padding:12px 0;
  border-bottom:1px solid #eee;
}
.item:last-child { border-bottom:none; }

.item h3 { margin:0; font-size:18px; }
.item p { margin:0; font-size:16px; }

.total { text-align:right; font-size:18px; font-weight:bold; margin-top:20px; }

button {
  background:#ff69b4;
  border:none;
  padding:12px 20px;
  color:white;
  border-radius:8px;
  cursor:pointer;
  font-size:16px;
  margin-top:15px;
}
button:hover { background:#ff85c1; }

.vacio { text-align:center; font-size:18px; color:#666; padding:20px; }
</style>
</head>
<body>

<h1>🛒 Tu Carrito</h1>

<div class="carrito">
  <?php if (empty($_SESSION['carrito'])): ?>
    <p class="vacio">Tu carrito está vacío.</p>
  <?php else: ?>
    <?php foreach ($_SESSION['carrito'] as $item): ?>
      <div class="item">
        <h3><?= htmlspecialchars($item['nombre']) ?> (x<?= $item['cantidad'] ?>)</h3>
        <p>$<?= number_format($item['precio'] * $item['cantidad'], 0, ',', '.') ?></p>
      </div>
    <?php endforeach; ?>

    <p class="total">Total: $<?= number_format($total, 0, ',', '.') ?></p>

    <form method="POST" action="checkout.php">
      <button type="submit">Finalizar compra</button>
    </form>
  <?php endif; ?>
</div>

</body>
</html>
