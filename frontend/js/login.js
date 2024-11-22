document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault()
    const usuario = document.getElementById('usuario').value
    const password = document.getElementById('password').value

    // Verificar credenciales del usuario
    const user = users.find(u => u.usuario === usuario && u.password === password)

    if (user) {
        // Redirigir según el rol
        if (user.role === "admin") {
            window.location.href = "admin.html"
        } else if (user.role === "user") {
            window.location.href = "user.html"
        }
    } else {
        alert("Usuario o contraseña incorrecta. Por favor intenta de nuevo")
    }
})