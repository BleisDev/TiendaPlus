<?php
session_start();
include_once("conexion.php"); // Archivo conexion.php con $conn = new mysqli(...);

// Solo admin
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: ../web/login.php");
    exit;
}

// Variables
$tabla = $_GET['tabla'] ?? 'pedidos';
$accion = $_GET['accion'] ?? '';
$id = $_GET['id'] ?? '';

// --- Funciones ---
function valor_por_defecto($tabla, $columna){
    $defaults = [
        'productos' => ['imagen' => 'sin_imagen.png'],
        'resenas' => ['calificacion' => 1],
        'pedidos' => ['estado' => 'pendiente']
    ];
    return $defaults[$tabla][$columna] ?? '';
}

// --- Procesar formulario POST ---
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    // Actualizar estado de pedido
    if(isset($_POST['pedido_id'], $_POST['estado_pedido'])) {
        $pedido_id = intval($_POST['pedido_id']);
        $estado = $conn->real_escape_string($_POST['estado_pedido']);
        $conn->query("UPDATE pedidos SET estado='$estado' WHERE id='$pedido_id'");
        echo "Estado actualizado";
        exit;
    }

    // Insertar o actualizar cualquier tabla
    $id_post = $_POST['id'] ?? '';
    if($id_post != ''){
        // Actualizar
        $sets = [];
        foreach($_POST as $campo=>$valor){
            if($campo != 'id') $sets[] = "$campo='".$conn->real_escape_string($valor)."'";
        }
        $sql = "UPDATE $tabla SET ".implode(",", $sets)." WHERE id='$id_post'";
        $conn->query($sql);
    } else {
        // Insertar
        $campos_insert = [];
        $valores_insert = [];
        $cols = $conn->query("SHOW COLUMNS FROM $tabla");
        while($col = $cols->fetch_assoc()){
            $campo = $col['Field'];
            if($campo=='id') continue;
            $valor = $_POST[$campo] ?? '';
            if($valor==='') $valor = valor_por_defecto($tabla,$campo);
            if(strpos($col['Type'],'int')!==false && !is_numeric($valor)) $valor = 0;
            $campos_insert[] = $campo;
            $valores_insert[] = "'".$conn->real_escape_string($valor)."'";
        }
        $sql = "INSERT INTO $tabla (".implode(",",$campos_insert).") VALUES (".implode(",",$valores_insert).")";
        $conn->query($sql);
    }
    header("Location: panel.php?tabla=$tabla");
    exit;
}

// --- Eliminar ---
if($accion=='eliminar' && $id){
    $conn->query("DELETE FROM $tabla WHERE id='$id'");
    header("Location: panel.php?tabla=$tabla");
    exit;
}

