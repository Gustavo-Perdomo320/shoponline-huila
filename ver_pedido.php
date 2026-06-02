<?php
// ver_pedido.php
require 'db.php';
require 'header.php';

$id_pedido = isset($_GET['id']) ? intval($_GET['id']) : 0;

$p_res = $conn->query("SELECT p.*, c.nombre AS c_nom, c.apellido AS c_ape, c.correo, e.direccion_envio, e.ciudad, pag.metodo_pago, d.estado_despacho, emp.nombre AS e_nom, emp.apellido AS e_ape
                       FROM pedido p
                       INNER JOIN cliente c ON p.id_cliente = c.id_cliente
                       INNER JOIN envio e ON p.id_pedido = e.id_pedido
                       INNER JOIN pago pag ON p.id_pedido = pag.id_pedido
                       INNER JOIN despacho d ON p.id_pedido = d.id_pedido
                       INNER JOIN empleado emp ON d.id_empleado = emp.id_empleado
                       WHERE p.id_pedido = $id_pedido");

if($p_res->num_rows == 0) {
    echo "<div class='alert alert-danger'>Pedido no encontrado.</div>";
    require 'footer.php'; exit;
}
$p = $p_res->fetch_assoc();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="section-title mb-0">Detalle del Pedido #<?php echo $id_pedido; ?></h2>
    <a href="index.php" class="btn btn-light border">Volver</a>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card h-100" style="background-color: #fafafa;">
            <h6 class="text-muted mb-3 text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px;">Información General</h6>
            <p class="mb-1"><strong>Cliente:</strong> <?php echo $p['c_nom'] . " " . $p['c_ape']; ?></p>
            <p class="mb-1"><strong>Fecha:</strong> <?php echo $p['fecha_pedido']; ?></p>
            <p class="mb-1"><strong>Pago:</strong> <?php echo $p['metodo_pago']; ?></p>
            <p class="mb-0 mt-3"><span style="background: #e8f5e9; color: #2e7d32; padding: 4px 10px; border-radius: 6px; font-weight: 500;"><?php echo $p['estado']; ?></span></p>
        </div>
    </div>
    
    <div class="col-md-8 mb-4">
        <div class="card h-100" style="background-color: #fafafa;">
            <h6 class="text-muted mb-3 text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px;">Logística y Envío</h6>
            <p class="mb-1"><strong>Destino:</strong> <?php echo $p['direccion_envio']; ?>, <?php echo $p['ciudad']; ?></p>
            <p class="mb-1"><strong>Encargado de Despacho:</strong> <?php echo $p['e_nom'] . " " . $p['e_ape']; ?></p>
            <p class="mb-0 mt-3"><span style="background: #e3f2fd; color: #1565c0; padding: 4px 10px; border-radius: 6px; font-weight: 500;"><?php echo $p['estado_despacho']; ?></span></p>
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            <h6 class="text-muted mb-3 text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px;">Artículos Comprados</h6>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead><tr><th>Producto</th><th>Cantidad</th><th>Precio Und.</th><th class="text-end">Subtotal</th></tr></thead>
                    <tbody>
                        <?php
                        $det_res = $conn->query("SELECT dp.*, pr.nombre_producto, pr.precio FROM detalle_pedido dp 
                                                 INNER JOIN producto pr ON dp.id_producto = pr.id_producto 
                                                 WHERE dp.id_pedido = $id_pedido");
                        while($d = $det_res->fetch_assoc()) {
                            echo "<tr>
                                    <td><strong>{$d['nombre_producto']}</strong></td>
                                    <td>{$d['cantidad']}</td>
                                    <td>$" . number_format($d['precio'], 2) . "</td>
                                    <td class='text-end fw-bold'>$" . number_format($d['subtotal'], 2) . "</td>
                                  </tr>";
                        }
                        ?>
                        <tr>
                            <td colspan="3" class="text-end text-muted pt-4">Total Facturado:</td>
                            <td class="text-end pt-4" style="font-size: 1.25rem; font-weight: 700; color: #0071e3;">$<?php echo number_format($p['total'], 2); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require 'footer.php'; ?>