<?php
session_start();
require_once('../backend/conexion.php');

// Validar sesión (ajústalo si no tienes login)
$usuario_id = $_SESSION['usuario_id'] ?? 1; // ID de prueba temporal

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $producto_id = intval($_POST['producto_id']);
    $comentario = trim($_POST['comentario']);
    $calificacion = intval($_POST['calificacion']);

    // Validar existencia del producto
    $check = $conn->prepare("SELECT id FROM productos WHERE id = ?");
    $check->bind_param("i", $producto_id);
    $check->execute();
    $result = $check->get_result();
    
    if ($result->num_rows > 0) {
        $stmt = $conn->prepare("INSERT INTO resenas (usuario_id, producto_id, comentario, calificacion) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iisi", $usuario_id, $producto_id, $comentario, $calificacion);
        $stmt->execute();
        $stmt->close();
        $mensaje = "✅ Reseña agregada correctamente.";
    } else {
        $mensaje = "⚠️ El producto no existe.";
    }
    $check->close();
}

// Obtener productos para el menú desplegable
$productos = $conn->query("SELECT id, nombre FROM productos ORDER BY nombre ASC");

// Obtener reseñas
$resenas = $conn->query("
    SELECT r.*, p.nombre AS producto
    FROM resenas r
    INNER JOIN productos p ON r.producto_id = p.id
    ORDER BY r.fecha DESC
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>✨ Reseñas - WOMENAL PLUS ✨</title>
<style>
body {
  font-family: 'Poppins', sans-serif;
  background-color: #fff8fb;
  margin: 0;
  padding: 0;
  color: #444;
}
header {
  background: linear-gradient(90deg, #ff80ab, #ff4081);
  color: white;
  text-align: center;
  padding: 20px;
  font-size: 26px;
  font-weight: 600;
  letter-spacing: 1px;
}
.contenedor {
  max-width: 900px;
  margin: 40px auto;
  background: white;
  border-radius: 15px;
  padding: 30px;
  box-shadow: 0 6px 25px rgba(0, 0, 0, 0.08);
}
form {
  display: flex;
  flex-direction: column;
  gap: 15px;
}
select, textarea, input[type="number"] {
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 8px;
  font-size: 15px;
}
button {
  background: #ff4081;
  color: white;
  border: none;
  padding: 12px;
  border-radius: 10px;
  font-size: 16px;
  cursor: pointer;
  transition: background 0.3s;
}
button:hover {
  background: #ff6fa9;
}
.resenas {
  margin-top: 40px;
}
.resena {
  border-bottom: 1px solid #eee;
  padding: 15px 0;
  animation: fadeIn 0.6s ease;
}
.resena strong {
  color: #ff4081;
}
@keyframes fadeIn {
  from {opacity: 0; transform: translateY(10px);}
  to {opacity: 1; transform: translateY(0);}
}
.mensaje {
  background: #ffe5f1;
  border-left: 5px solid #ff4081;
  padding: 10px 15px;
  border-radius: 8px;
  margin-bottom: 20px;
  color: #c2185b;
  font-weight: 500;
}
</style>
</head>
<body>

<header>💬 Reseñas - WOMENAL PLUS 💖</header>

<div class="contenedor">
<?php if (!empty($mensaje)): ?>
  <div class="mensaje"><?= htmlspecialchars($mensaje) ?></div>
<?php endif; ?>

<h2>✨ Deja tu reseña ✨</h2>
<form method="POST">
  <label>Producto:</label>
  <select name="producto_id" required>
    <option value="">Selecciona un producto</option>
    <?php while($p = $productos->fetch_assoc()): ?>
      <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nombre']) ?></option>
    <?php endwhile; ?>
  </select>

  <label>Comentario:</label>
  <textarea name="comentario" rows="4" required></textarea>

  <label>Calificación (1-5):</label>
  <input type="number" name="calificacion" min="1" max="5" required>

  <button type="submit">Enviar reseña ⭐</button>
</form>

<div class="resenas">
  <h2>📋 Reseñas recientes</h2>
  <?php while($r = $resenas->fetch_assoc()): ?>
    <div class="resena">
      <strong><?= htmlspecialchars($r['producto']) ?></strong><br>
      <?= htmlspecialchars($r['comentario']) ?><br>
      ⭐ <?= $r['calificacion'] ?>/5
      <div style="font-size: 12px; color: gray;"><?= $r['fecha'] ?></div>
    </div>
  <?php endwhile; ?>
</div>
</div>
</body>
</html>
