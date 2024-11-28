<?php

require 'db.php';

header('Content-Type: application/json');

// Manejo de las solicitudes POST (Crear)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->nombre_producto) && isset($data->descripcion) && isset($data->precio) && isset($data->estado)) {
        $nombre_producto = $data->nombre_producto;
        $descripcion = $data->descripcion;
        $precio = $data->precio;
        $estado = $data->estado;

        $sql = "INSERT INTO productos (nombre_producto, descripcion, precio, estado) VALUES (:nombre_producto, :descripcion, :precio, :estado)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre_producto', $nombre_producto);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':precio', $precio);
        $stmt->bindParam(':estado', $estado);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Producto agregado correctamente.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al agregar el producto.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Datos incompletos.']);
    }
}

// Manejo de las solicitudes GET (Leer)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT * FROM productos";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($productos);
}

// Manejo de las solicitudes DELETE (Eliminar)
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->id_producto)) {
        $id_producto = $data->id_producto;

        $sql = "DELETE FROM productos WHERE id_producto = :id_producto";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_producto', $id_producto);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Producto eliminado correctamente.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al eliminar el producto.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ID no proporcionado.']);
    }
}

// Manejo de las solicitudes PUT (Actualizar)
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->id_producto) && isset($data->nombre_producto) && isset($data->descripcion) && isset($data->precio) && isset($data->estado)) {
        $id_producto = $data->id_producto;
        $nombre_producto = $data->nombre_producto;
        $descripcion = $data->descripcion;
        $precio = $data->precio;
        $estado = $data->estado;

        $sql = "UPDATE productos SET nombre_producto = :nombre_producto, descripcion = :descripcion, precio = :precio, estado = :estado WHERE id_producto = :id_producto";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_producto', $id_producto);
        $stmt->bindParam(':nombre_producto', $nombre_producto);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':precio', $precio);
        $stmt->bindParam(':estado', $estado);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Producto actualizado correctamente.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al actualizar el producto.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Datos incompletos.']);
    }
}
?>