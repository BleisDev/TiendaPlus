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
body {
  font-family: Arial, sans-serif;
  margin: 0; padding: 0;
  background: #fafafa;
}
h1 {
  text-align: center;
  margin: 30px 0;
}
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
  transition: transform 0.2s, background-color 0.3s;
}
button:hover {
  background: #ff85c1;
  transform: scale(1.05);
}

/* 🔔 Estilo para alerta flotante */
#alert-message {
  position: fixed;
  top: 20px;
  right: 20px;
  background-color: #28a745;
  color: white;
  padding: 12px 20px;
  border-radius: 8px;
  font-weight: bold;
  display: none;
  z-index: 1000;
  box-shadow: 0 3px 10px rgba(0,0,0,0.2);
  opacity: 0;
  transition: opacity 0.3s ease;
}
</style>
</head>
<body>

<h1>Catálogo de Productos</h1>

<!-- 🔔 Contenedor de alerta -->
<div id="alert-message"></div>

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
      <input type="hidden" name="nombre" value="<?= htmlspecialchars($fila['nombre']) ?>">
      <input type="hidden" name="precio" value="<?= $fila['precio'] ?>">
      <input type="hidden" name="cantidad" value="1">
      <button type="submit" name="agregar" class="btn-agregar">Añadir al carrito 🛒</button>
    </form>
  </div>
<?php endwhile; ?>
</div>

<script>
// Animación de alerta flotante al agregar producto
document.addEventListener("DOMContentLoaded", () => {
  const alerta = document.getElementById("alert-message");

  function mostrarAlerta(mensaje, tipo = "success") {
    alerta.textContent = mensaje;
    alerta.style.backgroundColor = tipo === "success" ? "#28a745" : "#dc3545";
    alerta.style.display = "block";
    alerta.style.opacity = "1";
    setTimeout(() => {
      alerta.style.opacity = "0";
      setTimeout(() => { alerta.style.display = "none"; }, 500);
    }, 2000);
  }

  // Mostrar animación al presionar "Agregar al carrito"
  document.querySelectorAll(".btn-agregar").forEach(boton => {
    boton.addEventListener("click", (e) => {
      mostrarAlerta("🛒 Producto agregado al carrito ✅");
    });
  });
});
</script>

</body>
</html>
<?php $conn->close(); ?>
