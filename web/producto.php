<?php
session_start();
require_once('../backend/conexion.php');

$id = intval($_GET['id'] ?? 0);
if($id <= 0) die("⚠️ Producto no válido");

$stmt = $conn->prepare("SELECT id, nombre, precio, stock, imagen FROM productos WHERE id=?");
$stmt->bind_param("i",$id);
$stmt->execute();
$result = $stmt->get_result();
$producto = $result->fetch_assoc();
if(!$producto) die("⚠️ Producto no encontrado");

$usuario_id = $_SESSION['usuario_id'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($producto['nombre']) ?> - Tienda Plus</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background:#f8f9fa; font-family:Arial,sans-serif; }
.container { max-width:700px; margin:50px auto; background:#fff; padding:30px; border-radius:15px; }
.btn-pink { background: #f08db2; color: #fff; border:none; border-radius:30px; padding:10px 20px; }
.btn-pink:hover { background:#e76da0; }
#alert-message {
  position: fixed; top:20px; left:50%; transform:translateX(-50%);
  display:none; padding:15px 25px; border-radius:10px; color:#fff; font-weight:bold; z-index:1000;
}
</style>
</head>
<body>

<div class="container">
    <h2><?= htmlspecialchars($producto['nombre']) ?></h2>
    <img src="<?= htmlspecialchars($producto['imagen'] ?? 'https://via.placeholder.com/300') ?>" class="img-fluid mb-3">
    <p>Precio: $<?= number_format($producto['precio'],0,',','.') ?></p>
    <p>Stock disponible: <?= $producto['stock'] ?></p>

    <form id="agregar-form">
        <input type="hidden" name="id_producto" value="<?= $producto['id'] ?>">
        <label>Cantidad:</label>
        <input type="number" name="cantidad" value="1" min="1" max="<?= $producto['stock'] ?>" class="form-control" style="width:80px;">
        <button type="submit" class="btn btn-pink mt-3">Agregar al carrito</button>
    </form>
</div>

<div id="alert-message"></div>

<script>
const form = document.getElementById('agregar-form');
form.addEventListener('submit', function(e){
    e.preventDefault();
    const id_producto = form.id_producto.value;
    const cantidad = parseInt(form.cantidad.value);
    const stock = parseInt(form.cantidad.max);
    if(cantidad < 1 || cantidad > stock){
        mostrarMensaje("Cantidad inválida","error");
        return;
    }

    fetch('../backend/carrito_api.php', {
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:`accion=agregar&id_producto=${id_producto}&cantidad=${cantidad}`
    })
    .then(r=>r.json())
    .then(data=>{
        if(data.exito){
            mostrarMensaje("Producto agregado al carrito");
            const contador = document.getElementById('cart-count');
            if(contador) contador.textContent = data.count;
        } else mostrarMensaje(data.mensaje,'error');
    })
    .catch(()=> mostrarMensaje("Error al agregar al carrito",'error'));
});

function mostrarMensaje(msg,tipo="success"){
    const alertBox=document.getElementById('alert-message');
    alertBox.style.backgroundColor=tipo==="success"?"#28a745":"#dc3545";
    alertBox.textContent=msg;
    alertBox.style.opacity=0; alertBox.style.display='block';
    setTimeout(()=>alertBox.style.opacity=1,10);
    setTimeout(()=>{ alertBox.style.opacity=0; setTimeout(()=>alertBox.style.display='none',500); },2500);
}
</script>

</body>
</html>
