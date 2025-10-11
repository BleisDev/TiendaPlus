<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conn = new mysqli("localhost", "root", "", "TiendaPlus");
if ($conn->connect_error) {
    die("❌ Conexión fallida: " . $conn->connect_error);
}

// --- Consultas rápidas ---
$totalUsuarios  = $conn->query("SELECT COUNT(*) AS total FROM usuarios")->fetch_assoc()['total'];
$totalProductos = $conn->query("SELECT COUNT(*) AS total FROM productos")->fetch_assoc()['total'];
$totalPedidos   = $conn->query("SELECT COUNT(*) AS total FROM pedidos")->fetch_assoc()['total'];
$totalResenas   = $conn->query("SELECT COUNT(*) AS total FROM resenas")->fetch_assoc()['total'];

// --- Carritos ---
$totalCarritos = 0;
$resultCarritos = $conn->query("SELECT COUNT(*) AS total FROM carrito");
if ($resultCarritos) {
    $totalCarritos = $resultCarritos->fetch_assoc()['total'];
}

// --- Categorías ---
$totalCategorias = 0;
$resultCategorias = $conn->query("SELECT COUNT(*) AS total FROM categorias");
if ($resultCategorias) {
    $totalCategorias = $resultCategorias->fetch_assoc()['total'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel de Administración - Tienda Plus</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
    }
    .sidebar {
      height: 100vh;
      background: #343a40;
      padding: 20px;
      position: fixed;
      left: 0;
      top: 0;
    }
    .sidebar h2 {
      color: #fff;
      margin-bottom: 20px;
    }
    .sidebar a {
      color: #fff;
      text-decoration: none;
      display: block;
      margin: 10px 0;
      padding: 10px;
      border-radius: 8px;
      transition: background 0.3s;
    }
    .sidebar a:hover {
      background: #495057;
    }
    .content {
      margin-left: 250px;
      padding: 30px;
    }
    .card {
      border-radius: 15px;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="sidebar">
    <h2>Admin</h2>
    <a href="index.php">🏠 Dashboard</a>
    <a href="usuarios.php">👥 Usuarios</a>
    <a href="productos.php">📦 Productos</a>
    <a href="categorias.php">📂 Categorías</a>
    <a href="carritos.php">🛒 Carritos</a>
    <a href="pedidos.php">📑 Pedidos</a>
    <a href="resenas.php">⭐ Reseñas</a>
    <a href="../logout.php">🚪 Cerrar sesión</a>
  </div>

  <div class="content">
    <h1 class="mb-4">Panel de Administración</h1>

    <div class="row g-4">
      <div class="col-md-4">
        <a href="usuarios.php" style="text-decoration:none;">
          <div class="card text-bg-primary shadow">
            <div class="card-body">
              <h5 class="card-title text-white">Usuarios</h5>
              <p class="card-text fs-3 text-white"><?php echo $totalUsuarios; ?></p>
            </div>
          </div>
        </a>
      </div>

      <div class="col-md-4">
        <a href="productos.php" style="text-decoration:none;">
          <div class="card text-bg-success shadow">
            <div class="card-body">
              <h5 class="card-title text-white">Productos</h5>
              <p class="card-text fs-3 text-white"><?php echo $totalProductos; ?></p>
            </div>
          </div>
        </a>
      </div>

      <div class="col-md-4">
        <a href="pedidos.php" style="text-decoration:none;">
          <div class="card text-bg-warning shadow">
            <div class="card-body">
              <h5 class="card-title">Pedidos</h5>
              <p class="card-text fs-3"><?php echo $totalPedidos; ?></p>
            </div>
          </div>
        </a>
      </div>

      <div class="col-md-4">
        <a href="carritos.php" style="text-decoration:none;">
          <div class="card text-bg-info shadow">
            <div class="card-body">
              <h5 class="card-title text-white"