// --- AJAX: detalle de pedido ---
if(isset($_GET['accion']) && $_GET['accion']=='ver_detalle' && isset($_GET['id_pedido'])){
    $id_pedido = intval($_GET['id_pedido']);
    $pedido = $conn->query("SELECT * FROM pedidos WHERE id=$id_pedido")->fetch_assoc();
    $detalles = $conn->query("SELECT dp.cantidad, dp.precio, p.nombre 
                              FROM detalle_pedido dp
                              JOIN productos p ON dp.producto_id=p.id
                              WHERE dp.pedido_id=$id_pedido");

    echo "<h5>Cliente: {$pedido['cliente']} ({$pedido['email']})</h5>";
    echo "<p>Fecha: {$pedido['fecha']} - Estado: {$pedido['estado']}</p>";
    echo "<table class='table table-bordered'><thead><tr><th>Producto</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th></tr></thead><tbody>";
    $total = 0;
    while($d = $detalles->fetch_assoc()){
        $subtotal = $d['cantidad']*$d['precio'];
        $total += $subtotal;
        echo "<tr>
                <td>{$d['nombre']}</td>
                <td>{$d['cantidad']}</td>
                <td>{$d['precio']}</td>
                <td>{$subtotal}</td>
              </tr>";
    }
    echo "</tbody></table>";
    echo "<h5>Total: $total</h5>";
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
body{font-family:Arial;background:#f8f9fa;}
.sidebar{height:100vh;background:#212529;color:white;padding:20px;min-width:220px;}
.sidebar a{display:block;color:#ccc;margin:10px 0;text-decoration:none;}
.sidebar a:hover{color:white;}
.content{padding:20px;width:100%;}
table{background:white;}
.btn-custom{border-radius:20px;}
.modal-body table{margin:0;}
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
        <a href="panel.php?tabla=detalle_pedido">Detalle Pedidos</a>
        <a href="panel.php?tabla=resenas">Reseñas</a>
        <a href="panel.php?tabla=carrito">Carrito</a>
        <hr>
        <a href="../web/index.php" class="btn btn-light btn-sm">Volver a Tienda</a>
    </div>
    <div class="content">
        <h2 class="mb-4">Tabla: <?= ucfirst($tabla) ?></h2>

<?php
// --- Formulario editar/nuevo ---
if($accion=='editar' || $accion=='nuevo'){
    $fila=[];
    if($accion=='editar'){
        $res=$conn->query("SELECT * FROM $tabla WHERE id='$id'");
        $fila=$res->fetch_assoc();
    }
    $cols=$conn->query("SHOW COLUMNS FROM $tabla");
    echo "<form method='POST' class='card p-4'>";
    if($accion=='editar') echo "<input type='hidden' name='id' value='$id'>";
    while($col=$cols->fetch_assoc()){
        $campo=$col['Field'];
        $valor=$fila[$campo] ?? valor_por_defecto($tabla,$campo);
        $readonly=($campo=='id')?'readonly':'';
        echo "<div class='mb-3'><label class='form-label'>".ucfirst($campo)."</label>
        <input type='text' name='$campo' class='form-control' value='$valor' $readonly></div>";
    }
    echo "<button class='btn btn-success'>Guardar</button>
          <a href='panel.php?tabla=$tabla' class='btn btn-secondary'>Cancelar</a></form>";
} else {
    // --- Tabla principal ---
    $res=$conn->query("SELECT * FROM $tabla");
    echo "<a href='panel.php?tabla=$tabla&accion=nuevo' class='btn btn-primary mb-3 btn-custom'>+ Crear nuevo</a>";
    if($res->num_rows>0){
        echo "<table class='table table-bordered table-striped table-responsive'><thead class='table-dark'><tr>";
        $fields=$res->fetch_fields();
        foreach($fields as $f) echo "<th>{$f->name}</th>";
        echo "<th>Acciones</th></tr></thead><tbody>";
        $res->data_seek(0);
        while($row=$res->fetch_assoc()){
            echo "<tr>";
            foreach($row as $val) echo "<td>$val</td>";
            echo "<td>
                <a href='panel.php?tabla=$tabla&accion=editar&id={$row['id']}' class='btn btn-warning btn-sm'>Editar</a>
                <a href='panel.php?tabla=$tabla&accion=eliminar&id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"¿Eliminar este registro?\")'>Eliminar</a>";
            if($tabla=='pedidos'){
                echo " <button class='btn btn-info btn-sm ver-detalle' data-id='{$row['id']}' data-bs-toggle='modal' data-bs-target='#modal-detalle'>Ver Pedido</button>";
            }
            echo "</td></tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p class='text-center'>No hay registros</p>";
    }
}
?>
    </div>
</div>

<!-- Modal Detalle Pedido -->
<div class="modal fade" id="modal-detalle" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title">Detalle del Pedido</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="contenido-detalle"></div>
      <div class="modal-footer">
        <select id="estado-pedido" class="form-select w-auto me-2">
          <option value="pendiente">Pendiente</option>
          <option value="procesando">Procesando</option>
          <option value="completado">Completado</option>
          <option value="cancelado">Cancelado</option>
        </select>
        <button id="btn-guardar-estado" class="btn btn-success">Actualizar Estado</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
let pedidoActual = null;

document.querySelectorAll('.ver-detalle').forEach(btn => {
    btn.addEventListener('click', function(){
        pedidoActual = this.getAttribute('data-id');
        fetch('panel.php?accion=ver_detalle&id_pedido=' + pedidoActual)
            .then(res => res.text())
            .then(data => {
                document.getElementById('contenido-detalle').innerHTML = data;
            });
    });
});

document.getElementById('btn-guardar-estado').addEventListener('click', function(){
    const nuevoEstado = document.getElementById('estado-pedido').value;
    if(!pedidoActual) return;
    fetch('panel.php', {
        method: 'POST',
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: 'pedido_id=' + pedidoActual + '&estado_pedido=' + nuevoEstado
    })
    .then(res => res.text())
    .then(data => {
        alert('Estado actualizado');
        location.reload();
    });
});
</script>
</body>
</html>

