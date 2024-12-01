// Ruta base para las solicitudes
const BASE_URL = "/shop/Back/ventas.php";

// Obtener todas las ventas y mostrarlas en la tabla
async function obtenerVentas() {
  try {
    const response = await fetch(BASE_URL, { method: "GET" });
    if (!response.ok) throw new Error("Error al obtener ventas");
    const ventas = await response.json();
    const tbody = document.querySelector("#ventasTable");
    tbody.innerHTML = ""; // Limpiar el contenido anterior

    ventas.forEach((venta) => {
      const precio = parseFloat(venta.precio) || 0;
      const cantidad = parseInt(venta.cantidad) || 0;
      const total = (precio * cantidad).toFixed(2);

      const row = document.createElement("tr");
      row.innerHTML = `
                <td>${venta.nombre_producto}</td>
                <td>${cantidad}</td>
                <td>${venta.fecha_venta}</td>
                <td>${venta.nombre_empleado}</td>
                <td>$${total}</td>
                <td>
                    <button class="btn btn-warning btn-sm" onclick="editarVenta(${venta.id_venta})">
                        <i class="bi bi-pencil-fill"></i>
                    </button>
                    <button class="btn btn-danger btn-sm" onclick="eliminarVenta(${venta.id_venta})">
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

// Función para agregar una nueva venta
document
  .getElementById("guardarVentaBtn")
  .addEventListener("click", async () => {
    const id_empleado = document.getElementById("idEmpleado").value;
    const id_producto = document.getElementById("idProducto").value;
    const cantidad = document.getElementById("cantidadVenta").value;
    const fecha = document.getElementById("fechaVenta").value;

    try {
      const response = await fetch(BASE_URL, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          id_empleado: id_empleado,
          id_producto: id_producto,
          cantidad: cantidad,
          fecha_venta: fecha,
        }),
      });

      const result = await response.json();
      alert(result.message);
      obtenerVentas(); // Actualizar la lista de ventas
      document.getElementById("formRegistrarVenta").reset(); // Resetear el formulario
    } catch (error) {
      console.error("Error:", error);
    }
  });

// Función para eliminar una venta
async function eliminarVenta(id_venta) {
  if (confirm("¿Estás seguro de que deseas eliminar esta venta?")) {
    try {
      const response = await fetch(BASE_URL, {
        method: "DELETE",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id_venta: id_venta }),
      });

      const result = await response.json();
      alert(result.message);
      obtenerVentas(); // Actualizar la lista de ventas
    } catch (error) {
      console.error("Error:", error);
    }
  }
}

// Función para editar una venta
function editarVenta(id_venta) {
  // Aquí puedes implementar la lógica para cargar los datos de la venta y mostrarlos en un modal para editar
  alert("Funcionalidad de editar no implementada");
}

// Inicializar la carga de ventas al cargar la página
document.addEventListener("DOMContentLoaded", obtenerVentas);
