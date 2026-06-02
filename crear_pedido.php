<?php
// crear_pedido.php
require 'db.php';
require 'header.php';
?>

<h2 class="section-title">Generar Nuevo Pedido</h2>

<div class="card">
    <form action="procesar_pedido.php" method="POST">
        <div class="mb-4">
            <label class="form-label text-primary fw-bold">1. Cliente</label>
            <select name="id_cliente" class="form-select" required>
                <option value="">-- Seleccione el comprador --</option>
                <?php
                $clientes = $conn->query("SELECT id_cliente, nombre, apellido FROM cliente");
                while($c = $clientes->fetch_assoc()) {
                    echo "<option value='{$c['id_cliente']}'>{$c['nombre']} {$c['apellido']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <label class="form-label text-primary fw-bold mb-0">2. Productos</label>
                <button type="button" class="btn btn-sm btn-light border" onclick="agregarFilaProducto()">+ Añadir otro</button>
            </div>
            
            <div id="contenedor-productos">
                <div class="row g-2 fila-producto mb-2 align-items-center">
                    <div class="col-md-8">
                        <select name="productos[]" class="form-select" required>
                            <option value="">-- Elija el artículo --</option>
                            <?php
                            $prods = $conn->query("SELECT id_producto, nombre_producto, precio, stock FROM producto WHERE stock > 0");
                            while($p = $prods->fetch_assoc()) {
                                echo "<option value='{$p['id_producto']}'>{$p['nombre_producto']} - \${$p['precio']} (Stock: {$p['stock']})</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="cantidades[]" class="form-control" min="1" value="1" placeholder="Cant." required>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-light text-danger border w-100" onclick="if(document.querySelectorAll('.fila-producto').length > 1) this.closest('.fila-producto').remove()">✕</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12"><label class="form-label text-primary fw-bold">3. Envío y Pago</label></div>
            <div class="col-md-6 mb-3">
                <input type="text" name="direccion_envio" class="form-control" required placeholder="Dirección de Destino">
            </div>
            <div class="col-md-3 mb-3">
                <input type="text" name="ciudad" class="form-control" value="Neiva" required placeholder="Ciudad">
            </div>
            <div class="col-md-3 mb-3">
                <input type="text" name="telefono_contacto" class="form-control" required placeholder="Tel. Contacto">
            </div>
            <div class="col-md-12">
                <select name="metodo_pago" class="form-select" required>
                    <option value="">-- Método de Pago --</option>
                    <option value="Efectivo">Efectivo / Contraentrega</option>
                    <option value="Transferencia">Transferencia Bancaria</option>
                    <option value="Tarjeta">Tarjeta de Crédito</option>
                </select>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-3 mt-2" style="font-size: 1.1rem;">Confirmar y Procesar Pedido</button>
    </form>
</div>

<script>
function agregarFilaProducto() {
    const contenedor = document.getElementById('contenedor-productos');
    const primeraFila = document.querySelector('.fila-producto');
    const nuevaFila = primeraFila.cloneNode(true);
    nuevaFila.querySelector('select').value = "";
    nuevaFila.querySelector('input').value = "1";
    contenedor.appendChild(nuevaFila);
}
</script>

<?php require 'footer.php'; ?>