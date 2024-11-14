<div class="col-xs-12">
    <table class="table  table-striped">
        <thead class="table-dark">
            <tr>
                <td width: 100%>Producto</td>
                <td width: 100%>Precio</td>
                <td width: 100%></td>

            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $detalle) { ?>
                <tr>
                    <td><?php echo $detalle['nombreproducto'] ?></td>
                    <td><?php echo "$" . number_format($detalle['valorventaproducto'], 0, ",", ".") ?></td>
                    <td>

                        <button type="button" onclick="agregar_productos_x_categoria( <?php echo $detalle['codigointernoproducto'] ?>)" class="btn btn-success btn-icon">
                            <!-- Download SVG icon from http://tabler-icons.io/i/shopping-cart -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <circle cx="6" cy="19" r="2" />
                                <circle cx="17" cy="19" r="2" />
                                <path d="M17 17h-11v-14h-2" />
                                <path d="M6 5l14 1l-1 7h-13" />
                            </svg>
                        </button>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>