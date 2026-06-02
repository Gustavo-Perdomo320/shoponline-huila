-- ShopOnline Huila — Esquema completo + datos de demo
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS categoria (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre_categoria VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS cliente (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    correo VARCHAR(150),
    telefono VARCHAR(20)
);

CREATE TABLE IF NOT EXISTS producto (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    id_categoria INT NOT NULL,
    nombre_producto VARCHAR(150) NOT NULL,
    precio DECIMAL(10,2) NOT NULL DEFAULT 0,
    stock INT NOT NULL DEFAULT 0,
    FOREIGN KEY (id_categoria) REFERENCES categoria(id_categoria)
);

CREATE TABLE IF NOT EXISTS empleado (
    id_empleado INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    cargo VARCHAR(100),
    salario DECIMAL(12,2) DEFAULT 0,
    fecha_ingreso DATE
);

CREATE TABLE IF NOT EXISTS pedido (
    id_pedido INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    fecha_pedido DATE NOT NULL,
    total DECIMAL(12,2) DEFAULT 0,
    estado VARCHAR(100),
    FOREIGN KEY (id_cliente) REFERENCES cliente(id_cliente)
);

CREATE TABLE IF NOT EXISTS detalle_pedido (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT DEFAULT 1,
    subtotal DECIMAL(12,2) DEFAULT 0,
    FOREIGN KEY (id_pedido) REFERENCES pedido(id_pedido),
    FOREIGN KEY (id_producto) REFERENCES producto(id_producto)
);

CREATE TABLE IF NOT EXISTS pago (
    id_pago INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT NOT NULL,
    fecha_pago DATE,
    metodo_pago VARCHAR(50),
    valor_pagado DECIMAL(12,2),
    FOREIGN KEY (id_pedido) REFERENCES pedido(id_pedido)
);

CREATE TABLE IF NOT EXISTS envio (
    id_envio INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT NOT NULL,
    direccion_envio VARCHAR(200),
    ciudad VARCHAR(100),
    telefono_contacto VARCHAR(20),
    FOREIGN KEY (id_pedido) REFERENCES pedido(id_pedido)
);

CREATE TABLE IF NOT EXISTS despacho (
    id_despacho INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT NOT NULL,
    id_empleado INT NOT NULL,
    fecha_despacho DATE,
    estado_despacho VARCHAR(100),
    FOREIGN KEY (id_pedido) REFERENCES pedido(id_pedido),
    FOREIGN KEY (id_empleado) REFERENCES empleado(id_empleado)
);

SET FOREIGN_KEY_CHECKS = 1;

-- ─── Datos de demo ───────────────────────────────────────────────────────────

INSERT INTO categoria (nombre_categoria) VALUES
('Electrónica'), ('Ropa'), ('Hogar'), ('Deportes');

INSERT INTO cliente (nombre, apellido, correo, telefono) VALUES
('Carlos', 'Ramírez', 'carlos@gmail.com', '3101234567'),
('Laura', 'Gómez', 'laura@gmail.com', '3209876543'),
('Andrés', 'Pérez', 'andres@gmail.com', '3155556677');

INSERT INTO producto (id_categoria, nombre_producto, precio, stock) VALUES
(1, 'Audífonos Bluetooth', 89900, 25),
(1, 'Cargador USB-C 65W', 35000, 40),
(2, 'Camiseta Deportiva', 29900, 60),
(2, 'Tenis Running', 159900, 15),
(3, 'Lámpara LED de Escritorio', 55000, 20),
(4, 'Guantes de Boxeo', 75000, 12);

INSERT INTO empleado (nombre, apellido, cargo, salario, fecha_ingreso) VALUES
('Coordinador', 'General', 'Logística', 1500000.00, '2024-01-15'),
('María', 'Torres', 'Ventas', 1200000.00, '2024-03-01');

INSERT INTO pedido (id_cliente, fecha_pedido, total, estado) VALUES
(1, '2026-05-20', 124900, 'Pagado / Pendiente Despacho'),
(2, '2026-05-28', 159900, 'Despachado'),
(3, '2026-06-01', 89900,  'Pagado / Pendiente Despacho');

INSERT INTO detalle_pedido (id_pedido, id_producto, cantidad, subtotal) VALUES
(1, 1, 1, 89900), (1, 3, 1, 29900),
(2, 4, 1, 159900),
(3, 1, 1, 89900);

INSERT INTO pago (id_pedido, fecha_pago, metodo_pago, valor_pagado) VALUES
(1, '2026-05-20', 'Transferencia', 124900),
(2, '2026-05-28', 'Tarjeta',      159900),
(3, '2026-06-01', 'Efectivo',      89900);

INSERT INTO envio (id_pedido, direccion_envio, ciudad, telefono_contacto) VALUES
(1, 'Cra 5 # 12-34', 'Neiva', '3101234567'),
(2, 'Calle 8 # 6-15', 'Neiva', '3209876543'),
(3, 'Av. Circunvalar # 22-10', 'Neiva', '3155556677');

INSERT INTO despacho (id_pedido, id_empleado, fecha_despacho, estado_despacho) VALUES
(1, 1, '2026-05-20', 'En proceso de empaque'),
(2, 1, '2026-05-29', 'Entregado'),
(3, 1, '2026-06-01', 'En proceso de empaque');
