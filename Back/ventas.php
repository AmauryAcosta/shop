<?php

require 'db.php';

header('Content-Type: application/json');

// Manejo de las solicitudes POST (Crear)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->id_empleado) && isset($data->id_producto) && isset($data->cantidad) && isset($data->fecha_venta)) {
        $id_empleado = $data->id_empleado;
        $id_producto = $data->id_producto;
        $cantidad = $data->cantidad;
        $fecha_venta = $data->fecha_venta;

        // Verificar si el empleado existe
        $sql = "SELECT * FROM empleados WHERE id_empleado = :id_empleado";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_empleado', $id_empleado);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Intentar insertar la venta
            try {
                $sql = "INSERT INTO ventas (id_empleado, id_producto, cantidad, fecha_venta) VALUES (:id_empleado, :id_producto, :cantidad, :fecha_venta)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id_empleado', $id_empleado);
                $stmt->bindParam(':id_producto', $id_producto);
                $stmt->bindParam(':cantidad', $cantidad);
                $stmt->bindParam(':fecha_venta', $fecha_venta);

                if ($stmt->execute()) {
                    echo json_encode(['status' => 'success', 'message' => 'Venta registrada correctamente.']);
                }
            } catch (PDOException $e) {
                echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'El empleado no existe.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Datos incompletos.']);
    }
}

// Manejo de las solicitudes GET (Leer)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT v.id_venta, e.nombre AS nombre_empleado, p.nombre_producto, v.cantidad, v.fecha_venta, p.precio 
            FROM ventas v 
            JOIN empleados e ON v.id_empleado = e.id_empleado 
            JOIN productos p ON v.id_producto = p.id_producto";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($ventas);
}

// Manejo de las solicitudes DELETE (Eliminar)
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->id_venta)) {
        $id_venta = $data->id_venta;

        $sql = "DELETE FROM ventas WHERE id_venta = :id_venta";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_venta', $id_venta);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Venta eliminada correctamente.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al eliminar la venta.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ID no proporcionado.']);
    }
}

// Manejo de las solicitudes PUT (Actualizar)
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->id_venta) && isset($data->id_empleado) && isset($data->id_producto) && isset($data->cantidad) && isset($data->fecha_venta)) {
        $id_venta = $data->id_venta;
        $id_empleado = $data->id_empleado;
        $id_producto = $data->id_producto;
        $cantidad = $data->cantidad;
        $fecha_venta = $data->fecha_venta;

        // Verificar si el empleado existe
        $sql = "SELECT * FROM empleados WHERE id_empleado = :id_empleado";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_empleado', $id_empleado);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Verificar si el producto existe
            $sql = "SELECT * FROM productos WHERE id_producto = :id_producto";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id_producto', $id_producto);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                // Intentar actualizar la venta
                $sql = "UPDATE ventas SET id_empleado = :id_empleado, id_producto = :id_producto, cantidad = :cantidad, fecha_venta = :fecha_venta WHERE id_venta = :id_venta";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id_venta', $id_venta);
                $stmt->bindParam(':id_empleado', $id_empleado);
                $stmt->bindParam(':id_producto', $id_producto);
                $stmt->bindParam(':cantidad', $cantidad);
                $stmt->bindParam(':fecha_venta', $fecha_venta);

                if ($stmt->execute()) {
                    echo json_encode(['status' => 'success', 'message' => 'Venta actualizada correctamente.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Error al actualizar la venta.']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'El producto no existe.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'El empleado no existe.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Datos incompletos.']);
    }
}
?>