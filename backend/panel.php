<?php
session_start();
include_once("conexion.php");

// Verificar si es admin
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: ../web/login.php");
    exit;
}

$tabla = isset($_GET['tabla']) ? $_GET['tabla'] : 'usuarios';
$accion = $_GET['accion'] ?? '';
$id = $_GET['id'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Guardar cambios o nuevo registro
    if (isset($_POST['id']) && $_POST['id'] != '') {
        // UPDATE
        $id = $_POST['id'];
        $sets = [];
        foreach ($_POST as $campo => $valor) {
            if ($campo != 'id') {
                $sets[] = "$campo='" . $conn->real_escape_string($valor) . "'";
            }
        }
        $sql = "UPDATE $tabla SET " . implode(",", $sets) . " WHERE id='$id'";
        $conn->query($sql);
    } else {
        // INSERT (ignora campo ID autoincremental)
        $campos_insert = [];
        $valores_insert = [];

        foreach ($_POST as $campo => $valor) {
            if ($campo !== 'id' && $valor !== '') { 
                $campos_insert[] = $campo;
                $valores_insert[] = "'" . $conn->real_escape_string($valor) . "'";
            }
        }

        if (!empty($campos_insert)) {
            $campos_str = implode(",", $campos_insert);
            $valores_str = implode(",", $valores_insert);
            $sql = "INSERT INTO $tabla ($campos_str) VALUES ($valores_str)";
            $conn->query($sql);
        }
    }

    header("Location: panel.php?tabla=$tabla");
    exit;
}

// ELIMINAR
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
    <title>Panel Administrador - Tienda Plus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; font-family: Arial; }
        .sidebar { height: 100vh; background: #212529; color: white; padding: 20px; }
        .sidebar a { display: block; color: #ccc; margin: 10px 0; text-decoration: none; }
        .sidebar a:hover { color: white; }
        .content { padding: 20px; }
        table { background: white; }
        .btn-custom { border-radius: 20px; }
    </style>
</head>
<body>
<div class="d-flex">
    <div class="sidebar">
        <h4>Panel Admin</h4>
        <a href="panel.php?tabla=usuarios">Usuarios</a>
        <a href="panel.php?tabla=productos">Productos</a>
        <a href="panel.php?tabla=categorias">Categorías</a>
        <a href="panel.php?tabla=pedidos">Pedidos</a>
        <a href="panel.php?tabla=detalle_pedido">Detalle Pedido</a>
        <a href="panel.php?tabla=ventas">Ventas</a>
        <a href="panel.php?tabla=resenas">Reseñas</a>
        <a href="panel.php?tabla=carrito">Carrito</a>
        <hr>
        <a href="../web/index.php" class="btn btn-light btn-sm">Volver a Tienda</a>
    </div>

    <div class="content w-100">
        <h2 class="mb-4">Tabla: <?= ucfirst($tabla) ?></h2>

        <?php
        // MOSTRAR FORMULARIO EDITAR / NUEVO
        if ($accion == 'editar' || $accion == 'nuevo') {
            if ($accion == 'editar') {
                $res = $conn->query("SELECT * FROM $tabla WHERE id='$id'");
                $fila = $res->fetch_assoc();
            } else {
                $fila = [];
            }

            $cols = $conn->query("SHOW COLUMNS FROM $tabla");
            echo "<form method='POST' class='card p-4'>";
            foreach ($cols as $col) {
                $campo = $col['Field'];
                $valor = $fila[$campo] ?? '';
                $readonly = ($campo == 'id') ? 'readonly' : '';
                echo "
                <div class='mb-3'>
                    <label class='form-label'>".ucfirst($campo)."</label>
                    <input type='text' name='$campo' class='form-control' value='$valor' $readonly>
                </div>";
            }
            echo "<button class='btn btn-success'>Guardar</button>
                  <a href='panel.php?tabla=$tabla' class='btn btn-secondary'>Cancelar</a>
                  </form>";
        } else {
            // LISTAR REGISTROS
            $res = $conn->query("SELECT * FROM $tabla");
            echo "<a href='panel.php?tabla=$tabla&accion=nuevo' class='btn btn-primary mb-3 btn-custom'>+ Crear nuevo</a>";
            echo "<table class='table table-bordered table-striped'><tr>";

            if ($res->num_rows > 0) {
                $fields = $res->fetch_fields();
                foreach ($fields as $f) echo "<th>{$f->name}</th>";
                echo "<th>Acciones</th></tr>";

                $res->data_seek(0);
                while ($row = $res->fetch_assoc()) {
                    echo "<tr>";
                    foreach ($row as $val) echo "<td>$val</td>";
                    
                    // 🔹 Botones de acción (Editar, Eliminar, Ver pedido)
                    echo "<td>
                        <a href='panel.php?tabla=$tabla&accion=editar&id={$row['id']}' class='btn btn-warning btn-sm'>Editar</a>
                        <a href='panel.php?tabla=$tabla&accion=eliminar&id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"¿Eliminar este registro?\")'>Eliminar</a>";
                    
                    // ✅ Solo mostrar el botón Ver pedido si estás viendo la tabla pedidos
                    if ($tabla == 'pedidos') {
                        echo " <a href='../web/detalle_pedido.php?id={$row['id']}' class='btn btn-info btn-sm'>Ver pedido</a>";
                    }

                    echo "</td></tr>";
                }
            } else {
                echo "<tr><td colspan='99' class='text-center'>No hay registros</td></tr>";
            }

            echo "</table>";
        }
        ?>
    </div>
</div>
</body>
</html>

