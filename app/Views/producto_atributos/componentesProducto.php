<?php foreach ($idAtributos as $keyAtributo):
    $nombre = model('atributosProductoModel')->select('nombre')->where('id', $keyAtributo['id_atributo'])->first();
    $numeroComponentes = model('configuracionAtributosProductoModel')->getNumeroComponentes($idProducto, $keyAtributo['id_atributo']);
    $id = model('configuracionAtributosProductoModel')->getId($idProducto, $keyAtributo['id_atributo']);
?>
    <?php if (!empty($nombre)): ?>
        <tr>
            <td><?php echo $nombre['nombre']; ?></td>
            <td>
                <input type="text"
                    class="form-control text-center"
                    value="<?php echo $numeroComponentes[0]['numero_componentes']; ?>"
                    onkeyup="maxComponentes(this.value,<?php echo $id[0]['id']; ?>)"
                    onclick="this.select()"
                    placeholder="1">
            </td>

            <td>

                <button type="button" class="btn btn-outline-danger btn-icon" onclick="eliminaComponente(<?php echo $id[0]['id'];
                                                                                                            ?>,<?php echo $idProducto ?>)"><!-- Download SVG icon from http://tabler-icons.io/i/trash -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <line x1="4" y1="7" x2="20" y2="7" />
                        <line x1="10" y1="11" x2="10" y2="17" />
                        <line x1="14" y1="11" x2="14" y2="17" />
                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                    </svg></button>

            </td>
        </tr>
    <?php else: ?>
        <tr>
            <td colspan="3" class="text-center">No hay atributos disponibles</td>
        </tr>
    <?php endif; ?>
<?php endforeach ?>