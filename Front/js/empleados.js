// Ruta base para las solicitudes
const BASE_URL = "/shop/Back/empleados.php";

// Obtener todos los empleados y mostrarlos en la tabla
async function obtenerEmpleados() {
  try {
    const response = await fetch(BASE_URL, { method: "GET" });
    if (!response.ok) throw new Error("Error al obtener empleados");
    const empleados = await response.json();
    const tbody = document.querySelector("#tablaEmpleados tbody");
    tbody.innerHTML = ""; // Limpiar el contenido anterior

    empleados.forEach((empleado) => {
      const row = document.createElement("tr");
      row.innerHTML = `
                <td>${empleado.nombre}</td>
                <td>${empleado.puesto}</td>
                <td>${empleado.usuario}</td>
                <td>${empleado.fecha_contratacion}</td>
                <td>
                    <button class="btn btn-warning btn-sm" onclick="editarEmpleado(${empleado.id_empleado})">
                        <i class="bi bi-pencil-fill"></i>
                    </button>
                    <button class="btn btn-danger btn-sm" onclick="eliminarEmpleado(${empleado.id_empleado})">
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

// Función para agregar un empleado
document
  .getElementById("guardarEmpleadoBtn")
  .addEventListener("click", async () => {
    const nombre = document.getElementById("nombreEmpleado").value;
    const puesto = document.getElementById("puestoEmpleado").value;
    const usuario = document.getElementById("usuarioEmpleado").value;
    const password = document.getElementById("passwordEmpleado").value;
    const fechaContratacion =
      document.getElementById("fechaContratacion").value;

    try {
      const response = await fetch(BASE_URL, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          nombre,
          puesto,
          usuario,
          password,
          fecha_contratacion: fechaContratacion,
        }),
      });

      const result = await response.json();
      alert(result.message);
      obtenerEmpleados(); // Actualizar la lista de empleados
      document.getElementById("formRegistrarEmpleado").reset(); // Resetear el formulario
    } catch (error) {
      console.error("Error:", error);
    }
  });

// Función para editar empleado
function editarEmpleado(id) {
  fetch(`${BASE_URL}?id_empleado=${id}`, { method: "GET" })
    .then((response) => response.json())
    .then((empleado) => {
      document.getElementById("id_empleado").value = empleado.id_empleado;
      document.getElementById("editarNombreEmpleado").value = empleado.nombre;
      document.getElementById("editarPuestoEmpleado").value = empleado.puesto;
      document.getElementById("editarUsuarioEmpleado").value = empleado.usuario;
      document.getElementById("editarPasswordEmpleado").value = empleado.password;
      document.getElementById("editarFechaContratacion").value =
        empleado.fecha_contratacion;

      const modalEditar = new bootstrap.Modal(
        document.getElementById("modalEditarEmpleado")
      );
      modalEditar.show();
    })
    .catch((error) => console.error("Error:", error));
}

// Guardar cambios en empleado
document
  .getElementById("guardarCambiosBtn")
  .addEventListener("click", async () => {
    const id_empleado = document.getElementById("id_empleado").value;
    const nombre = document.getElementById("editarNombreEmpleado").value;
    const puesto = document.getElementById("editarPuestoEmpleado").value;
    const usuario = document.getElementById("editarUsuarioEmpleado").value;
    const password = document.getElementById("editarPasswordEmpleado").value;
    const fechaContratacion = document.getElementById(
      "editarFechaContratacion"
    ).value;

    try {
      const response = await fetch(BASE_URL, {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          id_empleado,
          nombre,
          puesto,
          usuario,
          password,
          fecha_contratacion: fechaContratacion,
        }),
      });

      const result = await response.json();
      alert(result.message);
      obtenerEmpleados(); // Actualizar la lista de empleados

      const modalEditar = bootstrap.Modal.getInstance(
        document.getElementById("modalEditarEmpleado")
      );
      modalEditar.hide(); // Cerrar el modal de edición
    } catch (error) {
      console.error("Error:", error);
    }
  });

// Eliminar empleado
function eliminarEmpleado(id) {
  if (confirm("¿Estás seguro de que deseas eliminar este empleado?")) {
    fetch(BASE_URL, {
      method: "DELETE",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id_empleado: id }),
    })
      .then((response) => response.json())
      .then((result) => {
        alert(result.message);
        obtenerEmpleados(); // Actualizar la lista de empleados
      })
      .catch((error) => console.error("Error:", error));
  }
}

// Inicializar la carga de empleados al cargar la página
document.addEventListener("DOMContentLoaded", obtenerEmpleados);
