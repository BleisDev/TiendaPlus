# 🛍️ TiendaPlus

**TiendaPlus** es una tienda web desarrollada como proyecto académico.  
El objetivo es simular una tienda de moda en línea con catálogo, carrito de compras, sistema de usuarios y panel administrativo.

---

## 🚀 Características principales

- 👗 **Catálogo de productos** con imágenes y descripción.  
- 🛒 **Carrito de compras dinámico**.  
- 💳 **Checkout funcional** con registro de pedidos.  
- 👤 **Registro e inicio de sesión** de usuarios.  
- 🛠️ **Panel administrativo** con gestión de:
  - Productos
  - Categorías
  - Usuarios
  - Pedidos
  - Reseñas  

---

## 📂 Estructura del proyecto

TiendaPlus/
│── admin/ # Panel de administración
│ │── index.php # Dashboard admin
│ │── productos.php
│ │── usuarios.php
│ │── pedidos.php
│ │── resenas.php
│ └── ...
│
│── backend/ # Archivos PHP del backend
│ │── conexion.php
│ │── login.php
│ │── guardar_usuario.php
│ │── guardar_pedido.php
│ └── ...
│
│── includes/ # Conexión a la base de datos
│ └── db.php
│
│── web/ # Frontend de la tienda (usuarios)
│ │── index.php # Página de inicio
│ │── catalogo.php
│ │── producto.php
│ │── carrito.php
│ │── checkout.php
│ │── perfil.php
│ │── login.php
│ │── registro.php
│ │── estilos.css
│ └── img/ # Imágenes de ejemplo
│
│── TiendaPlus.sql # Script de la base de datos
│── README.md # Documentación del proyecto

---

## ⚙️ Instalación en XAMPP

1. Copia la carpeta `TiendaPlus` dentro de:
   - **Windows** → `C:\xampp\htdocs\`
   - **Mac** → `/Applications/XAMPP/htdocs/`

2. Importa la base de datos:
   - Abre `phpMyAdmin` → crea la base `tienda_plus`.
   - Importa el archivo **`TiendaPlus.sql`**.

3. Inicia Apache y MySQL en XAMPP.

4. Abre en tu navegador:
   - 🏠 [http://localhost/TiendaPlus/web/index.php](http://localhost/TiendaPlus/web/index.php) → Tienda para clientes.  
   - ⚙️ [http://localhost/TiendaPlus/admin/index.php](http://localhost/TiendaPlus/admin/index.php) → Panel administrativo.  

---

## 📸 Capturas de pantalla

### 🏠 Página de inicio
![Inicio](web/img/img.jpg)

### 👗 Catálogo de productos
![Catálogo](web/img/img2.jpg)

### 🛒 Carrito de compras
![Carrito](web/img/img3.jpg)

### ⚙️ Panel administrativo
![Admin](web/img/img4.jpg)

---

## 👩‍💻 Autor

Proyecto desarrollado por **Bleidis Dev**  
📧 Contacto: [GitHub](https://github.com/BleisDev)  

---

## 📝 Licencia

Este proyecto es de uso académico y puede ser utilizado como referencia.
