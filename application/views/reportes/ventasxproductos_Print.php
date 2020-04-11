<body cz-shortcut-listen="true">
        <table width="100%"> 
			<?php $this->load->view("partial/encabezado_reporte");?>
            <tbody> 
                    <tr> 
                        <td width="100%"> 
						<div class="titulo_reporte">Reporte de Ventas por Productos(<?php echo date('d/m/Y', strtotime($_GET['fecha_desde']))." - ".date('d/m/Y', strtotime($_GET['fecha_hasta'])).")"; ?></div>
                            <table class="table table-bordered" style="font-size: smaller">
                                <tbody>
                                    <tr>
										<td class="padding0"><strong>Fecha</strong></td>
										<td class="padding0"><strong>Cod. Producto</strong></td>
                                        <td class="padding0"><strong>Productos</strong></td>    
                                        <td class="padding0"><strong>Cantidad</strong></td>    
                                        <td class="padding0"><strong>Total Venta</strong></td>    
                                        <td class="padding0"><strong>Total Costo</strong></td>  
                                        <td class="padding0"><strong>Ganancia</strong></td>    
                                    </tr>   
									<?php $totalVenta = 0; $totalCompra = 0; $ganancia = 0;?>                                                 
                                    <?php foreach ($filas as $fila):?>
                                    <tr>                                        
                                        <td class='c'><?= date('d/m/Y', strtotime(($fila->fecha))) ?></td>
                                        <td><?= $fila->codigoproducto ?></td>
                                        <td><?= $fila->nombre ?></td>
                                        <td class='r'><?= $fila->cantidad ?></td>
                                        <td class='r'><?= $fila->precioventa ?></td>
                                        <td class='r'><?= $fila->preciocompra ?></td>
                                        <td class='r'><?= $fila->diferencia ?></td>

               							<? $totalVenta = $totalVenta + $fila->precioventa ?>
										<? $totalCompra = $totalCompra + $fila->preciocompra ?>
										<? $ganancia = $ganancia + $fila->diferencia ?>
                                    </tr>
                                    <?php endforeach; ?>
									<tr>
                                        <td class="padding0"><strong></strong></td>
										<td class="padding0"><strong></strong></td>
										<td class="padding0"><strong></strong></td>
										<td class="padding0 r"><strong>TOTAL</strong></td>
                                        <td class="padding0 r"><strong><? echo "$ ".$totalVenta ?></strong></td>    
                                        <td class="padding0 r"><strong><? echo "$ ".$totalCompra ?></strong></td>    
                                        <td class="padding0 r"><strong><? echo "$ ".$ganancia ?></strong></td>    
                                    </tr>   
                                </tbody>
                            </table>
                        </td> 
                    </tr>
                </tbody> 
        </table>
    </body>
