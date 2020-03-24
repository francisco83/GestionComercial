    <body cz-shortcut-listen="true">
		
        <table width="100%"> 
			<?php $this->load->view("partial/encabezado_reporte");?>
            <tbody> 					
                    <tr> 				
                        <td width="100%"> 
						<div class="titulo_reporte">Reporte de Empresas</div>
                            <table class="table table-bordered" style="font-size: smaller">
                                <tbody>
                                    <tr>
                                        <td class="padding0"><strong>#</strong></td>
                                        <td class="padding0"><strong>Nombre</strong></td>
                                        <td class="padding0"><strong>Cuit</strong></td>
                                        <td class="padding0"><strong>Ingreso Bruto</strong></td>
                                        <td class="padding0"><strong>Dirección</strong></td>
                                        <td class="padding0"><strong>Teléfono</strong></td>
                                        <td class="padding0"><strong>Email</strong></td>
                                    </tr>                                                    
                                    <?php foreach ($filas as $fila): ?>
                                    <tr>
                                        <td class="r"><?= $fila->id ?></td>
                                        <td><?= $fila->nombre ?></td>
                                        <td class="r"><?= $fila->cuit ?></td>
                                        <td class="r"><?= $fila->ingresosbrutos ?></td>
                                        <td><?= $fila->direccion ?></td>
                                        <td class="r"><?= $fila->telefono ?></td>
                                        <td><?= $fila->email ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </td> 
                    </tr>
                </tbody> 
        </table>
    </body>
