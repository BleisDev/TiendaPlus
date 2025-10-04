# 🛍️ TiendaPlus

Proyecto web desarrollado como parte de la evidencia **GA7-220501096-AA2-EV02 - Módulos de software Web codificados y probados**.

## 🎯 Objetivo
Implementar una tienda virtual con funcionalidades de catálogo, carrito de compras, registro de usuarios, inicio de sesión, y panel administrativo, siguiendo buenas prácticas de usabilidad y accesibilidad.

## ⚙️ Tecnologías utilizadas
- HTML5, CSS3, JavaScript
- PHP 8.x
- MySQL / MariaDB
- XAMPP (servidor local)
- Git y GitHub (control de versiones)

## 📂 Estructura del proyecto
```bash
TiendaPlus/
│── admin/                 # Vistas y gestión del panel administrativo
│── backend/               # Lógica del servidor (guardar, eliminar, actualizar datos)
│── includes/              # Archivos comunes como cabeceras, pie de página
│── web/                   # Páginas principales de la tienda
│   ├── carrito.php        # Carrito de compras
│   ├── catalogo.php       # Catálogo de productos
│   ├── checkout.php       # Proceso de pago
│   ├── confirmacion.php   # Confirmación de compra
│   ├── factura.php        # Factura del pedido
│   ├── index.php          # Página de inicio
│   ├── login.php          # Inicio de sesión
│   ├── mis_pedidos.php    # Historial de pedidos del usuario
│   ├── perfil.php         # Perfil del usuario
│   ├── producto.php       # Detalle de un producto
│   ├── registro.php       # Registro de usuarios
│   └── estilos.css        # Estilos generales
│
│── TiendaPlus.sql         # Script de la base de datos
│── README.md              # Documentación del proyecto
