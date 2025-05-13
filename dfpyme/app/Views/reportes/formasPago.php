<?php foreach ($formasPago as $detalle): ?>
                    <tr>

                        <td>
                            <?php
                            $meses = [
                                "",
                                "enero",
                                "febrero",
                                "marzo",
                                "abril",
                                "mayo",
                                "junio",
                                "julio",
                                "agosto",
                                "septiembre",
                                "octubre",
                                "noviembre",
                                "diciembre"
                            ];
                            $fecha = new DateTime($detalle['fecha']);
                            echo $fecha->format('d') . ' ' . $meses[(int)$fecha->format('m')] . ' ' . $fecha->format('Y');
                            ?>
                        </td>

                        <td>
                            <?php
                            $hora = date("h:i A", strtotime($detalle['hora']));
                            echo $hora;
                            ?>
                        </td>
                        <td>
                            <?php echo $detalle['numero'] ?>
                        </td>
                        <td>
                            <?php echo number_format($detalle['total_documento'], 0, ',', '.'); ?>
                        </td>
                        <td>
                            <?php echo number_format($detalle['efectivo'], 0, ',', '.'); ?>
                        </td>
                        <td>
                            <?php echo number_format($detalle['transferencia'], 0, ',', '.'); ?>
                        </td>
                    </tr>
                <?php endforeach ?>