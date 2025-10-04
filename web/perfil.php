<?php
session_start();
require_once '../backend/conexion.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$id_usuario]);
$usuario = $stmt->fetch();

echo "<h1>Bienvenido {$usuario['nombre']}</h1>";
echo "<p>Email: {$usuario['email']}</p>";

// Mostrar pedidos del usuario
$stmt = $pdo->prepare("SELECT * FROM pedidos WHERE id_usuario = ? ORDER BY fecha DESC");
$stmt->execute([$id_usuario]);
$pedidos = $stmt->fetchAll();

echo "<h2>Mis pedidos</h2>";
if ($pedidos) {
    foreach ($pedidos as $p) {
        echo "<p>Pedido #{$p['id']} - Fecha: {$p['fecha']}</p>";
    }
} else {
    echo "<p>No has hecho pedidos aún.</p>";
}
// ==========================
// 🔹 Formulario para reseñas
// ==========================
?>
<h2>Dejar una reseña</h2>
<form action="../backend/guardar_resena.php" method="POST">
    <input type="hidden" name="usuario_id" value="<?php echo $id_usuario; ?>">

    <label for="producto_id">ID del producto:</label>
    <input type="number" name="producto_id" required><br><br>

    <label for="comentario">Comentario:</label><br>
    <textarea name="comentario" rows="4" required></textarea><br><br>

    <label for="calificacion">Calificación:</label>
    <select name="calificacion" required>
        <option value="1">1 ⭐</option>
        <option value="2">2 ⭐⭐</option>
        <option value="3">3 ⭐⭐⭐</option>
        <option value="4">4 ⭐⭐⭐⭐</option>
        <option value="5">5 ⭐⭐⭐⭐⭐</option>
    </select><br><br>

    <button type="submit">Enviar reseña</button>
</form>

<?php
// ==========================
// 🔹 Mostrar reseñas del usuario
// ==========================
$stmt = $pdo->prepare("SELECT r.*, p.nombre AS producto 
                       FROM reseñas r
                       JOIN productos p ON r.producto_id = p.id
                       WHERE r.usuario_id = ? 
                       ORDER BY r.fecha DESC");
$stmt->execute([$id_usuario]);
$mis_resenas = $stmt->fetchAll();

echo "<h2>Mis reseñas</h2>";
if ($mis_resenas) {
    foreach ($mis_resenas as $r) {
        echo "<p><strong>{$r['producto']}</strong>: {$r['comentario']} ({$r['calificacion']} ⭐) - {$r['fecha']}</p>";
    }
} else {
    echo "<p>No has dejado reseñas todavía.</p>";
}
?>

