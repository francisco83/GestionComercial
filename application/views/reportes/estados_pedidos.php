        <body cz-shortcut-listen="true">
        <table width="100%"> 
			<?php $this->load->view("partial/encabezado_reporte");?>
            <tbody> 
                    <tr> 
                        <td width="100%"> 
						<div class="titulo_reporte">Reporte de estados de pedidos</div>
                            <table class="table table-bordered" style="font-size: smaller">
                                <tbody>
                                    <tr>
                                        <td class="padding0"><strong>#</strong></td>
                                        <td class="padding0"><strong>Nombre</strong></td>                                        
										<td class="padding0"><strong>Habilitado</strong></td>
                                    </tr>                                                    
                                    <?php foreach ($estados_pedidos as $estados_pedidos): ?>
                                    <tr>
                                        <td class="r"><?= $estados_pedidos->Id ?></td>
                                        <td><?= $estados_pedidos->nombre ?></td>
                                        <td class="c"><?= $estados_pedidos->habilitado==1?"SI":"NO" ?></td>										
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </td> 
                    </tr>
                </tbody> 
        </table>
    </body>
