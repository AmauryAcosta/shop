// Ruta base para las solicitudes
const BASE_URL = "http://localhost:8888/shop/Back/inventario.php";

// Obtener todos los productos y mostrarlos en el selector
async function obtenerProductos() {
  try {
    const response = await fetch(
      "http://localhost:8888/shop/Back/productos.php"
    );
    if (!response.ok) throw new Error("Error al obtener productos");
    const productos = await response.json();
    const select = document.getElementById("productoSeleccionado");
    select.innerHTML = ""; // Limpiar el contenido anterior

    // Agregar opciones al select
    productos.forEach((producto) => {
      const option = document.createElement("option");
      option.value = producto.id_producto;
      option.textContent = producto.nombre_producto;
      select.appendChild(option);
    });
  } catch (error) {
    console.error("Error:", error);
  }
}

// Obtener todos los inventarios y mostrarlos en la tabla
async function obtenerInventarios() {
  try {
    const response = await fetch(BASE_URL, { method: "GET" });
    if (!response.ok) throw new Error("Error al obtener inventarios");
    const inventarios = await response.json();
    const tbody = document.querySelector("#inventarioTable tbody");
    tbody.innerHTML = ""; // Limpiar el contenido anterior

    inventarios.forEach((inventario) => {
      const row = document.createElement("tr");
      row.innerHTML = `
                <td>${inventario.id_producto}</td>
                <td>${inventario.nombre_producto}</td>
                <td>${inventario.cantidad}</td>
                <td>
                    <button class="btn btn-warning btn-sm" onclick="editarInventario(${inventario.id_inventario}, ${inventario.cantidad})">
                        <i class="bi bi-pencil-fill"></i>
                    </button>
                    <button class="btn btn-danger btn-sm" onclick="eliminarInventario(${inventario.id_inventario})">
                        <i class="bi bi-trash-fill"></i>
                    </button>
                </td>
            `;
      tbody.appendChild(row);
    });
  } catch (error) {
    console.error("Error:", error);
  }
}

// Función para agregar un inventario
document
  .getElementById("guardarInventarioBtn")
  .addEventListener("click", async () => {
    const id_producto = document.getElementById("productoSeleccionado").value;
    const cantidad = parseInt(
      document.getElementById("productoCantidad").value,
      10
    );

    try {
      const response = await fetch(BASE_URL, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          id_producto,
          cantidad,
        }),
      });

      const result = await response.json();
      alert(result.message);
      obtenerInventarios(); // Actualizar la lista de inventarios
      document.getElementById("formRegistrarInventario").reset(); // Resetear el formulario
    } catch (error) {
      console.error("Error:", error);
    }
  });

// Función para editar un inventario
async function editarInventario(id_inventario, cantidadActual) {
  const nuevaCantidad = parseInt(
    prompt("Ingrese la nueva cantidad:", cantidadActual),
    10
  );
  if (isNaN(nuevaCantidad) || nuevaCantidad < 0) {
    alert("Cantidad no válida.");
    return;
  }

  try {
    const response = await fetch(BASE_URL, {
      method: "PUT",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        id_inventario,
        cantidad: nuevaCantidad,
      }),
    });

    // Verificar si la respuesta es correcta
    if (!response.ok) {
      throw new Error("Error al actualizar la cantidad en el inventario");
    }

    const result = await response.json();
    alert(result.message);
    obtenerInventarios();
  } catch (error) {
    console.error("Error:", error);
    alert("Ocurrió un error al intentar actualizar el inventario.");
  }
}

// Función para eliminar un inventario
async function eliminarInventario(id_inventario) {
  if (confirm("¿Estás seguro de eliminar este inventario?")) {
    try {
      await fetch(BASE_URL, {
        method: "DELETE",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          id_inventario,
        }),
      });

      alert("Inventario eliminado correctamente.");
      obtenerInventarios(); // Actualizar la lista de inventarios
    } catch (error) {
      console.error("Error:", error);
    }
  }
}

// Inicializar la carga de productos e inventarios al cargar la página
document.addEventListener("DOMContentLoaded", () => {
  obtenerProductos();
  obtenerInventarios();
});
