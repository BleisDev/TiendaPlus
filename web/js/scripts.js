document.addEventListener("DOMContentLoaded", () => {
    const alerta = document.getElementById("alert-message");
    const overlay = document.getElementById("confirm-overlay");
    const btnSi = overlay?.querySelector(".btn-si");
    const btnNo = overlay?.querySelector(".btn-no");

    // Mostrar alerta flotante
    function mostrar(msg, tipo = "success") {
        alerta.textContent = msg;
        alerta.style.backgroundColor = tipo === "success" ? "#28a745" : "#dc3545";
        alerta.style.display = "block";
        alerta.classList.add("fade");
        setTimeout(() => {
            alerta.style.display = "none";
            alerta.classList.remove("fade");
        }, 3500);
    }

    // Detectar acciones por URL
    const params = new URLSearchParams(window.location.search);
    if (params.has("added")) mostrar("🛍️ Producto agregado al carrito", "success");
    if (params.has("updated")) mostrar("✅ Cantidad actualizada", "success");
    if (params.has("deleted")) mostrar("🗑️ Producto eliminado correctamente", "error");

    // Confirmar eliminación con ventana personalizada
    document.querySelectorAll(".btn-eliminar").forEach(btn => {
        btn.addEventListener("click", (e) => {
            e.preventDefault();
            const id = btn.dataset.id;
            overlay.style.display = "flex";

            btnSi.onclick = () => {
                // Crear y enviar formulario
                const form = document.createElement("form");
                form.method = "POST";
                form.innerHTML = `<input type="hidden" name="id" value="${id}">
                                  <input type="hidden" name="eliminar" value="1">`;
                document.body.appendChild(form);
                form.submit();
            };

            btnNo.onclick = () => {
                overlay.style.display = "none";
            };
        });
    });
});
