<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// 1️⃣ Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "TiendaPlus");
if ($conn->connect_error) {
    die("❌ Conexión fallida: " . $conn->connect_error);
}

// 2️⃣ Consultar productos
$resultado = $conn->query("SELECT id, nombre, precio, imagen FROM productos");
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Catálogo - Tienda Plus</title>
<style>
body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #fafafa; }
h1 { text-align: center; margin: 30px 0; }

.catalogo {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
  padding: 20px;
  max-width: 1200px;
  margin: auto;
}

.card {
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  padding: 15px;
  text-align: center;
  transition: transform 0.2s;
}
.card:hover { transform: scale(1.03); }

.card img {
  max-width: 100%;
  border-radius: 10px;
  margin-bottom: 10px;
}

.card h3 {
  margin: 10px 0;
  font-size: 18px;
}

.card p {
  font-size: 16px;
  font-weight: bold;
  margin: 10px 0;
}

button {
  background: #ff69b4;
  border: none;
  padding: 10px 15px;
  color: white;
  border-radius: 5px;
  cursor: pointer;
  font-size: 15px;
}
button:hover {
  background: #ff85c1;
}
</style>
</head>
<body>

<h1> Catálogo de Productos</h1>

<div class="catalogo">
<?php while($fila = $resultado->fetch_assoc()): ?>
  <div class="card">
    <?php 
      $img = !empty($fila['imagen']) ? htmlspecialchars($fila['imagen']) : "no-image.jpg";
    ?>
    <img src="imagenes/<?= $img ?>" alt="<?= htmlspecialchars($fila['nombre']) ?>">
    <h3><?= htmlspecialchars($fila['nombre']) ?></h3>
    <p>$<?= number_format($fila['precio'],0,',','.') ?></p>
    <form method="POST" action="carrito.php">
    <input type="hidden" name="id" value="<?= $fila['id'] ?>">
    <button type="submit">Añadir al carrito</button>
</form>

  </div>
<?php endwhile; ?>
</div>

</body>
</html>
<?php $conn->close(); ?>
