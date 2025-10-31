<?php
session_start();
include_once("conexion.php");

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: ../web/login.php");
    exit;
}

$tabla = $_GET['tabla'] ?? 'usuarios';
$accion = $_GET['accion'] ?? '';
$id = $_GET['id'] ?? '';

function valor_por_defecto($tabla, $columna) {
    $defaults = [
        'productos' => ['imagen' => 'sin_imagen.png'],
        'resenas' => ['calificacion' => 1],
        'pedidos' => ['estado' => 'pendiente']
    ];
    return $defaults[$tabla][$columna] ?? '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_post = $_POST['id'] ?? '';
    if ($id_post != '') {
        $sets = [];
        foreach ($_POST as $campo => $valor) {
            if ($campo != 'id') {
                $sets[] = "$campo='" . $conn->real_escape_string($valor) . "'";
            }
        }
        $sql = "UPDATE $tabla SET " . implode(",", $sets) . " WHERE id='$id_post'";
        $conn->query($sql);
    } else {
        $campos_insert = [];
        $valores_insert = [];
        $cols = $conn->query("SHOW COLUMNS FROM $tabla");
        while ($col = $cols->fetch_assoc()) {
            $campo = $col['Field'];
            if ($campo == 'id') continue;
            $valor = $_POST[$campo] ?? valor_por_defecto($tabla, $campo);
            if ($valor === '') $valor = valor_por_defecto($tabla, $campo);
            if (strpos($col['Type'], 'int') !== false && !is_numeric($valor)) $valor = 0;
            $campos_insert[] = $campo;
            $valores_insert[] = "'" . $conn->real_escape_string($valor) . "'";
        }
        $sql = "INSERT INTO $tabla (" . implode(",", $campos_insert) . ") VALUES (" . implode(",", $valores_insert) . ")";
        $conn->query($sql);
    }
    header("Location: panel.php?tabla=$tabla");
    exit;
}

if ($accion == 'eliminar' && $id) {
    $conn->query("DELETE FROM $tabla WHERE id='$id'");
    header("Location: panel.php?tabla=$tabla");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel Admin - TIENDA PLUS</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<script src="https://kit.fontawesome.com/4b6e2a2f87.js" crossorigin="anonymous"></script>
<style>
:root {
    --rosa: #ff69b4;
    --fondo: #f8f9fc;
    --gris: #444;
}
body {
    font-family: 'Poppins', sans-serif;
    background-color: var(--fondo);
}
.sidebar {
    position: fixed;
    left: 0;
    top: 0;
    width: 260px;
    height: 100%;
    background-color: #111;
    padding: 30px 20px;
    color: #fff;
}
.sidebar h4 {
    color: var(--rosa);
    text-align: center;
    font-weight: 700;
    margin-bottom: 25px;
}
.sidebar a {
    display: flex;
    align-items: center;
    color: #ddd;
    padding: 10px 15px;
    border-radius: 8px;
    margin-bottom: 10px;
    text-decoration: none;
    transition: 0.3s;
}
.sidebar a i {
    margin-right: 10px;
}
.sidebar a:hover, .sidebar a.active {
    background-color: var(--rosa);
    color: #fff;
}
.content {
    margin-left: 280px;
    padding: 30px;
}
header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: white;
    border-radius: 12px;
    padding: 15px 25px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.05);
    margin-bottom: 25px;
}
header h1 {
    font-size: 1.6em;
    color: var(--rosa);
    font-weight: 700;
}
.stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
    gap: 20px;
    margin-bottom: 25px;
}
.card-stat {
    background: white;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.05);
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.card-stat i {
    font-size: 30px;
    color: var(--rosa);
}
.card-stat h5 {
    margin: 0;
    font-weight: 600;
}
.table {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}
.table th {
    background-color: var(--rosa);
    color: white;
}
.btn-primary {
    background-color: var(--rosa);
    border: none;
}
.btn-primary:hover {
    background-color: #ff4fa4;
}
.btn {
    border-radius: 8px;
}
</style>
</head>
<body>

