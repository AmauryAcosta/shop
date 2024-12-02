// Ruta base para las solicitudes
const BASE_URL = "/shop/Back/productos.php";

// Obtener todos los productos y mostrarlos en la tabla
async function obtenerProductos() {
  try {
    const response = await fetch(BASE_URL, { method: "GET" });
    if (!response.ok) throw new Error("Error al obtener productos");
    const productos = await response.json();
    const tbody = document.querySelector("#productTable");
    tbody.innerHTML = ""; // Limpiar el contenido anterior

    productos.forEach((producto) => {
      const row = document.createElement("tr");
      row.innerHTML = `
                <td>${producto.nombre_producto}</td>
                <td>${producto.descripcion}</td>
                <td>${producto.precio}</td>
                <td>${producto.estado}</td>
                <td>
                    <button class="btn btn-warning btn-sm" onclick="editarProducto(${producto.id_producto})">
                        <i class="bi bi-pencil-fill"></i>
                    </button>
                    <button class="btn btn-danger btn-sm" onclick="eliminarProducto(${producto.id_producto})">
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

// Función para agregar un producto
document
  .getElementById("guardarProductoBtn")
  .addEventListener("click", async () => {
    const nombre_producto = document.getElementById("nombreProducto").value;
    const descripcion = document.getElementById("descripcionProducto").value;
    const precio = document.getElementById("precioProducto").value;
    const estado = document.getElementById("estadoProducto").value;

    try {
      const response = await fetch(BASE_URL, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          nombre_producto,
          descripcion,
          precio,
          estado,
        }),
      });

      const result = await response.json();
      alert(result.message);
      obtenerProductos(); // Actualizar la lista de productos
      document.getElementById("formRegistrarProducto").reset(); // Resetear el formulario
    } catch (error) {
      console.error("Error:", error);
    }
  });

// Función para editar producto
function editarProducto(id) {
  fetch(`${BASE_URL}?id_producto=${id}`, { method: "GET" })
    .then((response) => response.json())
    .then((producto) => {
      document.getElementById("id_producto").value = producto.id_producto;
      document.getElementById("editarNombreProducto").value =
        producto.nombre_producto;
      document.getElementById("editarDescripcionProducto").value =
        producto.descripcion;
      document.getElementById("editarPrecioProducto").value = producto.precio;
      document.getElementById("editarEstadoProducto").value = producto.estado;

      const modalEditar = new bootstrap.Modal(
        document.getElementById("modalEditarProducto")
      );
      modalEditar.show();
    })
    .catch((error) => console.error("Error:", error));
}

// Guardar cambios en producto
document
  .getElementById("guardarCambiosBtn")
  .addEventListener("click", async () => {
    const id_producto = document.getElementById("id_producto").value;
    const nombre_producto = document.getElementById(
      "editarNombreProducto"
    ).value;
    const descripcion = document.getElementById(
      "editarDescripcionProducto"
    ).value;
    const precio = document.getElementById("editarPrecioProducto").value;
    const estado = document.getElementById("editarEstadoProducto").value;

    try {
      const response = await fetch(BASE_URL, {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          id_producto,
          nombre_producto,
          descripcion,
          precio,
          estado,
        }),
      });

      const result = await response.json();
      alert(result.message);
      obtenerProductos(); // Actualizar la lista de productos

      const modalEditar = bootstrap.Modal.getInstance(
        document.getElementById("modalEditarProducto")
      );
      modalEditar.hide(); // Cerrar el modal de edición
    } catch (error) {
      console.error("Error:", error);
    }
  });

// Eliminar producto
function eliminarProducto(id) {
  if (confirm("¿Estás seguro de que deseas eliminar este producto?")) {
    fetch(BASE_URL, {
      method: "DELETE",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id_producto: id }),
    })
      .then((response) => response.json())
      .then((result) => {
        alert(result.message);
        obtenerProductos(); // Actualizar la lista de productos
      })
      .catch((error) => console.error("Error:", error));
  }
}

// Inicializar la carga de productos al cargar la página
document.addEventListener("DOMContentLoaded", obtenerProductos);
