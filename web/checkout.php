<?php
session_start();
require_once('../backend/conexion.php');

// ---------------------------
// Comprobaciones iniciales
// ---------------------------
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    header("Location: carrito.php");
    exit;
}

// ---------------------------
// Obtener datos del usuario
// ---------------------------
$usuario_id = $_SESSION['usuario_id'];
$stmt = $conn->prepare("SELECT nombre, email FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$res = $stmt->get_result();
$usuario = $res->fetch_assoc() ?: ['nombre' => 'Usuario', 'email' => ''];
$stmt->close();

// ---------------------------
// Limpiar y normalizar carrito
// ---------------------------
// Queremos asegurarnos que cada item tenga: id, nombre, precio (float), cantidad (int), imagen (string)
$raw = $_SESSION['carrito'];
$carrito = [];
$total = 0.0;

foreach ($raw as $key => $item) {
    // Si el carrito está en formato numérico (array de items) o asociativo por id, manejamos ambos:
    // Si el elemento tiene 'id' lo tomamos, si no usamos la clave si es numérica.
    $id = null;
    if (isset($item['id'])) {
        $id = (int)$item['id'];
    } elseif (is_numeric($key)) {
        $id = (int)$key;
    }

    // Intentar rellenar desde BD si faltan datos y tenemos id
    if ($id && (!isset($item['nombre']) || !isset($item['precio']))) {
        $stmt = $conn->prepare("SELECT nombre, precio, imagen FROM productos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $r = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if ($r) {
            $item['nombre'] = $item['nombre'] ?? $r['nombre'];
            $item['precio'] = $item['precio'] ?? $r['precio'];
            $item['imagen'] = $item['imagen'] ?? $r['imagen'];
        }
    }

    // Validaciones por seguridad / evitar warnings
    $nombre = isset($item['nombre']) ? (string)$item['nombre'] : 'Producto sin nombre';
    $precio = isset($item['precio']) ? floatval($item['precio']) : 0.0;
    $cantidad = isset($item['cantidad']) ? max(1, intval($item['cantidad'])) : 1;
    $imagen = isset($item['imagen']) ? (string)$item['imagen'] : '';

    // Si no tenemos id, asignamos una clave única (para evitar sobrescrituras)
    if (!$id) {
        $id = uniqid('i_');
    }

    // Añadir al carrito normalizado (clave por id)
    $carrito[$id] = [
        'id' => $id,
        'nombre' => $nombre,
        'precio' => $precio,
        'cantidad' => $cantidad,
        'imagen' => $imagen
    ];

    $total += $precio * $cantidad;
}

// Reescribir la sesión con la versión limpia (evita volver a tener elementos corruptos)
$_SESSION['carrito'] = $carrito;
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Checkout - TiendaPlus</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
:root{
  --accent:#ff69b4;
  --muted:#f5f5f7;
  --card:#ffffff;
  --text:#333;
  --soft:#f1f1f3;
}
*{box-sizing:border-box}
body{
  font-family:'Poppins',sans-serif;
  background:var(--muted);
  color:var(--text);
  margin:0;
  padding:30px;
}
.page {
  max-width:1100px;
  margin:0 auto;
}
.header {
  text-align:center;
  margin-bottom:18px;
}
.header h1{color:var(--accent); margin:0; font-size:28px}
.grid {
  display:grid;
  grid-template-columns: 1fr 420px;
  gap:22px;
  align-items:start;
}
.card {
  background:var(--card);
  border-radius:12px;
  padding:18px;
  box-shadow:0 6px 22px rgba(0,0,0,0.06);
  border:1px solid var(--soft);
}
.section-title{color:var(--accent); margin:0 0 12px 0; font-size:16px}

/* Form layout (Contacto / Envío / Pago) */
.form-row{display:flex; gap:12px; margin-bottom:12px}
.form-row .field{flex:1}
.field label{display:block; font-size:13px; margin-bottom:6px; color:#555}
.field input, .field select, .field textarea{
  width:100%;
  padding:10px 12px;
  border:1px solid #e6e6e8;
  border-radius:8px;
  font-size:14px;
  background:#fff;
}

/* Payment boxes */
.payment-methods{display:flex; gap:10px; flex-wrap:wrap}
.pay-card{
  border:1px solid var(--soft);
  padding:10px 12px;
  border-radius:10px;
  display:flex; align-items:center; gap:10px;
  cursor:pointer;
  background:#fff;
}
.pay-card.selected{box-shadow:0 6px 18px rgba(0,0,0,0.06); border-color:var(--accent)}

/* Order summary (right column) */
.summary-list{list-style:none; padding:0; margin:0}
.summary-item{display:flex; gap:12px; align-items:center; padding:12px 0; border-bottom:1px dashed #eee}
.summary-item img{width:64px; height:64px; object-fit:cover; border-radius:8px; background:#fafafa}
.summary-qty{font-size:13px; color:#666; margin-top:6px}
.summary-line{display:flex; justify-content:space-between; align-items:center; margin-top:12px; font-weight:600}
.summary-footer{margin-top:14px; padding-top:12px; border-top:1px solid #f1f1f1}
.btn{
  display:inline-block;
  width:100%;
  padding:12px 16px;
  border-radius:10px;
  background:var(--accent);
  color:#fff;
  text-align:center;
  font-weight:600;
  border:0;
  cursor:pointer;
  transition:transform .12s ease, background .12s ease;
}
.btn:hover{transform:translateY(-2px); background:#ff85c0}
.small{font-size:13px;color:#666}

/* Responsive */
@media (max-width: 980px){
  .grid{grid-template-columns:1fr; padding:0 12px}
  .header h1{font-size:24px}
}
</style>
</head>
<body>
<div class="page">
  <div class="header">
    <h1>💳 Pantalla de pago</h1>
    <p class="small">Revisa tu información, elige envío y método de pago.</p>
  </div>

  <div class="grid">
    <!-- Left: Form (Contacto / Envío / Pago) -->
    <div class="card">
      <h3 class="section-title">Contacto</h3>
      <div style="margin-bottom:12px" class="small">Tu correo recibirá la confirmación del pedido</div>
      <div style="margin-bottom:16px">
        <strong><?= htmlspecialchars($usuario['nombre'] ?: 'Usuario') ?></strong><br>
        <span class="small"><?= htmlspecialchars($usuario['email'] ?: '') ?></span>
      </div>

      <h3 class="section-title">Entrega</h3>
      <form id="checkoutForm" method="post" action="../backend/finalizar_compra.php">
        <div class="form-row">
          <div class="field">
            <label for="pais">País</label>
            <select id="pais" name="pais">
              <option value="CO" selected>Colombia</option>
            </select>
          </div>
          <div class="field">
            <label for="region">Departamento / Estado</label>
            <select id="region" name="region">
              <option value="ANT">Antioquia</option>
              <option value="CO">Otro</option>
            </select>
          </div>
        </div>

        <div class="form-row">
          <div class="field">
            <label for="nombre">Nombre</label>
            <input id="nombre" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" />
          </div>
          <div class="field">
            <label for="telefono">Teléfono</label>
            <input id="telefono" name="telefono" placeholder="Ej: 3123456789" />
          </div>
        </div>

        <div class="form-row">
          <div class="field">
            <label for="direccion">Dirección</label>
            <input id="direccion" name="direccion" placeholder="Dirección de envío (calle, número, apto)" />
          </div>
          <div class="field">
            <label for="ciudad">Ciudad</label>
            <input id="ciudad" name="ciudad" placeholder="Ej: Medellín" />
          </div>
        </div>

        <h3 class="section-title" style="margin-top:18px">Método de envío</h3>
        <div style="margin-bottom:12px" class="small">Selecciona un método de envío</div>
        <div class="payment-methods" id="envios">
          <label class="pay-card" data-price="10000">
            <input type="radio" name="envio" value="medellin" checked style="display:none">
            <div>
              <strong>Medellín y Área metropolitana</strong><br>
              <span class="small">$ 10.000,00</span>
            </div>
          </label>

          <label class="pay-card" data-price="15000">
            <input type="radio" name="envio" value="otras_antioquia" style="display:none">
            <div>
              <strong>Otras ciudades de Antioquia</strong><br>
              <span class="small">$ 15.000,00</span>
            </div>
          </label>
        </div>

        <h3 class="section-title" style="margin-top:18px">Pago</h3>
        <div class="small" style="margin-bottom:10px">Todas las transacciones son seguras y están encriptadas.</div>

        <div class="payment-methods" id="pagos">
          <label class="pay-card selected" data-type="card">
            <input type="radio" name="pago" value="tarjeta" checked style="display:none">
            <div>Tarjeta de crédito</div>
          </label>

          <label class="pay-card" data-type="mercadopago">
            <input type="radio" name="pago" value="mercadopago" style="display:none">
            <div>Mercado Pago</div>
          </label>

          <label class="pay-card" data-type="pse">
            <input type="radio" name="pago" value="pse" style="display:none">
            <div>PSE / Transferencia</div>
          </label>
        </div>

        <!-- Campos simulados de tarjeta (solo visual) -->
        <div id="tarjetaFields" style="margin-top:14px">
          <div class="form-row">
            <div class="field"><label>Número de tarjeta</label><input name="card_number" placeholder="1234 1234 1234 1234"></div>
            <div class="field"><label>Nombre en la tarjeta</label><input name="card_name" placeholder="Nombre como en tarjeta"></div>
          </div>
          <div class="form-row">
            <div class="field"><label>Expiración (MM/AA)</label><input name="card_exp" placeholder="MM/AA"></div>
            <div class="field"><label>CVC</label><input name="card_cvc" placeholder="CVC"></div>
          </div>
        </div>

      </form>
    </div>

    <!-- Right: Resumen -->
    <aside class="card">
      <h3 class="section-title">Resumen del pedido</h3>

      <ul class="summary-list">
        <?php foreach ($_SESSION['carrito'] as $id => $item): 
            // variables seguras (ya normalizadas más arriba, pero duplicamos seguridad)
            $nombre = htmlspecialchars($item['nombre'] ?? 'Producto');
            $cantidad = intval($item['cantidad'] ?? 1);
            $precio = floatval($item['precio'] ?? 0);
            $imagen = !empty($item['imagen']) ? "../uploads/".htmlspecialchars($item['imagen']) : null;
        ?>
        <li class="summary-item">
          <?php if ($imagen): ?>
            <img src="<?= $imagen ?>" alt="<?= $nombre ?>">
          <?php else: ?>
            <img src="../assets/img/no-image.png" alt="Sin imagen">
          <?php endif; ?>
          <div style="flex:1">
            <div><strong><?= $nombre ?></strong></div>
            <div class="small">Cantidad: <?= $cantidad ?></div>
          </div>
          <div style="min-width:90px; text-align:right">
            <div style="font-weight:700">$<?= number_format($precio * $cantidad, 0, ',', '.') ?></div>
          </div>
        </li>
        <?php endforeach; ?>
      </ul>

      <div class="summary-footer">
        <div class="summary-line">
          <span>Subtotal</span>
          <span>$<?= number_format($total, 0, ',', '.') ?></span>
        </div>

        <!-- El envío se calcula en cliente (según opción seleccionada) y también server-side al finalizar -->
        <div class="summary-line" style="margin-top:8px">
          <span>Envío</span>
          <span id="envioPrecio">$10.000</span>
        </div>

        <div class="summary-line" style="margin-top:12px; font-size:18px;">
          <span>Total</span>
          <span id="granTotal" style="font-weight:800">$<?= number_format($total + 10000, 0, ',', '.') ?></span>
        </div>

        <div style="margin-top:14px">
          <button class="btn" id="btnPagar">Procesar pago</button>
        </div>

        <div style="margin-top:10px" class="small">
          Incluye impuestos y cargos (si aplica).
        </div>
      </div>
    </aside>
  </div>
</div>

<script>
// Interacciones visuales: seleccionar métodos y actualizar precio de envío
document.addEventListener('DOMContentLoaded', () => {
  // seleccionar envíos
  document.querySelectorAll('#envios .pay-card').forEach(card => {
    card.addEventListener('click', () => {
      document.querySelectorAll('#envios .pay-card').forEach(c=>c.classList.remove('selected'));
      card.classList.add('selected');
      // actualizar precio de envío
      const price = parseInt(card.getAttribute('data-price') || '0', 10);
      document.getElementById('envioPrecio').textContent = '$' + price.toLocaleString('de-DE');
      const subtotal = <?= json_encode(floatval($total)) ?>;
      document.getElementById('granTotal').textContent = '$' + (subtotal + price).toLocaleString('de-DE');
    });
  });

  // seleccionar método de pago (visual)
  document.querySelectorAll('#pagos .pay-card').forEach(card => {
    card.addEventListener('click', () => {
      document.querySelectorAll('#pagos .pay-card').forEach(c=>c.classList.remove('selected'));
      card.classList.add('selected');
      // mostrar/ocultar campos de tarjeta si es necesario
      const type = card.getAttribute('data-type');
      document.getElementById('tarjetaFields').style.display = (type === 'card') ? 'block' : 'none';
    });
  });

  // boton pagar: envia el formulario real (a tu backend)
  document.getElementById('btnPagar').addEventListener('click', () => {
    // copiar envío seleccionado al formulario
    const selectedEnvio = document.querySelector('#envios .pay-card.selected');
    const envioValue = selectedEnvio ? (selectedEnvio.querySelector('input') ? selectedEnvio.querySelector('input').value : '') : '';
    // inyectar hidden fields
    const f = document.getElementById('checkoutForm');
    let h = document.getElementById('envio_hidden');
    if (!h) {
      h = document.createElement('input');
      h.type = 'hidden';
      h.name = 'envio_seleccionado';
      h.id = 'envio_hidden';
      f.appendChild(h);
    }
    h.value = envioValue;

    // enviar (a tu backend finalizar_compra.php)
    f.submit();
  });
});
</script>
</body>
</html>
