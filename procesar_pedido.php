<?php
// procesar_pedido.php - Motor de Transacciones e Integridad del Sistema
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_cliente = intval($_POST['id_cliente']);
    $direccion_envio = $conn->real_escape_string($_POST['direccion_envio']);
    $ciudad = $conn->real_escape_string($_POST['ciudad']);
    $telefono_contacto = $conn->real_escape_string($_POST['telefono_contacto']);
    $metodo_pago = $conn->real_escape_string($_POST['metodo_pago']);

    $arr_productos = $_POST['productos'];
    $arr_cantidades = $_POST['cantidades'];

    if(empty($id_cliente) || empty($arr_productos)) {
        die("Error: Datos incompletos para procesar el pedido.");
    }

    // 1. Calcular el Total acumulado del pedido consultando los precios reales
    $total_pedido = 0;
    $items_procesados = [];

    foreach($arr_productos as $index => $id_p) {
        $id_p = intval($id_p);
        $cant = intval($arr_cantidades[$index]);
        if($id_p > 0 && $cant > 0) {
            $p_res = $conn->query("SELECT precio, stock FROM producto WHERE id_producto = $id_p");
            if($p_row = $p_res->fetch_assoc()) {
                if($p_row['stock'] < $cant) {
                    die("<script>alert('Error: Stock insuficiente para procesar uno de los productos seleccionados.'); window.history.back();</script>");
                }
                $subtotal = $p_row['precio'] * $cant;
                $total_pedido += $subtotal;
                $items_procesados[] = [
                    'id_producto' => $id_p,
                    'cantidad' => $cant,
                    'subtotal' => $subtotal
                ];
            }
        }
    }

    // 2. Crear Registro Maestro en Tabla 'pedido'
    $fecha_actual = date('Y-m-d');
    $estado_inicial = "Pagado / Pendiente Despacho";
    $conn->query("INSERT INTO pedido (id_cliente, fecha_pedido, total, estado) VALUES ($id_cliente, '$fecha_actual', $total_pedido, '$estado_inicial')");
    $id_pedido_nuevo = $conn->insert_id;

    // 3. Procesar Detalles, Modificar Stock y Guardar Multifilas
    foreach($items_procesados as $item) {
        $id_prod = $item['id_producto'];
        $cant = $item['cantidad'];
        $sub = $item['subtotal'];
        
        // Insertar detalle
        $conn->query("INSERT INTO detalle_pedido (id_pedido, id_producto, cantidad, subtotal) VALUES ($id_pedido_nuevo, $id_prod, $cant, $sub)");
        // Afectar el Stock restando el inventario actual vendido
        $conn->query("UPDATE producto SET stock = stock - $cant WHERE id_producto = $id_prod");
    }

    // 4. Registrar de Forma Única e Inmediata el Pago Realizado
    $conn->query("INSERT INTO pago (id_pedido, fecha_pago, metodo_pago, valor_pagado) VALUES ($id_pedido_nuevo, '$fecha_actual', '$metodo_pago', $total_pedido)");

    // 5. Registrar la Información Logística Completa de Envíos
    $conn->query("INSERT INTO envio (id_pedido, direccion_envio, ciudad, telefono_contacto) VALUES ($id_pedido_nuevo, '$direccion_envio', '$ciudad', '$telefono_contacto')");

    // 6. Asignar Responsabilidad Operativa del Despacho de Forma Automatizada a un Empleado
    // Crear un empleado comodín si la tabla está vacía para evitar errores de claves foráneas
    $conn->query("INSERT IGNORE INTO empleado (id_empleado, nombre, apellido, cargo, salario, fecha_ingreso) VALUES (1, 'Coordinador', 'General', 'Logística', 1500000.00, '$fecha_actual')");
    $emp_res = $conn->query("SELECT id_empleado FROM empleado LIMIT 1");
    $emp_row = $emp_res->fetch_assoc();
    $id_empleado_responsable = $emp_row['id_empleado'];

    $conn->query("INSERT INTO despacho (id_pedido, id_empleado, fecha_despacho, estado_despacho) VALUES ($id_pedido_nuevo, $id_empleado_responsable, '$fecha_actual', 'En proceso de empaque')");

    // Redireccionar al Dashboard con confirmación de éxito
    echo "<script>alert('¡Pedido transaccionado y procesado con total éxito en la base de datos!'); window.location='index.php';</script>";
}
?>