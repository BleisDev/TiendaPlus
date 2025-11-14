<?php
session_start();
$conexion = new mysqli("localhost", "root", "", "TiendaPlus");


if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Consulta para mostrar productos destacados
$query = "SELECT * FROM productos WHERE destacado = 1";
$resultado = $conexion->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tienda Plus</title>
  <style>
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background-color: #fff;
      color: #333;
    }

    /* Header */
    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px 60px;
      border-bottom: 1px solid #eee;
      background-color: #fff;
      position: sticky;
      top: 0;
      z-index: 100;
    }

    .logo {
      font-size: 28px;
      font-weight: 700;
      letter-spacing: 2px;
      color: #ff4d6d;
    }

    nav a {
      margin: 0 20px;
      text-decoration: none;
      color: #000;
      font-weight: 500;
    }

    nav a:hover {
      color: #ff4d6d;
    }

    .icons {
      display: flex;
      align-items: center;
      gap: 20px;
      font-size: 22px;
    }

    .icons a {
      color: #333;
      text-decoration: none;
    }

    .user-info {
      font-size: 16px;
      background: #ff4d6d;
      color: white;
      padding: 8px 15px;
      border-radius: 30px;
      font-weight: bold;
    }

    .logout {
      font-size: 14px;
      color: #ff4d6d;
      text-decoration: none;
      margin-left: 10px;
    }

    .logout:hover {
      text-decoration: underline;
    }

    /* Banner principal */
    .banner {
      position: relative;
      width: 100%;
      height: 700px;
      background-image: url('img/img2.jpg');
      background-size: cover;
      background-position: center;
      display: flex;
      align-items: center;
      justify-content: flex-start;
      color: white;
    }

    .banner-content {
      margin-left: 80px;
      background: rgba(0, 0, 0, 0.5);
      padding: 30px;
      border-radius: 12px;
      max-width: 400px;
    }

    .banner-content h1 {
      font-size: 42px;
      margin-bottom: 20px;
      line-height: 1.2;
    }

    .banner-content a {
      display: inline-block;
      padding: 14px 30px;
      background-color: white;
      color: #000;
      font-weight: bold;
      text-decoration: none;
      border-radius: 30px;
    }

    .banner-content a:hover {
      background-color: #ff4d6d;
      color: #fff;
    }

    /* Productos */
    .productos {
      padding: 60px 80px;
      text-align: center;
    }

    .productos h2 {
      font-size: 30px;
      margin-bottom: 40px;
      font-weight: 700;
    }

    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 30px;
    }

    .producto {
      border: 1px solid #eee;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 4px 8px rgba(0,0,0,0.05);
      transition: transform 0.2s;
    }

    .producto:hover {
      transform: translateY(-5px);
    }

    .producto img {
      width: 100%;
      height: 320px;
      object-fit: cover;
    }

    .producto-info {
      padding: 15px;
    }

    .producto-info h3 {
      font-size: 18px;
      margin: 10px 0;
    }

    .producto-info p {
      font-size: 16px;
      color: #ff4d6d;
      font-weight: bold;
    }

    footer {
      background-color: #f5f5f5;
      text-align: center;
      padding: 25px;
      font-size: 14px;
      color: #666;
      margin-top: 60px;
    }
  </style>
</head>
<body>

<header>
  <div class="logo">TIENDA PLUS</div>
  <nav>
    <a href="ayuda.php">Novedades</a>
    <a href="catalogo.php">Ropa</a>
    <a href="contactanos.php">Tendencias</a>
    <a href="guia_tallas.php">Guía de estilo</a>
    <a href="nosotros.php">Nosotros</a>
  </nav>

  <div class="icons">
    <?php if(isset($_SESSION["nombre"])): ?>
        <span class="user-info">👤 <?php echo htmlspecialchars($_SESSION["nombre"]); ?></span>
        <a href="../backend/logout.php" class="logout">Cerrar sesión</a>
    <?php else: ?>
        <a href="login.php" title="Mi cuenta">👤</a>
    <?php endif; ?>
    <a href="resenas.php" title="Reseñas">♡</a>
    <a href="carrito.php" title="Carrito">🛒</a>
  </div>
</header>

<section class="banner">
  <img src="img/banner1.jpg" alt="Tienda Plus Size" class="banner-img">
  <div class="banner-text">
    <h2>Moda Plus Size</h2>
    <p>Encuentra tu estilo, siente tu confianza 💖</p>
    <a href="catalogo.php" class="btn">Ver Catálogo</a>
  </div>
</section>


<section class="productos">
  <h2>Productos Destacados</h2>
  <div class="grid">
    <?php while ($fila = $resultado->fetch_assoc()) { ?>
      <div class="producto">
        <img src="img/<?php echo $fila['imagen']; ?>" alt="<?php echo $fila['nombre']; ?>">
        <div class="producto-info">
          <h3><?php echo $fila['nombre']; ?></h3>
          <p>$<?php echo number_format($fila['precio'], 2); ?></p>
        </div>
      </div>
    <?php } ?>
  </div>
</section>

<footer>
  © 2025 TIENDA PLUS | Todos los derechos reservados
</footer>

</body>
</html>
