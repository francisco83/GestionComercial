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
                                        <td class="padding0"><strong>Total Venta</strong></td>    
                                        <td class="padding0"><strong>Total Pago</strong></td>    
                                        <td class="padding0"><strong>Total Vuelto</strong></td>    
                                    </tr>   
									<?php $total = 0; $totalpago = 0; $totalvuelto = 0;?>                                                 
                                    <?php foreach ($filas as $fila):?>
                                    <tr>
                                        <td class="r"><?= $fila->id ?></td>
                                        <td><?= date('d/m/Y', strtotime(($fila->fecha))) ?></td>
                                        <td><?= $fila->nombre ?></td>
                                        <td class='r'><?= $fila->total ?></td>
                                        <td class='r'><?= $fila->monto == null ? 0 : $fila->monto ?></td>
                                        <td class='r'><?= $fila->vuelto == null ? 0 : $fila->vuelto ?></td>
										<? $total = $total + $fila->total ?>
										<? $totalpago = $totalpago + $fila->monto ?>
										<? $totalvuelto = $totalvuelto + $fila->vuelto ?>
                                    </tr>
                                    <?php endforeach; ?>
									<tr>
                                        <td class="padding0"><strong></strong></td>
										<td class="padding0"><strong></strong></td>
										<td class="padding0 r"><strong>TOTAL</strong></td>
                                        <td class="padding0 r"><strong><? echo "$ ".$total ?></strong></td>    
                                        <td class="padding0 r"><strong><? echo "$ ".$totalpago ?></strong></td>    
                                        <td class="padding0 r"><strong><? echo "$ ".$totalvuelto ?></strong></td>    
                                    </tr>   
                                </tbody>
                            </table>
                        </td> 
                    </tr>
                </tbody> 
        </table>
    </body>
