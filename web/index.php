<?php session_start(); ?>
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
    }
    .hero h1 { font-size: 3rem; font-weight: bold; }
    .navbar-brand { font-weight: bold; }
    footer { background: #ff69aaff; color: #ccc; padding: 20px; text-align: center; }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="index.php">TIENDA PLUS</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="menu">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="index.php">Inicio</a></li>
        <li class="nav-item"><a class="nav-link" href="catalogo.php">Catálogo</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Guia de tallas</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Contactanos</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Ayuda</a></li>
      </ul>
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
        <li class="nav-item"><a class="nav-link" href="carrito.php">🛒 
          <?php echo isset($_SESSION['carrito']) ? count($_SESSION['carrito']) : 0; ?>
        </a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Hero -->
<section class="hero">
  <div>
    <h1>Nueva Colección de Invierno</h1>
    <p>Encuentra tu estilo con Tienda Plus</p>
    <a href="catalogo.php" class="btn btn-primary btn-lg">Ver colección</a>
  </div>
</section>

<!-- Productos destacados -->
<div class="container my-5">
  <h2 class="text-center mb-4">Productos destacados</h2>
  <div class="row">
    <div class="col-md-4">
      <div class="card shadow-sm">
        <img src="img/img3.jpg" class="card-img-top" alt="Producto 1">
        <div class="card-body">
          <h5 class="card-title">Abrigo Invierno</h5>
          <p class="card-text">$120.000</p>
          <a href="producto.php?id=1" class="btn btn-outline-dark">Ver más</a>
        </div>
      </div>
    </div>
    <!-- Repite más productos aquí -->
<div class="row">
    <div class="col-md-4">
      <div class="card shadow-sm">
        <img src="img/img4.jpg" class="card-img-top" alt="Producto 1">
        <div class="card-body">
          <h5 class="card-title">Zapatos cuero</h5>
          <p class="card-text">$2200.000</p>
          <a href="producto.php?id=1" class="btn btn-outline-dark">Ver más</a>
        </div>
      </div>

<!-- Footer -->
<footer>
  <p>© 2025 Tienda Plus Size - Todos los derechos reservados</p>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
