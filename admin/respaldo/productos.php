<?php
// admin/productos.php
// CRUD completo de productos (listar, crear, editar, eliminar)

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
// Protege: solo admin
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../web/login.php");
    exit();
}

// incluir conexión (ajusta ruta si tu conexion.php está en otro lugar)
include_once('../backend/conexion.php');
if (!isset($conn) || !$conn) {
    // fallback: intentar crear conexión simple si no existe $conn
    $conn = new mysqli("localhost", "root", "", "TiendaPlus");
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }
}

// carpeta para subir imágenes (asegúrate que exista y tenga permisos)
$uploadDir = __DIR__ . '/../web/img/products/';
$publicDir = 'img/products/'; // ruta relativa usada en DB (../web/<publicDir>)
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// --- Función helper para obtener tabla categorias/categoria ---
function obtenerCategorias($conn) {
    $cats = $conn->query("SELECT id, nombre FROM categorias ORDER BY nombre ASC");
    if (!$cats || $cats->num_rows === 0) {
        // intentar singular
        $cats = $conn->query("SELECT id, nombre FROM categoria ORDER BY nombre ASC");
    }
    return $cats;
}

// --- Procesar CREAR ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'crear') {
    $nombre      = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $precio      = floatval($_POST['precio'] ?? 0);
    $stock       = intval($_POST['stock'] ?? 0);
    $categoriaId = !empty($_POST['categoria_id']) ? intval($_POST['categoria_id']) : null;

    // Procesar imagen (opcional)
    $imagen_path = null;
    if (!empty($_FILES['imagen']['name'])) {
        $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
        $nuevoNombre = uniqid('p_') . '.' . $ext;
        $destino = $uploadDir . $nuevoNombre;
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $destino)) {
            $imagen_path = $publicDir . $nuevoNombre; // ruta relativa para DB
        }
    }

    $stmt = $conn->prepare("INSERT INTO productos (nombre, descripcion, precio, stock, id_categoria, imagen, fecha_creacion) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssdiis", $nombre, $descripcion, $precio, $stock, $categoriaId, $imagen_path);
    $ok = $stmt->execute();
    $stmt->close();

    header("Location: productos.php");
    exit();
}

// --- Procesar EDITAR ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'editar') {
    $id          = intval($_POST['id'] ?? 0);
    $nombre      = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $precio      = floatval($_POST['precio'] ?? 0);
    $stock       = intval($_POST['stock'] ?? 0);
    $categoriaId = !empty($_POST['categoria_id']) ? intval($_POST['categoria_id']) : null;

    // Obtener imagen antigua
    $old = $conn->query("SELECT imagen FROM productos WHERE id = $id")->fetch_assoc();
    $imagen_path = $old['imagen'];

    // Si se sube nueva imagen, reemplazar
    if (!empty($_FILES['imagen']['name'])) {
        // eliminar imagen antigua si existe
        if (!empty($imagen_path)) {
            $archivoAnt = __DIR__ . '/../web/' . $imagen_path;
            if (file_exists($archivoAnt)) @unlink($archivoAnt);
        }
        $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
        $nuevoNombre = uniqid('p_') . '.' . $ext;
        $destino = $uploadDir . $nuevoNombre;
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $destino)) {
            $imagen_path = $publicDir . $nuevoNombre;
        }
    }

    $stmt = $conn->prepare("UPDATE productos SET nombre=?, descripcion=?, precio=?, stock=?, id_categoria=?, imagen=? WHERE id=?");
    $stmt->bind_param("ssdiisi", $nombre, $descripcion, $precio, $stock, $categoriaId, $imagen_path, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: productos.php");
    exit();
}

// --- Procesar ELIMINAR (vía GET) ---
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    // borrar imagen
    $old = $conn->query("SELECT imagen FROM productos WHERE id = $id")->fetch_assoc();
    if (!empty($old['imagen'])) {
        $archivo = __DIR__ . '/../web/' . $old['imagen'];
        if (file_exists($archivo)) @unlink($archivo);
    }
    $conn->query("DELETE FROM productos WHERE id = $id");
    header("Location: productos.php");
    exit();
}

// --- Preparar edición (si ?editar=ID) ---
$editarProducto = null;
if (isset($_GET['editar'])) {
    $idEdit = intval($_GET['editar']);
    $res = $conn->query("SELECT * FROM productos WHERE id = $idEdit");
    if ($res && $res->num_rows > 0) {
        $editarProducto = $res->fetch_assoc();
    }
}

// --- Obtener listado de productos (intentamos join con categorias/categoria) ---
$sqlCatJoin = "LEFT JOIN categorias c ON p.id_categoria = c.id";
$resCheck = $conn->query("SHOW TABLES LIKE 'categorias'");
if (!$resCheck || $resCheck->num_rows === 0) {
    // intentar singular
    $sqlCatJoin = "LEFT JOIN categoria c ON p.id_categoria = c.id";
}
$sql = "SELECT p.*, c.nombre AS categoria FROM productos p $sqlCatJoin ORDER BY p.id DESC";
$resultado = $conn->query($sql);

// obtener categorías para el select
$cats = obtenerCategorias($conn);
?>

