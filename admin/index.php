<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("../backend/conexion.php");

// Consultas rápidas
$totalUsuarios = $conn->query("SELECT COUNT(*) AS total FROM usuarios")->fetch_assoc()['total'];
$totalProductos = $conn->query("SELECT COUNT(*) AS total FROM productos")->fetch_assoc()['total'];
$totalPedidos = $conn->query("SELECT COUNT(*) AS total FROM pedidos")->fetch_assoc()['total'];
$totalResenas = $conn->query("SELECT COUNT(*) AS total FROM resenas")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Admin - Tienda Plus</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .sidebar {
      height: 100vh;
      background: #343a40;
      padding: 20px;
    }
    .sidebar a {
      color: #fff;
      text-decoration: none;
      display: block;
      margin: 10px 0;
      padding: 10px;
      border-radius: 8px;
    }
    .sidebar a:hover {
      background: #495057;
    }
    .card {
      border-radius: 15px;
    }
  </style>
</head>
<body>
  <div class="container-fluid">
    <div class="row">
      
      <!-- Sidebar -->
      <div class="col-md-2 sidebar">
        <h2 class="text-white">Admin</h2>
        <a href="index.php">Dashboard</a>
        <a href="usuarios.php">Usuarios</a>
        <a href="productos.php">Productos</a>
        <a href="pedidos.php">Pedidos</a>
        <a href="resenas.php">Resenas</a>
      </div>

      <!-- Contenido principal -->
      <div class="col-md-10 p-4">
        <h1 class="mb-4">Dashboard</h1>

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
            <div class="card text-bg-success shadow">
              <div class="card-body">
                <h5 class="card-title">Productos</h5>
                <p class="card-text fs-3"><?php echo $totalProductos; ?></p>
              </div>
            </div>
          </div>
          
          <div class="col-md-4">
            <div class="card text-bg-warning shadow">
              <div class="card-body">
                <h5 class="card-title">Pedidos</h5>
                <p class="card-text fs-3"><?php echo $totalPedidos; ?></p>
              </div>
            </div>
          </div>
        
         <div class="col-md-4">
           <a href="resenas.php" style="text-decoration:none;">
          <div class="card text-bg-success shadow">
          <div class="card-body">
        <h5 class="card-title text-white">Resenas</h5>
        <p class="card-text fs-3 text-white"><?php echo $totalResenas; ?> ⭐</p>
      </div>
    </div>
  </a>
</div>


      </div>
    </div>
  </div>
</body>
</html>

