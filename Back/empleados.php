<?php

require 'db.php';

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");


$requestUri = $_SERVER['REQUEST_URI'];

// Manejo de las solicitudes POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (strpos($requestUri, '/api/login') !== false) {
        $input = json_decode(file_get_contents('php://input'), true);
        $usuario = $input['usuario'];
        $password = $input['password'];

        $sql = "SELECT puesto FROM usuarios WHERE usuario = :usuario AND password = :password";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['usuario' => $usuario, 'password' => $password]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            echo json_encode(['puesto' => $user['puesto']]);
        } else {
            http_response_code(401);
            echo json_encode(['error' => 'Credenciales inválidas']);
        }
    } elseif (strpos($requestUri, '/api/empleados') !== false) {
        $data = json_decode(file_get_contents("php://input"));

        if (isset($data->nombre) && isset($data->puesto) && isset($data->usuario) && isset($data->password) && isset($data->fecha_contratacion)) {
            $hashedPassword = password_hash($data->password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO empleados (nombre, puesto, usuario, password, fecha_contratacion) VALUES (:nombre, :puesto, :usuario, :password, :fecha_contratacion)";
            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':nombre' => $data->nombre,
                ':puesto' => $data->puesto,
                ':usuario' => $data->usuario,
                ':password' => $hashedPassword,
                ':fecha_contratacion' => $data->fecha_contratacion
            ]);

            echo json_encode(['status' => 'success', 'message' => 'Empleado agregado correctamente.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Datos incompletos.']);
        }
    }
}

// Manejo de las solicitudes GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Prepara la consulta SQL para obtener todos los empleados
    $sql = "SELECT * FROM empleados";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // Obtiene todos los empleados
    $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($empleados);
}

// Manejo de las solicitudes DELETE
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->id_empleado)) {
        $id_empleado = $data->id_empleado;

        $sql = "DELETE FROM empleados WHERE id_empleado = :id_empleado";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_empleado', $id_empleado);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Empleado eliminado correctamente.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al eliminar el empleado.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ID no proporcionado.']);
    }
}

// Manejo de las solicitudes PUT
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->id_empleado) && isset($data->nombre) && isset($data->puesto) && isset($data->fecha_contratacion)) {
        $id_empleado = $data->id_empleado;
        $nombre = $data->nombre;
        $puesto = $data->puesto;
        $fechaContratacion = $data->fecha_contratacion;

        $sql = "UPDATE empleados SET nombre = :nombre, puesto = :puesto, fecha_contratacion = :fecha_contratacion WHERE id_empleado = :id_empleado";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_empleado', $id_empleado);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':puesto', $puesto);
        $stmt->bindParam(':fecha_contratacion', $fechaContratacion);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Empleado actualizado correctamente.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al actualizar el empleado.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Datos incompletos.']);
    }
}

?>