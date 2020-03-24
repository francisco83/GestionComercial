<body cz-shortcut-listen="true">
        <table width="100%"> 
			<?php $this->load->view("partial/encabezado_reporte");?>
            <tbody> 
                    <tr> 
                        <td width="100%"> 
						<div class="titulo_reporte">Reporte de Ventas (<?php echo date('d/m/Y', strtotime($_GET['fecha_desde']))." - ".date('d/m/Y', strtotime($_GET['fecha_hasta'])).")"; ?></div>
                            <table class="table table-bordered" style="font-size: smaller">
                                <tbody>
                                    <tr>
                                        <td class="padding0"><strong>#</strong></td>
										<td class="padding0"><strong>Fecha</strong></td>
										<td class="padding0"><strong>Cliente</strong></td>
                                        <td class="padding0"><strong>Total</strong></td>    
                                    </tr>                                                    
                                    <?php foreach ($filas as $fila):?>
                                    <tr>
                                        <td class="r"><?= $fila->id ?></td>
                                        <td><?= $fila->fecha ?></td>
                                        <td><?= $fila->nombre ?></td>
                                        <td><?= $fila->total ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </td> 
                    </tr>
                </tbody> 
        </table>
    </body>
