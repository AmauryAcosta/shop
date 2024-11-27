<?php

require 'db.php';

header('Content-Type: application/json');

// Manejo de las solicitudes POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtiene los datos del cuerpo de la solicitud
    $data = json_decode(file_get_contents("php://input"));

    // Verifica que se reciban los datos necesarios
    if (isset($data->nombre) && isset($data->puesto) && isset($data->fecha_contratacion)) {
        $nombre = $data->nombre;
        $puesto = $data->puesto;
        $fechaContratacion = $data->fecha_contratacion;

        // Prepara la consulta SQL para insertar un nuevo empleado
        $sql = "INSERT INTO empleados (nombre, puesto, fecha_contratacion) VALUES (:nombre, :puesto, :fecha_contratacion)";
        $stmt = $pdo->prepare($sql);

        // Asigna los parámetros
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':puesto', $puesto);
        $stmt->bindParam(':fecha_contratacion', $fechaContratacion);

        // Ejecuta la consulta
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Empleado agregado correctamente.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al agregar el empleado.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Datos incompletos.']);
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

    if (isset($data->id)) {
        $id = $data->id;

        $sql = "DELETE FROM empleados WHERE id_empleado = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);

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