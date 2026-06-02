<?php
// index.php
require 'db.php';
require 'header.php';
?>

<h2 class="section-title">Panel de Análisis y Consultas</h2>

<div class="card mb-4">
    <h5 class="mb-4" style="color: #1d1d1f; font-weight: 600;">Reporte General de Pedidos</h5>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr><th>Pedido</th><th>Fecha</th><th>Cliente</th><th>Total</th><th>Estado</th><th>Acciones</th></tr>
            </thead>
            <tbody>
                <?php
                $q1 = "SELECT p.id_pedido, p.fecha_pedido, p.total, p.estado, c.nombre, c.apellido 
                       FROM pedido p 
                       INNER JOIN cliente c ON p.id_cliente = c.id_cliente 
                       ORDER BY p.id_pedido DESC";
                $res1 = $conn->query($q1);
                if ($res1->num_rows > 0) {
                    while($row = $res1->fetch_assoc()) {
                        echo "<tr>
                                <td class='text-muted'>#{$row['id_pedido']}</td>
                                <td>{$row['fecha_pedido']}</td>
                                <td><strong>{$row['nombre']} {$row['apellido']}</strong></td>
                                <td style='color: #0071e3; font-weight: 600;'>$" . number_format($row['total'], 2) . "</td>
                                <td><span style='background: #f5f5f7; padding: 4px 8px; border-radius: 6px; font-size: 0.85rem;'>{$row['estado']}</span></td>
                                <td><a href='ver_pedido.php?id={$row['id_pedido']}' class='btn btn-sm btn-light border'>Ver Detalle</a></td>
                              </tr>";
                    }
                } else { echo "<tr><td colspan='6' class='text-center text-muted py-4'>No hay pedidos registrados.</td></tr>"; }
                ?>
            </tbody>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <h5 class="mb-4" style="color: #1d1d1f; font-weight: 600;">Rendimiento de Productos</h5>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead><tr><th>Producto</th><th>Vendidos</th><th>Stock Actual</th></tr></thead>
                    <tbody>
                        <?php
                        $q2 = "SELECT p.nombre_producto, p.stock, IFNULL(SUM(dp.cantidad), 0) AS total_vendido 
                               FROM producto p 
                               LEFT JOIN detalle_pedido dp ON p.id_producto = dp.id_producto 
                               GROUP BY p.id_producto ORDER BY total_vendido DESC";
                        $res2 = $conn->query($q2);
                        while($row = $res2->fetch_assoc()) {
                            $stock_color = $row['stock'] > 0 ? '#1d1d1f' : '#e30000';
                            echo "<tr>
                                    <td>{$row['nombre_producto']}</td>
                                    <td><strong>{$row['total_vendido']}</strong> u.</td>
                                    <td style='color: $stock_color;'>{$row['stock']} u.</td>
                                  </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <h5 class="mb-4" style="color: #1d1d1f; font-weight: 600;">Historial por Cliente</h5>
            <form method="GET" class="d-flex gap-2 mb-4">
                <select name="filtrar_cliente" class="form-select" required>
                    <option value="">Seleccione un cliente...</option>
                    <?php
                    $c_res = $conn->query("SELECT id_cliente, nombre, apellido FROM cliente");
                    while($c = $c_res->fetch_assoc()) {
                        $sel = (isset($_GET['filtrar_cliente']) && $_GET['filtrar_cliente'] == $c['id_cliente']) ? 'selected' : '';
                        echo "<option value='{$c['id_cliente']}' $sel>{$c['nombre']} {$c['apellido']}</option>";
                    }
                    ?>
                </select>
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </form>

            <?php
            if (isset($_GET['filtrar_cliente']) && !empty($_GET['filtrar_cliente'])) {
                $id_c = intval($_GET['filtrar_cliente']);
                $q3 = "SELECT id_pedido, fecha_pedido, total FROM pedido WHERE id_cliente = $id_c ORDER BY fecha_pedido DESC";
                $res3 = $conn->query($q3);
                if($res3->num_rows > 0) {
                    echo "<div class='table-responsive'><table class='table'><thead><tr><th>Pedido</th><th>Fecha</th><th>Total</th></tr></thead><tbody>";
                    while($row = $res3->fetch_assoc()) {
                        echo "<tr>
                                <td class='text-muted'>#{$row['id_pedido']}</td>
                                <td>{$row['fecha_pedido']}</td>
                                <td><strong>$" . number_format($row['total'], 2) . "</strong></td>
                              </tr>";
                    }
                    echo "</tbody></table></div>";
                } else {
                    echo "<p class='text-muted text-center'>Este cliente no tiene compras.</p>";
                }
            }
            ?>
        </div>
    </div>
</div>

<?php require 'footer.php'; ?>