<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Productos - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background:#f8f9fa; }
    .sidebar { height:100vh; background:#343a40; padding:20px; position: fixed; left:0; top:0; width:220px; }
    .sidebar a{ color:white; display:block; padding:8px; margin:6px 0; text-decoration:none; border-radius:6px;}
    .sidebar a:hover{ background:#495057; }
    .content { margin-left:240px; padding:30px; }
    .thumb { width:70px; height:70px; object-fit:cover; border-radius:6px; }
    .form-card{border-radius:12px;}
  </style>
</head>
<body>
  <div class="sidebar">
    <h4 class="text-white">Admin</h4>
    <a href="index.php">Dashboard</a>
    <a href="usuarios.php">Usuarios</a>
    <a href="productos.php" style="background:#495057">Productos</a>
    <a href="categorias.php">Categorías</a>
    <a href="carritos.php">Carritos</a>
    <a href="pedidos.php">Pedidos</a>
    <a href="resenas.php">Reseñas</a>
    <a href="logout.php" class="text-danger">Cerrar sesión</a>
  </div>

  <div class="content">
    <h1 class="mb-4">📦 Productos</h1>

    <!-- Formulario: Crear / Editar -->
    <div class="card mb-4 form-card shadow-sm">
      <div class="card-body">
        <?php if ($editarProducto): ?>
          <h5>Editar producto #<?= $editarProducto['id'] ?></h5>
        <?php else: ?>
          <h5>Agregar nuevo producto</h5>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="row g-3 mt-2">
          <input type="hidden" name="accion" value="<?= $editarProducto ? 'editar' : 'crear' ?>">
          <?php if ($editarProducto): ?>
            <input type="hidden" name="id" value="<?= $editarProducto['id'] ?>">
          <?php endif; ?>

          <div class="col-md-6">
            <label class="form-label">Nombre</label>
            <input class="form-control" name="nombre" required value="<?= htmlspecialchars($editarProducto['nombre'] ?? '') ?>">
          </div>

          <div class="col-md-3">
            <label class="form-label">Precio</label>
            <input class="form-control" name="precio" type="number" step="0.01" required value="<?= htmlspecialchars($editarProducto['precio'] ?? '') ?>">
          </div>

          <div class="col-md-3">
            <label class="form-label">Stock</label>
            <input class="form-control" name="stock" type="number" required value="<?= htmlspecialchars($editarProducto['stock'] ?? 0) ?>">
          </div>

          <div class="col-md-4">
            <label class="form-label">Categoría</label>
            <select name="categoria_id" class="form-select">
              <option value="">-- Sin categoría --</option>
              <?php if ($cats && $cats->num_rows > 0): while($c = $cats->fetch_assoc()): ?>
                <option value="<?= $c['id'] ?>" <?= (isset($editarProducto['id_categoria']) && $editarProducto['id_categoria']==$c['id']) ? 'selected' : '' ?>>
                  <?= htmlspecialchars($c['nombre']) ?>
                </option>
              <?php endwhile; endif; ?>
            </select>
          </div>

          <div class="col-12">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control" rows="3"><?= htmlspecialchars($editarProducto['descripcion'] ?? '') ?></textarea>
          </div>

          <div class="col-md-6">
            <label class="form-label">Imagen (opcional)</label>
            <input type="file" name="imagen" class="form-control">
            <?php if (!empty($editarProducto['imagen'])): ?>
              <small class="text-muted">Imagen actual:</small><br>
              <img src="../web/<?= htmlspecialchars($editarProducto['imagen']) ?>" class="thumb mt-2">
            <?php endif; ?>
          </div>

          <div class="col-12">
            <button class="btn btn-success"><?= $editarProducto ? 'Guardar cambios' : 'Crear producto' ?></button>
            <?php if ($editarProducto): ?>
              <a href="productos.php" class="btn btn-secondary ms-2">Cancelar</a>
            <?php endif; ?>
          </div>
        </form>
      </div>
    </div>

    <!-- Tabla de productos -->
    <div class="card shadow-sm">
      <div class="card-body">
        <?php if ($resultado && $resultado->num_rows > 0): ?>
        <div class="table-responsive">
          <table class="table table-striped align-middle">
            <thead class="table-dark">
              <tr>
                <th>ID</th>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Categoría</th>
                <th>Fecha</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php while($row = $resultado->fetch_assoc()): ?>
              <tr>
                <td><?= $row['id'] ?></td>
                <td>
                  <?php if (!empty($row['imagen'])): ?>
                    <img src="../web/<?= htmlspecialchars($row['imagen']) ?>" class="thumb">
                  <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($row['nombre']) ?></td>
                <td>$<?= number_format($row['precio'], 2, ',', '.') ?></td>
                <td><?= $row['stock'] ?></td>
                <td><?= htmlspecialchars($row['categoria'] ?? 'Sin categoría') ?></td>
                <td><?= $row['fecha_creacion'] ?? $row['fecha'] ?? '' ?></td>
                <td>
                  <a href="productos.php?editar=<?= $row['id'] ?>" class="btn btn-sm btn-primary mb-1">Editar</a>
                  <a href="productos.php?eliminar=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Eliminar producto?')">Eliminar</a>
                </td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
        <?php else: ?>
          <p class="text-muted m-3">No hay productos registrados.</p>
        <?php endif; ?>
      </div>
    </div>

  </div>
</body>
</html>
