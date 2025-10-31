<?php
// ayuda.php - Tienda Plus (archivo único con CSS y JS embebidos)
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Ayuda - Tienda Plus</title>

  <!-- Fuente -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">

  <style>
  :root{
    --rosa:#ff69aa;
    --rosa-claro:#ffe9ec;
    --negro:#0b0b0b;
    --blanco:#fff;
    --maxw:1100px;
    --radius:12px;
  }
  *{box-sizing:border-box}
  body{margin:0;font-family:'Poppins',system-ui,Segoe UI,Roboto,Arial;background:#faf7f8;color:var(--negro)}
  .header{background:linear-gradient(180deg,#000,#111);color:var(--blanco);padding:18px 28px;display:flex;align-items:center;justify-content:space-between}
  .brand{font-weight:700}
  .header nav{display:flex;gap:14px}
  .header nav a{color:rgba(255,255,255,0.95);text-decoration:none;font-weight:600}
  .header nav a.activo::after{content:"";display:block;height:3px;background:linear-gradient(90deg,var(--rosa),#ffb2c6);margin-top:6px;border-radius:4px}

  .wrap{max-width:var(--maxw);margin:36px auto;padding:26px}
  .box{background:var(--white);border-radius:var(--radius);padding:28px;background:linear-gradient(180deg,#fff,#fffafc);box-shadow:0 10px 30px rgba(0,0,0,0.06)}
  .submenu{display:flex;gap:12px;justify-content:center;margin-bottom:20px}
  .submenu button{background:var(--rosa);color:#fff;border:none;padding:10px 18px;border-radius:8px;cursor:pointer;font-weight:700}
  .submenu button.active{background:#ffb6c1}

  .content{display:flex;gap:20px;flex-wrap:wrap;justify-content:center}
  .card{flex:0 1 480px;background:#fff;border-radius:10px;padding:18px;box-shadow:0 8px 20px rgba(0,0,0,0.05)}
  .card h3{margin:0 0 10px 0;color:var(--rosa)}
  .acc-item{border-top:1px solid #f1f1f1;padding:12px 0;cursor:pointer}
  .acc-item:first-of-type{border-top:0}
  .acc-item p{margin:8px 0 0 0;display:none;color:#444;line-height:1.5}
  .acc-item.open p{display:block}

  .formulario .input{width:100%;padding:12px;border-radius:8px;border:1px solid #eee;margin-bottom:10px}

  .btn{background:#111;color:#fff;padding:10px 16px;border-radius:8px;border:none;font-weight:700;cursor:pointer}
  .whats{position:fixed;right:20px;bottom:20px;width:64px;height:64px;border-radius:50%;display:flex;align-items:center;justify-content:center;background:#25D366;box-shadow:0 12px 30px rgba(0,0,0,0.18)}
  .whats img{width:34px;height:34px}

  @media (max-width:900px){.content{flex-direction:column;align-items:center}}
  </style>
</head>
<body>

<header class="header">
  <div class="brand">Tienda Plus</div>
  <nav>
    <a href="index.php">Inicio</a>
    <a href="catalogo.php">Catálogo</a>
    <a href="guiadetalle.php">Guía de Detalle</a>
    <a href="contactanos.php">Contáctanos</a>
    <a href="ayuda.php" class="activo">Ayuda</a>
  </nav>
</header>

<main class="wrap">
  <div class="box">
    <div class="submenu" role="tablist">
      <button id="btn-faq" class="active" onclick="show('faq')">Preguntas frecuentes</button>
      <button id="btn-form" onclick="show('form')">Formulario Cambios y Garantías</button>
    </div>

    <div id="faq" class="content">
      <div class="card" aria-live="polite">
        <h3>Preguntas frecuentes</h3>
        <div class="acc-item" onclick="toggleAcc(this)">
          <strong>¿Qué tallas manejan?</strong>
          <p>Ofrecemos tallas desde L hasta 5XL. Consulta la Guía de Detalle por producto para las medidas exactas.</p>
        </div>
        <div class="acc-item" onclick="toggleAcc(this)">
          <strong>¿Puedo cambiar o devolver?</strong>
          <p>Sí, dentro de los 5 días hábiles posteriores a la entrega. El producto debe estar en perfecto estado y conservar etiqueta.</p>
        </div>
        <div class="acc-item" onclick="toggleAcc(this)">
          <strong>¿Cuánto tarda el envío?</strong>
          <p>Los envíos tardan entre 2 y 5 días hábiles según la ciudad de destino. Recibirás notificación cuando salga tu pedido.</p>
        </div>
      </div>

      <div class="card">
        <h3>Contacto directo</h3>
        <p>Si no encuentras respuesta, usa el formulario de Cambios o contáctanos por WhatsApp.</p>
        <p style="margin-top:12px"><strong>Correo:</strong> soporte@tiendaplus.com</p>
      </div>
    </div>

    <div id="form" class="content" style="display:none">
      <div class="card">
        <h3>Formulario de Cambios y Garantías</h3>
        <form class="formulario" method="post" action="#">
          <input class="input" type="text" name="nombre" placeholder="Nombre completo" required>
          <input class="input" type="email" name="correo" placeholder="Correo electrónico" required>
          <input class="input" type="text" name="pedido" placeholder="Número de pedido" required>
          <textarea class="input" name="motivo" placeholder="Motivo del cambio o garantía" rows="4" required></textarea>
          <button class="btn" type="submit">Enviar solicitud</button>
        </form>
      </div>

      <div class="card">
        <h3>Política resumida</h3>
        <p>Revisamos cada solicitud en 48 horas hábiles. Para cambios, el producto debe enviarse en su empaque original y sin uso.</p>
      </div>
    </div>

  </div>
</main>

<a class="whats" href="https://wa.me/573001112233" target="_blank" title="WhatsApp">
  <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp">
</a>

<script>
function show(id){
  document.getElementById('faq').style.display = (id==='faq') ? 'flex' : 'none';
  document.getElementById('form').style.display = (id==='form') ? 'flex' : 'none';
  document.querySelectorAll('.submenu button').forEach(b=>b.classList.remove('active'));
  event.target.classList.add('active');
}
function toggleAcc(el){
  el.classList.toggle('open');
  const p = el.querySelector('p');
  if(el.classList.contains('open')) p.style.display = 'block';
  else p.style.display = 'none';
}
</script>

</body>
</html>

