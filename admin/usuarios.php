<?php
include("../backend/conexion.php");
$resultado = $conn->query("SELECT * FROM usuarios");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Usuarios - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-5">
    <h1 class="mb-4">Usuarios Registrados</h1>
    <table class="table table-striped table-hover shadow">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Email</th>
          <th>Rol</th>
        </tr>
      </thead>
      <tbody>
        <?php while($fila = $resultado->fetch_assoc()) { ?>
        <tr>
          <td><?php echo $fila['id']; ?></td>
          <td><?php echo $fila['nombre']; ?></td>
          <td><?php echo $fila['email']; ?></td>
          <td><?php echo $fila['rol']; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</body>
</html>
