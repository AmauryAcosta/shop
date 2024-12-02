document.getElementById("loginForm").addEventListener("submit", async function (event) {
  event.preventDefault();

  const usuario = document.getElementById("usuario").value;
  const password = document.getElementById("password").value;

  try {
    // Enviar las credenciales al backend
    const response = await fetch("/api/login", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ usuario, password }),
    });

    if (!response.ok) {
      throw new Error("Usuario o contraseña incorrecta.");
    }

    const data = await response.json();

    // Redirigir según el rol
    if (data.role === "admin") {
      window.location.href = "admin.html";
    } else if (data.role === "user") {
      window.location.href = "user.html";
    }
  } catch (error) {
    alert(error.message);
  }
});
