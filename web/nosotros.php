<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nosotros | TiendaPlus</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <script src="https://kit.fontawesome.com/4b6e2a2f87.js" crossorigin="anonymous"></script>
  <style>
    :root {
      --rosa: #ff69b4;
      --gris: #444;
      --fondo: #f8f9fa;
    }
    body {
      font-family: 'Poppins', sans-serif;
      background-color: var(--fondo);
      color: var(--gris);
    }
    header {
      background-color: var(--rosa);
      color: white;
      padding: 15px 0;
      text-align: center;
      font-weight: 600;
      font-size: 1.4em;
    }
    .hero {
      background: linear-gradient(rgba(255,105,180,0.7), rgba(255,105,180,0.7)), url('img/banner-tienda.jpg') center/cover;
      color: white;
      text-align: center;
      padding: 100px 20px;
    }
    .hero h1 {
      font-size: 2.5em;
      font-weight: 700;
    }
    .section {
      padding: 60px 20px;
    }
    .icon-box {
      text-align: center;
      padding: 30px;
      background: white;
      border-radius: 15px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.05);
      transition: transform 0.3s ease;
    }
    .icon-box:hover {
      transform: translateY(-5px);
    }
    .icon-box i {
      font-size: 40px;
      color: var(--rosa);
      margin-bottom: 15px;
    }
    footer {
      background-color: var(--rosa);
      color: white;
      text-align: center;
      padding: 15px;
      margin-top: 50px;
    }
    .btn-back {
      background-color: var(--rosa);
      color: white;
      border-radius: 8px;
      padding: 10px 20px;
      text-decoration: none;
      transition: background-color 0.3s;
    }
    .btn-back:hover {
      background-color: #f8f9fa;
      color: white;
    }
  </style>
</head>
<body>

  <!-- Header -->
  <header>
    <i class="fa-solid fa-store"></i> TiendaPlus
  </header>

  <!-- Hero Section -->
  <section class="hero">
    <h1>Conoce más sobre TiendaPlus</h1>
    <p>Tu tienda moderna, confiable y creada para ti 💕</p>
  </section>

  <!-- Nuestra historia -->
  <section class="section container">
    <div class="row align-items-center">
      <div class="col-md-6">
        <img src="img/nosotros.jpg" alt="Nosotros" class="img-fluid rounded shadow">
      </div>
      <div class="col-md-6">
        <h2 class="fw-bold mb-3" style="color: var(--rosa);">Nuestra Historia</h2>
        <p>
          En <strong>TiendaPlus</strong> comenzamos con una idea simple: ofrecer productos de moda y estilo
          de la mejor calidad a precios accesibles. Desde nuestros inicios, nos hemos enfocado en brindar
          una experiencia de compra única, moderna y cercana a nuestros clientes.
        </p>
        <p>
          Hoy somos más que una tienda: somos una comunidad que celebra la autenticidad, el diseño y la pasión por la moda.
        </p>
      </div>
    </div>
  </section>

  <!-- Misión, Visión, Valores -->
  <section class="section text-center bg-light">
    <div class="container">
      <div class="row g-4">
        <div class="col-md-4">
          <div class="icon-box">
            <i class="fa-solid fa-bullseye"></i>
            <h4>Misión</h4>
            <p>Brindar a nuestros clientes productos de alta calidad, con una atención cercana y personalizada, impulsando la confianza y el estilo propio.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="icon-box">
            <i class="fa-solid fa-eye"></i>
            <h4>Visión</h4>
            <p>Ser la tienda en línea líder en moda femenina en Latinoamérica, reconocida por su innovación, autenticidad y compromiso con la satisfacción del cliente.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="icon-box">
            <i class="fa-solid fa-heart"></i>
            <h4>Valores</h4>
            <p>Pasión, confianza, respeto y compromiso. Cada producto y cada cliente son parte fundamental de nuestra historia.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Contacto / Botón -->
  <section class="section text-center">
    <h3 class="mb-4">¿Tienes preguntas o sugerencias?</h3>
    <p>Nos encanta escucharte 💌</p>
    <a href="contacto.php" class="btn-back"><i class="fa-solid fa-envelope"></i> Contáctanos</a>
  </section>

  <!-- Footer -->
  <footer>
    © 2025 TiendaPlus — Todos los derechos reservados 🌸
  </footer>

</body>
</html>
