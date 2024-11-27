// Simulación de clic para cargar datos en el modal
document.querySelectorAll(".btn-warning").forEach((button) => {
  button.addEventListener("click", () => {
    // Ejemplo de datos a cargar
    const datosEmpleado = {
      nombre: "Juan",
      apellidoPaterno: "Pérez",
      apellidoMaterno: "Gómez",
      usuario: "jperez",
      rol: "Administrador",
    };

    // Rellenar los campos del modal
    document.getElementById("editarNombreEmpleado").value =
      datosEmpleado.nombre;
    document.getElementById("editarApellidoPaterno").value =
      datosEmpleado.apellidoPaterno;
    document.getElementById("editarApellidoMaterno").value =
      datosEmpleado.apellidoMaterno;
    document.getElementById("editarUsuarioEmpleado").value =
      datosEmpleado.usuario;
    document.getElementById("editarRolEmpleado").value = datosEmpleado.rol;
  });
});
