<?php
session_start();
require_once('../backend/conexion.php'); // Asegúrate de ajustar la ruta si es diferente

// Inicializar carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// --- Agregar producto ---
if (isset($_POST['agregar'])) {
    $id = $_POST['id'];

    // ✅ Validar stock desde la base de datos
    $stmt = $conn->prepare("SELECT nombre, precio, stock FROM productos WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $producto = $res->fetch_assoc();
    $stmt->close();

    if (!$producto) {
        header("Location: carrito.php?error=producto_no_existe");
        exit;
    }

    if ($producto['stock'] <= 0) {
        header("Location: carrito.php?error=sin_stock");
        exit;
    }

    $nombre = $producto['nombre'];
    $precio = $producto['precio'];
    $cantidad = 1;

    if (isset($_SESSION['carrito'][$id])) {
        $nuevaCantidad = $_SESSION['carrito'][$id]['cantidad'] + 1;
        if ($nuevaCantidad > $producto['stock']) {
            header("Location: carrito.php?error=stock_insuficiente");
            exit;
        }
        $_SESSION['carrito'][$id]['cantidad'] = $nuevaCantidad;
    } else {
        $_SESSION['carrito'][$id] = [
            'id' => $id,
            'nombre' => $nombre,
            'precio' => $precio,
            'cantidad' => $cantidad
        ];
    }
    header("Location: carrito.php?added=1");
    exit;
}

// --- Eliminar producto ---
if (isset($_POST['eliminar'])) {
    $id = $_POST['id'];
    unset($_SESSION['carrito'][$id]);
    header("Location: carrito.php?deleted=1");
    exit;
}

// --- Actualizar cantidad ---
if (isset($_POST['actualizar'])) {
    $id = $_POST['id'];
    $cantidad = max(1, intval($_POST['cantidad']));

    // ✅ Validar stock al actualizar
    $stmt = $conn->prepare("SELECT stock FROM productos WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $producto = $res->fetch_assoc();
    $stmt->close();

    if ($producto && $cantidad > $producto['stock']) {
        header("Location: carrito.php?error=stock_insuficiente");
        exit;
    }

    $_SESSION['carrito'][$id]['cantidad'] = $cantidad;
    header("Location: carrito.php?updated=1");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Carrito - Tienda Plus</title>
<style>
body {
    font-family: 'Poppins', sans-serif;
    background: #fafafa;
    margin: 0; padding: 0;
}
h1 {
    text-align: center;
    color: #ff69b4;
    margin-top: 30px;
}
.carrito {
    max-width: 900px;
    margin: 40px auto;
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}
.item-card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px;
    border-bottom: 1px solid #eee;
    transition: all 0.3s ease;
}
.item-card:hover { background: #fff6fb; }
button {
    background: #ff69b4;
    border: none;
    color: white;
    padding: 6px 12px;
    border-radius: 6px;
    cursor: pointer;
    transition: background 0.3s;
}
button:hover { background: #ff8fc6; }
.total {
    text-align: right;
    font-size: 18px;
    font-weight: bold;
    margin-top: 20px;
}
.vacio {
    text-align: center;
    font-size: 18px;
    color: #777;
    margin-top: 50px;
}
#alert-message {
    position: fixed;
    top: 20px;
    right: 20px;
    background: #28a745;
    color: white;
    padding: 14px 22px;
    border-radius: 10px;
    display: none;
    font-weight: 600;
    font-size: 15px;
    z-index: 1000;
    box-shadow: 0 4px 12px rgba(0,0,0,0.25);
    animation: floatIn 0.6s ease forwards;
}
@keyframes floatIn {
    from { opacity: 0; transform: translateY(-15px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Confirmación de eliminación */
#confirm-overlay {
    position: fixed; inset:0; background: rgba(0,0,0,0.5);
    display: none; justify-content:center; align-items:center; z-index:2000;
}
#confirm-overlay .confirm-box {
    background:#fff; padding:25px 35px; border-radius:10px;
    text-align:center; box-shadow:0 4px 15px rgba(0,0,0,0.3); max-width:300px;
}
#confirm-overlay .confirm-box h3 { margin-bottom:20px; color:#333; }
#confirm-overlay .confirm-box button {
    margin:0 10px; padding:8px 16px; border-radius:6px; cursor:pointer; border:none; font-weight:600;
}
.btn-si { background:#dc3545; color:#fff; }
.btn-no { background:#6c757d; color:#fff; }

/* ✅ Responsive mejorado */
@media (max-width: 600px) {
    .carrito {
        width: 95%;
        padding: 15px;
    }
    .item-card {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    .item-card div {
        width: 100%;
        text-align: left;
    }
    .total {
        text-align: center;
        font-size: 16px;
    }
    button {
        width: 100%;
        margin-top: 5px;
    }
}
</style>
</head>
<body>

<h1>🛍️ Tu Carrito</h1>
<div id="alert-message"></div>

<div class="carrito">
<?php if (empty($_SESSION['carrito'])): ?>
    <p class="vacio">Tu carrito está vacío.</p>
    <div style="text-align:center;">
        <a href="catalogo.php"><button>🛒 Ver productos</button></a>
    </div>
<?php else: ?>
    <?php
    $total = 0;
    foreach ($_SESSION['carrito'] as $item):
        $subtotal = $item['precio'] * $item['cantidad'];
        $total += $subtotal;
    ?>
    <div class="item-card">
        <div><strong><?= htmlspecialchars($item['nombre']) ?></strong></div>
        <div class="precio" data-precio="<?= $item['precio'] ?>">$<?= number_format($item['precio'],0,',','.') ?></div>
        <div>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="id" value="<?= $item['id'] ?>">
                <input type="number" class="cantidad" name="cantidad" value="<?= $item['cantidad'] ?>" min="1" style="width:60px;">
                <button type="submit" name="actualizar">↻</button>
            </form>
        </div>
        <div class="subtotal">Subtotal: $<?= number_format($subtotal,0,',','.') ?></div>
        <div>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="id" value="<?= $item['id'] ?>">
                <button type="submit" name="eliminar" class="btn-eliminar" data-id="<?= $item['id'] ?>">🗑️</button>
            </form>
        </div>
    </div>
    <?php endforeach; ?>

    <div class="total">Total: $<?= number_format($total,0,',','.') ?></div>
    <div style="text-align:right; margin-top:20px;">
        <a href="checkout.php"><button>Continuar al pago 💳</button></a>
    </div>
<?php endif; ?>
</div>

<!-- Confirmación de eliminación -->
<div id="confirm-overlay">
    <div class="confirm-box">
        <h3>¿Eliminar este producto?</h3>
        <button class="btn-si">Sí</button>
        <button class="btn-no">No</button>
    </div>
</div>

<script>
// ✅ Manejador de alertas y mensajes
document.addEventListener("DOMContentLoaded", () => {
    const alerta = document.getElementById("alert-message");
    const overlay = document.getElementById("confirm-overlay");
    const btnSi = overlay?.querySelector(".btn-si");
    const btnNo = overlay?.querySelector(".btn-no");

    function mostrar(msg, tipo="success") {
        alerta.textContent = msg;
        alerta.style.backgroundColor = tipo==="success"?"#28a745":"#dc3545";
        alerta.style.display = "block";
        alerta.style.opacity = 1;
        setTimeout(()=>{
            alerta.style.transition="opacity 1s ease";
            alerta.style.opacity=0;
            setTimeout(()=>{ alerta.style.display="none"; alerta.style.opacity=1; },1000);
        },4500);
    }

    const params = new URLSearchParams(window.location.search);
    if(params.has("added")) mostrar("🛍️ Producto agregado al carrito");
    if(params.has("updated")) mostrar("✅ Cantidad actualizada");
    if(params.has("deleted")) mostrar("🗑️ Producto eliminado correctamente","error");
    if(params.has("error")) {
        const err = params.get("error");
        if(err==="sin_stock") mostrar("🚫 Producto sin stock","error");
        if(err==="stock_insuficiente") mostrar("⚠️ Stock insuficiente","error");
        if(err==="producto_no_existe") mostrar("❌ Producto no encontrado","error");
    }

    // Confirmación de eliminación
    document.querySelectorAll(".btn-eliminar").forEach(btn=>{
        btn.addEventListener("click", (e)=>{
            e.preventDefault();
            const id = btn.dataset.id;
            overlay.style.display="flex";

            btnSi.onclick = ()=>{
                const form=document.createElement("form");
                form.method="POST";
                form.innerHTML=`<input type="hidden" name="id" value="${id}">
                                <input type="hidden" name="eliminar" value="1">`;
                document.body.appendChild(form);
                form.submit();
            };
            btnNo.onclick = ()=>{ overlay.style.display="none"; };
        });
    });
});
</script>

</body>
</html>

</html>