<div class="sidebar">
    <h4><i class="fa-solid fa-shop"></i> TIENDA PLUS ADMIN</h4>
    <a href="panel.php?tabla=usuarios"><i class="fa-solid fa-user"></i> Usuarios</a>
    <a href="panel.php?tabla=productos"><i class="fa-solid fa-box"></i> Productos</a>
    <a href="panel.php?tabla=categorias"><i class="fa-solid fa-tags"></i> Categorías</a>
    <a href="panel.php?tabla=pedidos"><i class="fa-solid fa-cart-shopping"></i> Pedidos</a>
    <a href="panel.php?tabla=detalle_pedido"><i class="fa-solid fa-list"></i> Detalle Pedido</a>
    <a href="panel.php?tabla=resenas"><i class="fa-solid fa-comment-dots"></i> Reseñas</a>
    <a href="panel.php?tabla=carrito"><i class="fa-solid fa-basket-shopping"></i> Carrito</a>
    <hr>
    <a href="../web/index.php" class="btn btn-light w-100"><i class="fa-solid fa-house"></i> Volver a Tienda</a>
</div>

<div class="content">
    <header>
        <h1>Panel de Administración</h1>
        <div>👋 Bienvenido, <strong><?= $_SESSION['nombre'] ?? 'Administrador' ?></strong></div>
    </header>

    <div class="stats">
        <div class="card-stat"><div><h5>Usuarios</h5><p><?= $conn->query("SELECT COUNT(*) AS c FROM usuarios")->fetch_assoc()['c'] ?></p></div><i class="fa-solid fa-user"></i></div>
        <div class="card-stat"><div><h5>Productos</h5><p><?= $conn->query("SELECT COUNT(*) AS c FROM productos")->fetch_assoc()['c'] ?></p></div><i class="fa-solid fa-box"></i></div>
        <div class="card-stat"><div><h5>Pedidos</h5><p><?= $conn->query("SELECT COUNT(*) AS c FROM pedidos")->fetch_assoc()['c'] ?></p></div><i class="fa-solid fa-cart-shopping"></i></div>
        <div class="card-stat"><div><h5>Reseñas</h5><p><?= $conn->query("SELECT COUNT(*) AS c FROM resenas")->fetch_assoc()['c'] ?></p></div><i class="fa-solid fa-comment"></i></div>
    </div>

    <h2 class="mb-3">📋 Tabla: <?= ucfirst($tabla) ?></h2>
    <?php
    if ($accion == 'editar' || $accion == 'nuevo') {
        $fila = [];
        if ($accion == 'editar') {
            $res = $conn->query("SELECT * FROM $tabla WHERE id='$id'");
            $fila = $res->fetch_assoc();
        }

        $cols = $conn->query("SHOW COLUMNS FROM $tabla");
        echo "<form method='POST' class='card p-4'>";
        if ($accion == 'editar') echo "<input type='hidden' name='id' value='$id'>";

        while ($col = $cols->fetch_assoc()) {
            $campo = $col['Field'];
            $valor = $fila[$campo] ?? valor_por_defecto($tabla, $campo);
            $readonly = ($campo == 'id') ? 'readonly' : '';
            echo "<div class='mb-3'><label class='form-label'>".ucfirst($campo)."</label>
                  <input type='text' name='$campo' class='form-control' value='$valor' $readonly></div>";
        }

        echo "<button class='btn btn-success'>Guardar</button>
              <a href='panel.php?tabla=$tabla' class='btn btn-secondary'>Cancelar</a></form>";
    } else {
        $res = $conn->query("SELECT * FROM $tabla");
        echo "<a href='panel.php?tabla=$tabla&accion=nuevo' class='btn btn-primary mb-3'>+ Crear nuevo</a>";
        echo "<div class='table-responsive'><table class='table table-striped'>";
        if ($res->num_rows > 0) {
            echo "<tr>";
            foreach ($res->fetch_fields() as $f) echo "<th>{$f->name}</th>";
            echo "<th>Acciones</th></tr>";
            $res->data_seek(0);
            while ($row = $res->fetch_assoc()) {
                echo "<tr>";
                foreach ($row as $v) echo "<td>$v</td>";
                echo "<td>
                    <a href='panel.php?tabla=$tabla&accion=editar&id={$row['id']}' class='btn btn-warning btn-sm'>Editar</a>
                    <a href='panel.php?tabla=$tabla&accion=eliminar&id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"¿Eliminar este registro?\")'>Eliminar</a>
                </td></tr>";
            }
        } else {
            echo "<tr><td colspan='99' class='text-center'>No hay registros</td></tr>";
        }
        echo "</table></div>";
    }
    ?>
</div>
</body>
</html>
