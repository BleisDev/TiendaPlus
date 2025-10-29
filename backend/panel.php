<?php
session_start();
include_once("conexion.php");

// Verificar si es admin
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: ../web/login.php");
    exit;
}

$tabla = $_GET['tabla'] ?? 'usuarios';
$accion = $_GET['accion'] ?? '';
$id = $_GET['id'] ?? '';

// Función para valores por defecto según tabla y columna
function valor_por_defecto($tabla, $columna) {
    $defaults = [
        'productos' => ['imagen' => 'sin_imagen.png'],
        'resenas' => ['calificacion' => 1],
        'pedidos' => ['estado' => 'pendiente']
    ];
    return $defaults[$tabla][$columna] ?? '';
}

// --- PROCESAR FORMULARIO ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_post = $_POST['id'] ?? '';
    if ($id_post != '') {
        // UPDATE
        $sets = [];
        foreach ($_POST as $campo => $valor) {
            if ($campo != 'id') {
                $sets[] = "$campo='" . $conn->real_escape_string($valor) . "'";
            }
        }
        $sql = "UPDATE $tabla SET " . implode(",", $sets) . " WHERE id='$id_post'";
        if (!$conn->query($sql)) {
            echo "<p class='text-danger'>Error al actualizar: " . $conn->error . "</p>";
        }
    } else {
        // INSERT
        $campos_insert = [];
        $valores_insert = [];

        $cols = $conn->query("SHOW COLUMNS FROM $tabla");
        while ($col = $cols->fetch_assoc()) {
            $campo = $col['Field'];
            if ($campo == 'id') continue;

            $valor = $_POST[$campo] ?? '';

            // Aplicar valor por defecto si existe
            if ($valor === '') {
                $valor = valor_por_defecto($tabla, $campo);
            }

            // Evitar errores en campos numéricos
            if (strpos($col['Type'], 'int') !== false && !is_numeric($valor)) {
                $valor = 0;
            }

            $campos_insert[] = $campo;
            $valores_insert[] = "'" . $conn->real_escape_string($valor) . "'";
        }

        $sql = "INSERT INTO $tabla (" . implode(",", $campos_insert) . ") VALUES (" . implode(",", $valores_insert) . ")";
        if (!$conn->query($sql)) {
            echo "<p class='text-danger'>Error al crear registro: " . $conn->error . "</p>";
        }
    }
    header("Location: panel.php?tabla=$tabla");
    exit;
}

// --- ELIMINAR ---
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
        <a href="panel.php?tabla=resenas">Reseñas</a>
        <a href="panel.php?tabla=carrito">Carrito</a>
        <hr>
        <a href="../web/index.php" class="btn btn-light btn-sm">Volver a Tienda</a>
    </div>

    <div class="content w-100">
        <h2 class="mb-4">Tabla: <?= ucfirst($tabla) ?></h2>

        <?php
        if ($accion == 'editar' || $accion == 'nuevo') {
            $fila = [];
            if ($accion == 'editar') {
                $res = $conn->query("SELECT * FROM $tabla WHERE id='$id'");
                $fila = $res->fetch_assoc();
            }

            $cols = $conn->query("SHOW COLUMNS FROM $tabla");
            echo "<form method='POST' class='card p-4'>";
            if ($accion == 'editar') {
                echo "<input type='hidden' name='id' value='$id'>";
            }

            while ($col = $cols->fetch_assoc()) {
                $campo = $col['Field'];
                $valor = $fila[$campo] ?? valor_por_defecto($tabla, $campo);
                $readonly = ($campo == 'id') ? 'readonly' : '';

                // Campos especiales
                if ($campo == 'imagen') {
                    echo "<div class='mb-3'>
                            <label class='form-label'>".ucfirst($campo)."</label>
                            <input type='text' name='$campo' class='form-control' value='$valor' placeholder='Nombre de archivo o URL'>
                          </div>";
                } elseif ($campo == 'calificacion' && $tabla == 'resenas') {
                    echo "<div class='mb-3'>
                            <label class='form-label'>Calificación</label>
                            <select name='calificacion' class='form-control'>";
                    for ($i=1; $i<=5; $i++) {
                        $sel = ($i == $valor) ? 'selected' : '';
                        echo "<option value='$i' $sel>$i</option>";
                    }
                    echo "</select></div>";
                } elseif ($campo == 'estado' && $tabla == 'pedidos') {
                    echo "<div class='mb-3'>
                            <label class='form-label'>Estado</label>
                            <select name='estado' class='form-control'>
                                <option value='pendiente' ".($valor=='pendiente'?'selected':'').">Pendiente</option>
                                <option value='procesando' ".($valor=='procesando'?'selected':'').">Procesando</option>
                                <option value='completado' ".($valor=='completado'?'selected':'').">Completado</option>
                                <option value='cancelado' ".($valor=='cancelado'?'selected':'').">Cancelado</option>
                            </select>
                          </div>";
                } else {
                    echo "<div class='mb-3'>
                            <label class='form-label'>".ucfirst($campo)."</label>
                            <input type='text' name='$campo' class='form-control' value='$valor' $readonly>
                          </div>";
                }
            }

            echo "<button class='btn btn-success'>Guardar</button>
                  <a href='panel.php?tabla=$tabla' class='btn btn-secondary'>Cancelar</a>
                  </form>";

        } else {
            // LISTADO
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

                    echo "<td>
                        <a href='panel.php?tabla=$tabla&accion=editar&id={$row['id']}' class='btn btn-warning btn-sm'>Editar</a>
                        <a href='panel.php?tabla=$tabla&accion=eliminar&id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"¿Eliminar este registro?\")'>Eliminar</a>";

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
