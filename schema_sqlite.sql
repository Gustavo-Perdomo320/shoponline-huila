PRAGMA foreign_keys = OFF;

CREATE TABLE IF NOT EXISTS categoria (
    id_categoria INTEGER PRIMARY KEY,
    nombre_categoria TEXT NOT NULL
);
CREATE TABLE IF NOT EXISTS cliente (
    id_cliente INTEGER PRIMARY KEY,
    nombre TEXT NOT NULL,
    apellido TEXT NOT NULL,
    correo TEXT,
    telefono TEXT
);
CREATE TABLE IF NOT EXISTS producto (
    id_producto INTEGER PRIMARY KEY,
    id_categoria INTEGER NOT NULL,
    nombre_producto TEXT NOT NULL,
    precio REAL NOT NULL DEFAULT 0,
    stock INTEGER NOT NULL DEFAULT 0,
    FOREIGN KEY (id_categoria) REFERENCES categoria(id_categoria)
);
CREATE TABLE IF NOT EXISTS empleado (
    id_empleado INTEGER PRIMARY KEY,
    nombre TEXT NOT NULL,
    apellido TEXT NOT NULL,
    cargo TEXT,
    salario REAL DEFAULT 0,
    fecha_ingreso TEXT
);
CREATE TABLE IF NOT EXISTS pedido (
    id_pedido INTEGER PRIMARY KEY,
    id_cliente INTEGER NOT NULL,
    fecha_pedido TEXT NOT NULL,
    total REAL DEFAULT 0,
    estado TEXT,
    FOREIGN KEY (id_cliente) REFERENCES cliente(id_cliente)
);
CREATE TABLE IF NOT EXISTS detalle_pedido (
    id_detalle INTEGER PRIMARY KEY,
    id_pedido INTEGER NOT NULL,
    id_producto INTEGER NOT NULL,
    cantidad INTEGER DEFAULT 1,
    subtotal REAL DEFAULT 0,
    FOREIGN KEY (id_pedido) REFERENCES pedido(id_pedido),
    FOREIGN KEY (id_producto) REFERENCES producto(id_producto)
);
CREATE TABLE IF NOT EXISTS pago (
    id_pago INTEGER PRIMARY KEY,
    id_pedido INTEGER NOT NULL,
    fecha_pago TEXT,
    metodo_pago TEXT,
    valor_pagado REAL,
    FOREIGN KEY (id_pedido) REFERENCES pedido(id_pedido)
);
CREATE TABLE IF NOT EXISTS envio (
    id_envio INTEGER PRIMARY KEY,
    id_pedido INTEGER NOT NULL,
    direccion_envio TEXT,
    ciudad TEXT,
    telefono_contacto TEXT,
    FOREIGN KEY (id_pedido) REFERENCES pedido(id_pedido)
);
CREATE TABLE IF NOT EXISTS despacho (
    id_despacho INTEGER PRIMARY KEY,
    id_pedido INTEGER NOT NULL,
    id_empleado INTEGER NOT NULL,
    fecha_despacho TEXT,
    estado_despacho TEXT,
    FOREIGN KEY (id_pedido) REFERENCES pedido(id_pedido),
    FOREIGN KEY (id_empleado) REFERENCES empleado(id_empleado)
);

PRAGMA foreign_keys = ON;

INSERT INTO categoria (nombre_categoria) VALUES ('Electrónica'),('Ropa'),('Hogar'),('Deportes');
INSERT INTO cliente (nombre,apellido,correo,telefono) VALUES
('Carlos','Ramírez','carlos@gmail.com','3101234567'),
('Laura','Gómez','laura@gmail.com','3209876543'),
('Andrés','Pérez','andres@gmail.com','3155556677');
INSERT INTO producto (id_categoria,nombre_producto,precio,stock) VALUES
(1,'Audífonos Bluetooth',89900,25),(1,'Cargador USB-C 65W',35000,40),
(2,'Camiseta Deportiva',29900,60),(2,'Tenis Running',159900,15),
(3,'Lámpara LED de Escritorio',55000,20),(4,'Guantes de Boxeo',75000,12);
INSERT INTO empleado (id_empleado,nombre,apellido,cargo,salario,fecha_ingreso) VALUES
(1,'Coordinador','General','Logística',1500000,'2024-01-15'),
(2,'María','Torres','Ventas',1200000,'2024-03-01');
INSERT INTO pedido (id_cliente,fecha_pedido,total,estado) VALUES
(1,'2026-05-20',124900,'Pagado / Pendiente Despacho'),
(2,'2026-05-28',159900,'Despachado'),
(3,'2026-06-01',89900,'Pagado / Pendiente Despacho');
INSERT INTO detalle_pedido (id_pedido,id_producto,cantidad,subtotal) VALUES
(1,1,1,89900),(1,3,1,29900),(2,4,1,159900),(3,1,1,89900);
INSERT INTO pago (id_pedido,fecha_pago,metodo_pago,valor_pagado) VALUES
(1,'2026-05-20','Transferencia',124900),(2,'2026-05-28','Tarjeta',159900),(3,'2026-06-01','Efectivo',89900);
INSERT INTO envio (id_pedido,direccion_envio,ciudad,telefono_contacto) VALUES
(1,'Cra 5 # 12-34','Neiva','3101234567'),(2,'Calle 8 # 6-15','Neiva','3209876543'),(3,'Av. Circunvalar # 22-10','Neiva','3155556677');
INSERT INTO despacho (id_pedido,id_empleado,fecha_despacho,estado_despacho) VALUES
(1,1,'2026-05-20','En proceso de empaque'),(2,1,'2026-05-29','Entregado'),(3,1,'2026-06-01','En proceso de empaque');
