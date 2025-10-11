<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// --- Conexión a la base de datos ---
$host = "localhost";
$user = "root";
$pass = "";
$db   = "tiendaplus";
$conn = new mysqli($host, $user, $pass, $db);
if($conn->connect_error) die("Conexión fallida: " . $conn->connect_error);

// --- Usuario logueado ---
$usuario_id = $_SESSION['id_usuario'] ?? null;

// --- Inicializar carrito ---
if(!isset($_SESSION['carrito'])) $_SESSION['carrito'] = [];

// --- Agregar producto al carrito ---
if(isset($_POST['agregar'])){
    $id = intval($_POST['id']);
    $nombre = $_POST['nombre'];
    $precio = floatval($_POST['precio']);
    $imagen = $_POST['imagen'] ?? 'img/products/default.png';
    $cantidad = max(1, intval($_POST['cantidad']));

    // Guardar en sesión
    if(isset($_SESSION['carrito'][$id])){
        $_SESSION['carrito'][$id]['cantidad'] += $cantidad;
    } else {
        $_SESSION['carrito'][$id] = [
            'nombre' => $nombre,
            'precio' => $precio,
            'imagen' => $imagen,
            'cantidad' => $cantidad
        ];
    }

    // Guardar en base de datos si está logueado
    if($usuario_id){
        $stmt = $conn->prepare("
            INSERT INTO carrito (usuario_id, producto_id, cantidad)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE cantidad = VALUES(cantidad)
        ");
        $stmt->bind_param("iii", $usuario_id, $id, $_SESSION['carrito'][$id]['cantidad']);
        $stmt->execute();
    }
}

// --- Eliminar producto ---
if(isset($_POST['eliminar'])){
    $id_eliminar = intval($_POST['eliminar']);
    unset($_SESSION['carrito'][$id_eliminar]);

    if($usuario_id){
        $stmt = $conn->prepare("DELETE FROM carrito WHERE usuario_id=? AND producto_id=?");
        $stmt->bind_param("ii", $usuario_id, $id_eliminar);
        $stmt->execute();
    }
}

// --- Modificar cantidades ---
if(isset($_POST['modificar']) && isset($_POST['cant'])){
    foreach($_POST['cant'] as $id => $cantidad){
        $id = intval($id);
        $cantidad = max(1, intval($cantidad));
        if(isset($_SESSION['carrito'][$id])){
            $_SESSION['carrito'][$id]['cantidad'] = $cantidad;

            if($usuario_id){
                $stmt = $conn->prepare("UPDATE carrito SET cantidad=? WHERE usuario_id=? AND producto_id=?");
                $stmt->bind_param("iii", $cantidad, $usuario_id, $id);
                $stmt->execute();
            }
        }
    }
}

// --- Cargar carrito desde la base de datos al iniciar sesión ---
if($usuario_id){
    $stmt = $conn->prepare("
        SELECT c.producto_id, c.cantidad, p.nombre, p.precio, p.imagen
        FROM carrito c
        JOIN productos p ON p.id = c.producto_id
        WHERE c.usuario_id = ?
    ");
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $_SESSION['carrito'] = []; // limpiar sesión antes de cargar
    while($row = $result->fetch_assoc()){
        $_SESSION['carrito'][$row['producto_id']] = [
            'nombre' => $row['nombre'],
            'precio' => $row['precio'],
            'imagen' => $row['imagen'],
            'cantidad' => $row['cantidad']
        ];
    }
}

// --- Calcular total ---
$total = 0;
foreach($_SESSION['carrito'] as $item){
    $total += $item['precio'] * $item['cantidad'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Carrito - Tienda Plus</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background:#f8f9fa; font-family:Arial,sans-serif; }
.container { max-width:900px; margin:40px auto; }
.card { border-radius:12px; margin-bottom:20px; }
.thumb { width:80px; height:80px; object-fit:cover; border-radius:8px; }
.total { font-size:1.2rem; font-weight:bold; text-align:right; margin-top:20px; }
.btn-pink { background: #f08db2; color: white; font-weight: 600; border-radius: 30px; padding: 10px 20px; border: none; }
.btn-pink:hover { background: #e76da0; color: white; }
</style>
</head>
<body>
<div class="container">
<h1 class="mb-4 text-center">🛒 Tu Carrito</h1>

<?php if(empty($_SESSION['carrito'])): ?>
    <div class="alert alert-info text-center">Tu carrito está vacío.</div>
<?php else: ?>
<form method="POST">
  <?php foreach($_SESSION['carrito'] as $id => $item): ?>
    <div class="card shadow-sm p-3 d-flex flex-row align-items-center">
      <img src="<?= htmlspecialchars($item['imagen'] ?? 'img/products/default.png') ?>" class="thumb me-3">
      <div class="flex-grow-1">
        <h5><?= htmlspecialchars($item['nombre']) ?></h5>
        <p>Precio unitario: $<?= number_format($item['precio'], 0, ',', '.') ?></p>
        <div class="d-flex align-items-center gap-2">
          <label>Cantidad:</label>
          <input type="number" name="cant[<?= $id ?>]" value="<?= $item['cantidad'] ?>" min="1" class="form-control" style="width:70px;">
        </div>
        <p class="mt-2">Subtotal: $<?= number_format($item['precio'] * $item['cantidad'], 0, ',', '.') ?></p>
      </div>
      <div>
        <button type="submit" name="eliminar" value="<?= $id ?>" class="btn btn-danger">Eliminar</button>
      </div>
    </div>
  <?php endforeach; ?>

  <p class="total">Total: $<?= number_format($total,0,',','.') ?></p>

  <div class="d-flex justify-content-between mt-3">
    <button type="submit" name="modificar" class="btn btn-primary">Actualizar cantidades</button>

    <?php if($usuario_id): ?>
      <a href="checkout.php" class="btn btn-pink">Continuar al pago</a>
    <?php else: ?>
      <a href="login.php?redir=checkout.php" class="btn btn-pink">Continuar al pago</a>
    <?php endif; ?>
  </div>
</form>
<?php endif; ?>
</div>
</body>
</html>
