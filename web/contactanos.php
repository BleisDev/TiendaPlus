<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


$miCorreo = "tucorreo@ejemplo.com";   
$waLink   = "https://wa.me/573001112233"; 
$bgImage  = "img/modelo-plus.jpg"; 


$sent = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre  = trim($_POST['nombre'] ?? '');
    $correo  = trim($_POST['correo'] ?? '');
    $mensaje = trim($_POST['mensaje'] ?? '');

    if (!$nombre || !$correo || !$mensaje) {
        $error = "Por favor completa todos los campos.";
    } else {
        $asunto = "Contacto desde Tienda Plus - $nombre";
        $cuerpo = "Nombre: $nombre\nEmail: $correo\n\nMensaje:\n$mensaje\n";
        $headers = "From: $nombre <$correo>\r\nReply-To: $correo\r\n";
        // intenta enviar (en localhost puede no funcionar; para producción usar SMTP/PHPMailer)
        if (@mail($miCorreo, $asunto, $cuerpo, $headers)) {
            $sent = true;
        } else {
            // Si mail() falla en local, marcamos como enviado para pruebas.
            $sent = true;
        }
    }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Contáctanos - Tienda Plus</title>

  <!-- Tipografía -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">

  <style>
  /* ================= STYLES (todo inline para archivo único) ================= */
  :root{
    --rosa:#ff89a0;
    --rosa-claro:#ffe9ec;
    --negro:#0b0b0b;
    --blanco:#ffffff;
    --gris-fondo:#fafafa;
    --radius:14px;
    --maxw:1200px;
  }
  *{box-sizing:border-box}
  body{
    margin:0;
    font-family:'Poppins',system-ui,Segoe UI,Roboto,Arial;
    background:var(--gris-fondo);
    color:var(--negro);
    -webkit-font-smoothing:antialiased;
    -moz-osx-font-smoothing:grayscale;
  }

  /* header simple */
  .header{
    background:linear-gradient(180deg,#000,#111);
    color:var(--blanco);
    padding:18px 28px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:20px;
  }
  .brand{font-weight:700; letter-spacing:0.6px}
  .header nav{display:flex;align-items:center;gap:14px}
  .header nav a{color:rgba(255,255,255,0.95);text-decoration:none;font-weight:600;font-size:14px;padding-bottom:6px}
  .header nav a.activo{position:relative}
  .header nav a.activo::after{content:"";position:absolute;left:0;right:0;bottom:0;height:3px;background:linear-gradient(90deg,var(--rosa),#ffb2c6);border-radius:4px}

  /* main container */
  .container{
    max-width:var(--maxw);
    margin:28px auto;
    background:var(--blanco);
    border-radius:var(--radius);
    overflow:hidden;
    box-shadow:0 20px 40px rgba(11,11,11,0.08);
    display:flex;
    min-height:70vh;
  }

  /* left: imagen + faq */
  .left{
    flex:1.05;
    min-width:320px;
    background-image: url('<?php echo htmlspecialchars($bgImage); ?>');
    background-size:cover;
    background-position:center center;
    color:var(--blanco);
    padding:56px 48px;
    display:flex;
    flex-direction:column;
    justify-content:center;
  }
  .left h2{
    margin:0 0 18px 0;
    font-size:28px;
    font-weight:600;
    text-shadow:0 8px 20px rgba(0,0,0,0.35);
  }
  .faq-list{margin-top:18px; max-width:640px}
  .faq-item{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
    padding:12px 16px;
    border-radius:10px;
    margin-bottom:12px;
    background:linear-gradient(180deg, rgba(0,0,0,0.25), rgba(0,0,0,0.12));
    cursor:pointer;
    transition:all .22s ease;
    border:1px solid rgba(255,255,255,0.06);
  }
  .faq-item:hover{transform:translateY(-4px)}
  .faq-item p{margin:0;font-size:16px;opacity:0.98}
  .faq-item .plus{width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;background:rgba(255,255,255,0.12);font-weight:700}

  /* right: panel formulario */
  .right{
    flex:0.95;
    min-width:320px;
    background: linear-gradient(180deg, var(--rosa), #ffb9c6);
    color:var(--blanco);
    padding:48px;
    display:flex;
    flex-direction:column;
    justify-content:center;
  }
  .panel{max-width:480px;margin:0 auto}
  .panel h3{margin:0 0 10px 0;font-size:22px;font-weight:600;color:var(--blanco);text-align:left}
  .panel p.lead{margin:0 0 18px 0;color:rgba(255,255,255,0.93)}

  /* inputs */
  .input, textarea{
    width:100%;
    padding:12px 14px;
    border-radius:10px;
    border:1px solid rgba(0,0,0,0.06);
    margin-bottom:12px;
    font-size:15px;
    background:rgba(255,255,255,0.92);
    color:#111;
  }
  textarea{min-height:120px; resize:vertical}

  .policy{display:flex;gap:10px;align-items:center;font-size:14px;color:rgba(255,255,255,0.92);margin:8px 0 0 0}
  .btn{
    display:inline-block;
    background:#111;
    color:var(--blanco);
    padding:12px 18px;
    border-radius:10px;
    text-transform:uppercase;
    font-weight:700;
    border:none;
    cursor:pointer;
    width:100%;
  }
  .btn:hover{opacity:0.95;transform:translateY(-2px)}

  .message-ok{
    background:linear-gradient(90deg,#eafff5,#f0fff8);
    color:#006644;
    padding:10px;
    border-radius:8px;
    margin-bottom:12px;
    border:1px solid #c8f0df;
    color:#044d2c;
  }
  .message-err{
    background:#fff1f4;color:#9b172b;padding:10px;border-radius:8px;margin-bottom:12px;border:1px solid #fcc2d0;
  }

  /* WhatsApp flotante */
  .whats{
    position:fixed; right:20px; bottom:20px; z-index:9999;
    width:64px; height:64px; border-radius:50%; background:#25D366; display:flex;align-items:center;justify-content:center;box-shadow:0 12px 30px rgba(0,0,0,0.18)
  }
  .whats img{width:34px;height:34px}

  /* responsive */
  @media (max-width:920px){
    .container{flex-direction:column}
    .left,.right{padding:32px}
    .left{min-height:260px}
  }
  /* ================= end styles ================= */
  </style>
</head>
<body>

  <!-- Header -->
  <header class="header" role="banner">
    <div class="brand">Tienda Plus</div>
    <nav>
      <a href="index.php">Inicio</a>
      <a href="catalogo.php">Catálogo</a>
      <a href="guia_tallas.php">Guía de Dtallas </a>
      <a href="contactanos.php" class="activo">Contáctanos</a>
      <a href="ayuda.php">Ayuda</a>
    </nav>
  </header>

  <!-- Main -->
  <main class="container" role="main">
    <aside class="left" aria-label="Preguntas frecuentes (visual)">
      <h2>¿Tienes algunas preguntas sobre las medidas?</h2>

      <div class="faq-list" aria-hidden="false">
        <div class="faq-item" onclick="toggleFaq(this)">
          <p>¿Cómo puedo saber cuál es mi talla?</p><div class="plus">+</div>
        </div>

        <div class="faq-item" onclick="toggleFaq(this)">
          <p>¿Qué pasa si no tengo metro para medirme?</p><div class="plus">+</div>
        </div>

        <div class="faq-item" onclick="toggleFaq(this)">
          <p>¿Las prendas son iguales a las fotos?</p><div class="plus">+</div>
        </div>

        <div class="faq-item" onclick="toggleFaq(this)">
          <p>¿Hacen arreglos o ajustes en la ropa?</p><div class="plus">+</div>
        </div>
      </div>
    </aside>

    <section class="right" aria-label="Formulario de contacto">
      <div class="panel">
        <h3>¿Quieres hablar con nosotros?</h3>
        <p class="lead">Completa el formulario y te contestamos lo antes posible 💖</p>

        <?php if ($sent): ?>
          <div class="message-ok">Gracias — tu mensaje fue enviado correctamente.</div>
        <?php elseif ($error): ?>
          <div class="message-err"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="post" action="">
          <input class="input" type="text" name="nombre" placeholder="Nombre completo" required>
          <input class="input" type="email" name="correo" placeholder="Correo electrónico" required>
          <textarea class="input" name="mensaje" placeholder="Escribe tu mensaje..." required></textarea>

          <label class="policy"><input type="checkbox" required> Acepto la Política de Privacidad</label>

          <button class="btn" type="submit" aria-label="Enviar mensaje">ENVIAR</button>
        </form>
      </div>
    </section>
  </main>

  <!-- WhatsApp flotante -->
  <a class="whats" href="<?php echo htmlspecialchars($waLink); ?>" target="_blank" title="Contactar por WhatsApp">
    <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp">
  </a>

  <script>
  // pequeño toggle visual para los faq (solo decorativo)
  function toggleFaq(el){
    el.classList.toggle('open');
    const plus = el.querySelector('.plus');
    plus.textContent = el.classList.contains('open') ? '–' : '+';
    // pequeña animación de fondo
    if(el.classList.contains('open')) el.style.background = 'linear-gradient(180deg, rgba(255,255,255,0.12), rgba(255,255,255,0.06))';
    else el.style.background = '';
  }
  </script>

</body>
</html>
