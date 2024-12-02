
-- Crear base de datos
CREATE DATABASE IF NOT EXISTS tienda;
USE tienda;

-- Tabla de empleados
CREATE TABLE empleados (
    id_empleado INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    puesto VARCHAR(50) NOT NULL,
    usuario VARCHAR(250) NOT NULL, 
    password VARCHAR(250) NOT NULL,
    fecha_contratacion DATE NOT NULL
);

-- Tabla de productos
CREATE TABLE productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    nombre_producto VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10, 2) NOT NULL,
    estado ENUM('disponible', 'no disponible') NOT NULL DEFAULT 'disponible'
);

-- Tabla de inventario
CREATE TABLE inventario (
    id_inventario INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL,
    FOREIGN KEY (id_producto) REFERENCES productos(id_producto) ON DELETE CASCADE
);

-- Tabla de ventas
CREATE TABLE ventas (
    id_venta INT AUTO_INCREMENT PRIMARY KEY,
    id_empleado INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL,
    fecha_venta DATETIME NOT NULL,
    FOREIGN KEY (id_empleado) REFERENCES empleados(id_empleado) ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES productos(id_producto) ON DELETE CASCADE
);

-- Trigger para verificar el inventario antes de una venta
DELIMITER $$
CREATE TRIGGER before_insert_ventas
BEFORE INSERT ON ventas
FOR EACH ROW
BEGIN
    DECLARE stock_actual INT;
    
    -- Obtener la cantidad actual en inventario
    SELECT cantidad INTO stock_actual
    FROM inventario
    WHERE id_producto = NEW.id_producto;

    -- Verificar si hay suficiente inventario
    IF stock_actual IS NULL OR stock_actual < NEW.cantidad THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'No hay suficiente inventario para realizar la venta';
    END IF;

    -- Actualizar el inventario
    UPDATE inventario
    SET cantidad = cantidad - NEW.cantidad
    WHERE id_producto = NEW.id_producto;
END $$
DELIMITER ;