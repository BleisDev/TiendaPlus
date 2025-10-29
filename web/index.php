<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Tienda Plus</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { font-family: Arial, sans-serif; }
    .hero {
      background: url("img/img2.jpg") no-repeat center center;
      background-size: cover;
      color: white;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      flex-direction: column;
    }
    .hero h1 { font-size: 3rem; font-weight: bold; }
    .hero p { font-size: 1.2rem; margin-bottom: 20px; }
    .navbar-brand { font-weight: bold; }
    footer { background: #ff69aa; color: #fff; padding: 20px; text-align: center; margin-top: 50px; }
  </style>
</head>
<body>

<!-- 🔹 Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="index.php">TIENDA PLUS</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="menu">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link active" href="index.php">Inicio</a></li>
        <li class="nav-item"><a class="nav-link" href="catalogo.php">Catálogo</a></li>
        <li class="nav-item"><a class="nav-link" href="guia_tallas.php">Guía de tallas</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Contáctanos</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Ayuda</a></li>
      </ul>

      <ul class="navbar-nav">
       <?php if (isset($_SESSION['usuario_id'])): ?>
  <li class="nav-item">
    <a class="nav-link" href="perfil.php">👤 <?php echo htmlspecialchars($_SESSION['nombre']); ?></a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="../backend/logout.php">Cerrar sesión</a>
  </li>
<?php else: ?>
  <li class="nav-item"><a class="nav-link" href="login.php">Iniciar sesión</a></li>
  <li class="nav-item"><a class="nav-link" href="registro.php">Registrarse</a></li>
<?php endif; ?>

        <li class="nav-item">
          <a class="nav-link" href="carrito.php">🛒 
            <?php echo isset($_SESSION['carrito']) ? count($_SESSION['carrito']) : 0; ?>
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- 🔹 Hero -->
<section class="hero">
  <h1>Nueva Colección de Invierno</h1>
  <p>Encuentra tu estilo con Tienda Plus 💖</p>
  <a href="catalogo.php" class="btn btn-light btn-lg">Ver colección</a>
</section>

<!-- 🔹 Productos destacados -->
<div class="container my-5">
  <h2 class="text-center mb-4">Productos destacados</h2>
  <div class="row g-4 justify-content-center">
    <div class="col-md-4">
      <div class="card shadow-sm border-0">
        <img src="img/img3.jpg" class="card-img-top" alt="Abrigo Invierno">
        <div class="card-body text-center">
          <h5 class="card-title">Abrigo Invierno</h5>
          <p class="card-text text-muted">$120.000</p>
          <a href="producto.php?id=1" class="btn btn-outline-dark">Ver más</a>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card shadow-sm border-0">
        <img src="img/img4.jpg" class="card-img-top" alt="Zapatos Cuero">
        <div class="card-body text-center">
          <h5 class="card-title">Zapatos de Cuero</h5>
          <p class="card-text text-muted">$220.000</p>
          <a href="producto.php?id=2" class="btn btn-outline-dark">Ver más</a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- 🔹 Footer -->
<footer>
  <p>© 2025 Tienda Plus Size - Todos los derechos reservados</p>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
