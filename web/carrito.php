<?php
session_start();
include_once("../backend/conexion.php");

// Si el usuario ha iniciado sesión, guardamos su ID
$usuario_id = $_SESSION['usuario_id'] ?? null;

// --- Inicializar carrito de sesión ---
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// --- Sincronizar carrito con base de datos (solo si el usuario inició sesión) ---
if ($usuario_id) {
    // 🧩 Cargar productos guardados en BD al iniciar
    $sql = $conn->prepare("SELECT c.producto_id, c.cantidad, p.nombre, p.precio, p.imagen
                           FROM carrito c 
                           INNER JOIN productos p ON c.producto_id = p.id 
                           WHERE c.usuario_id = ?");
    $sql->bind_param("i", $usuario_id);
    $sql->execute();
    $resultado = $sql->get_result();

    $carritoBD = [];
    while ($fila = $resultado->fetch_assoc()) {
        $carritoBD[$fila['producto_id']] = [
            'nombre' => $fila['nombre'],
            'precio' => $fila['precio'],
            'imagen' => $fila['imagen'],
            'cantidad' => $fila['cantidad']
        ];
    }

    // Fusionar carrito de sesión con carrito BD
    $_SESSION['carrito'] = array_replace($_SESSION['carrito'], $carritoBD);
}

// --- ACTUALIZAR cantidad ---
if (isset($_POST['accion']) && $_POST['accion'] === 'actualizar') {
    $id = intval($_POST['id']);
    $cantidad = intval($_POST['cantidad']);
    if (isset($_SESSION['carrito'][$id])) {
        $_SESSION['carrito'][$id]['cantidad'] = $cantidad;

        // 🧩 Actualizar también en BD si está logueado
        if ($usuario_id) {
            $sql = $conn->prepare("UPDATE carrito SET cantidad = ? WHERE usuario_id = ? AND producto_id = ?");
            $sql->bind_param("iii", $cantidad, $usuario_id, $id);
            $sql->execute();
        }
    }
    exit;
}

// --- ELIMINAR producto ---
if (isset($_POST['accion']) && $_POST['accion'] === 'eliminar') {
    $id = intval($_POST['id']);
    unset($_SESSION['carrito'][$id]);

    // 🧩 Eliminar también de la base de datos
    if ($usuario_id) {
        $sql = $conn->prepare("DELETE FROM carrito WHERE usuario_id = ? AND producto_id = ?");
        $sql->bind_param("ii", $usuario_id, $id);
        $sql->execute();
    }
    exit;
}

// --- MENSAJE flotante ---
$mensaje = '';
if (isset($_GET['msg'])) {
    switch ($_GET['msg']) {
        case 'added': $mensaje = "🛍 Producto agregado al carrito"; break;
        case 'updated': $mensaje = "✅ Cantidad actualizada"; break;
        case 'deleted': $mensaje = "🗑 Producto eliminado"; break;
    }
}

$total = 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>🛍 Carrito de compra | TiendaPlus</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
/* 🎨 --- Todo tu mismo estilo --- */
body {
    font-family: 'Poppins', sans-serif;
    background-color: #fff;
    margin: 0;
    padding: 0;
}
h2 {
    text-align: center;
    color: #ff4ba8;
    margin-top: 40px;
}
table {
    width: 90%;
    margin: 30px auto;
    border-collapse: collapse;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
th, td {
    padding: 15px;
    border-bottom: 1px solid #f0f0f0;
    text-align: center;
}
th {
    background-color: #ff4ba8;
    color: #fff;
}
td img {
    width: 70px;
    border-radius: 10px;
    transition: transform 0.3s ease;
}
td img:hover { transform: scale(1.05); }
button {
    background-color: #ff4ba8;
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: 6px 12px;
    cursor: pointer;
    transition: 0.3s ease;
}
button:hover {
    background-color: #e63c93;
    transform: scale(1.05);
}
input[type="number"] {
    border: 1px solid #ccc;
    border-radius: 8px;
    padding: 5px;
    width: 60px;
    text-align: center;
}
.total {
    text-align: right;
    width: 90%;
    margin: 20px auto;
    font-weight: bold;
    font-size: 1.2rem;
    color: #333;
}
.botones {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-bottom: 50px;
}
.btn {
    text-decoration: none;
    background-color: #ff4ba8;
    color: #fff;
    padding: 10px 20px;
    border-radius: 10px;
    transition: 0.3s ease;
}
.btn:hover {
    background-color: #e63c93;
    transform: scale(1.05);
}
.fade { animation: fadeIn 0.5s ease forwards; }
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(15px); }
    to { opacity: 1; transform: translateY(0); }
}
.fade-out { animation: fadeOut 0.4s ease forwards; }
@keyframes fadeOut {
    from { opacity: 1; transform: scale(1); }
    to { opacity: 0; transform: scale(0.9); height: 0; }
}
/* 🌈 Mensaje flotante */
#mensaje {
    position: fixed;
    top: 20px;
    right: 20px;
    background: #ff4ba8;
    color: #fff;
    padding: 14px 22px;
    border-radius: 10px;
    font-weight: 600;
    box-shadow: 0 4px 15px rgba(0,0,0,0.25);
    opacity: 0;
    pointer-events: none;
    z-index: 1000;
}
#mensaje.show { animation: showMsg 0.6s ease forwards; }
@keyframes showMsg {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
</head>
<body>

<h2 class="fade">🛍 Carrito de compra</h2>

<div id="mensaje"><?= htmlspecialchars($mensaje) ?></div>

<table class="fade" id="tablaCarrito">
    <tr>
        <th>Producto</th>
        <th>Precio</th>
        <th>Cantidad</th>
        <th>Acción</th>
    </tr>

    <?php if (empty($_SESSION['carrito'])): ?>
        <tr><td colspan="4">Tu carrito está vacío 🛒</td></tr>
    <?php else: ?>
        <?php foreach ($_SESSION['carrito'] as $id => $item): 
            if (!isset($item['nombre']) || !isset($item['precio'])) continue;
            $subtotal = $item['precio'] * $item['cantidad'];
            $total += $subtotal;
        ?>
        <tr id="fila<?= $id ?>" class="fade">
            <td>
                <?php if (!empty($item['imagen'])): ?>
                    <img src="../uploads/<?= htmlspecialchars($item['imagen']) ?>" alt="<?= htmlspecialchars($item['nombre']) ?>"><br>
                <?php endif; ?>
                <?= htmlspecialchars($item['nombre']) ?>
            </td>
            <td>$<?= number_format($item['precio'], 0, ',', '.') ?></td>
            <td>
                <input type="number" id="cantidad<?= $id ?>" value="<?= $item['cantidad'] ?>" min="1"
                       onchange="actualizarCantidad(<?= $id ?>)">
            </td>
            <td><button onclick="eliminarProducto(<?= $id ?>)">🗑</button></td>
        </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</table>

<p class="total fade">Total: $<?= number_format($total, 0, ',', '.') ?></p>

<div class="botones fade">
    <a href="catalogo.php" class="btn">🛍 Seguir comprando</a>
    <?php if ($total > 0): ?>
        <a href="checkout.php" class="btn">💳 Ir a pagar</a>
    <?php endif; ?>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const msg = document.getElementById("mensaje");
    if (msg.textContent.trim() !== "") {
        msg.classList.add("show");
        setTimeout(() => {
            msg.style.transition = "opacity 1s ease";
            msg.style.opacity = "0";
        }, 3000);
    }
});

function actualizarCantidad(id) {
    const cantidad = document.getElementById("cantidad" + id).value;
    fetch("carrito.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "accion=actualizar&id=" + id + "&cantidad=" + cantidad
    }).then(() => {
        window.location.href = "carrito.php?msg=updated";
    });
}

function eliminarProducto(id) {
    if (!confirm("¿Quieres eliminar este producto del carrito?")) return;
    const fila = document.getElementById("fila" + id);
    fila.classList.add("fade-out");
    setTimeout(() => {
        fetch("carrito.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "accion=eliminar&id=" + id
        }).then(() => {
            window.location.href = "carrito.php?msg=deleted";
        });
    }, 400);
}
</script>
</body>
</html>
