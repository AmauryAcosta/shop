<?php

require 'db.php';

header('Content-Type: application/json');

// Manejo de las solicitudes POST (Crear)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->id_producto) && isset($data->cantidad)) {
        $id_producto = $data->id_producto;
        $cantidad = $data->cantidad;

        // Verificar si el producto existe
        $sql = "SELECT * FROM productos WHERE id_producto = :id_producto";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_producto', $id_producto);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $sql = "INSERT INTO inventario (id_producto, cantidad) VALUES (:id_producto, :cantidad)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id_producto', $id_producto);
            $stmt->bindParam(':cantidad', $cantidad);

            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Inventario agregado correctamente.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error al agregar el inventario.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'El producto no existe.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Datos incompletos.']);
    }
}

// Manejo de las solicitudes GET (Leer)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT i.id_inventario, i.id_producto, i.cantidad, p.nombre_producto 
            FROM inventario i 
            JOIN productos p ON i.id_producto = p.id_producto";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $inventario = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($inventario);
}

// Manejo de las solicitudes DELETE (Eliminar)
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->id_inventario)) {
        $id_inventario = $data->id_inventario;

        $sql = "DELETE FROM inventario WHERE id_inventario = :id_inventario";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_inventario', $id_inventario);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Inventario eliminado correctamente.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al eliminar el inventario.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ID no proporcionado.']);
    }
}

// Manejo de las solicitudes PUT (Actualizar)
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->id_inventario) && isset($data->id_producto) && isset($data->cantidad)) {
        $id_inventario = $data->id_inventario;
        $id_producto = $data->id_producto;
        $cantidad = $data->cantidad;

        // Verificar si el producto existe
        $sql = "SELECT * FROM productos WHERE id_producto = :id_producto";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_producto', $id_producto);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $sql = "UPDATE inventario SET id_producto = :id_producto, cantidad = :cantidad WHERE id_inventario = :id_inventario";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id_inventario', $id_inventario);
            $stmt->bindParam(':id_producto', $id_producto);
            $stmt->bindParam(':cantidad', $cantidad);

            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Inventario actualizado correctamente.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error al actualizar el inventario.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'El producto no existe.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Datos incompletos.']);
    }
}
?>