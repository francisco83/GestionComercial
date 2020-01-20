        <body cz-shortcut-listen="true">
        <table width="100%"> 
			<?php $this->load->view("partial/encabezado_reporte");?>
            <tbody> 
                    <tr> 
                        <td width="100%"> 

						<div class="col-md-12" style="border:1px solid #ddd; margin-bottom: 10px;">
							Código: <?= $filas[0]->codigo_servicio  ?></br>
							Fecha: <?= date("d/m/Y", strtotime($filas[0]->fecha_servicio )); ?></br>
							Cliente: <?= $filas[0]->nombrecliente ?></br>
						</div>


                            <table class="table table-bordered" style="font-size: smaller">
                                <tbody>
                                    <tr>
                                        <td class="padding0"><strong>#</strong></td>
                                        <td class="padding0"><strong>Servicio</strong></td>
										<td class="padding0"><strong>Descripción</strong></td>
										<td class="padding0"><strong>Cantidad</strong></td>
                                        <td class="padding0"><strong>Precio U.</strong></td>                                        
                                        <td class="padding0"><strong>Total</strong></td>
                                    </tr>    
									<?php $i=1; $suma=0;?>     									                                           
                                    <?php foreach ($filas as $fila): ?>
                                    <tr>
                                        <td class="r"><?= $i ?></td> 
                                        <td><?= $fila->nombre ?></td>
										<td><?= $fila->descripcion?></td>	
										<td class="r"><?= $fila->cantidad ?></td>
                                        <td class="r"><?= $fila->precio ?></td>
                                        <td class="r"><?= $fila->precio * $fila->cantidad ?></td>									
                                    </tr>
									<?php 
										$i++; 
										$suma = $suma + ($fila->precio * $fila->cantidad);
									?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
							<div class = "pull-right">
							<?php echo "Subtotal: ".$suma?></br>
							<?php echo "Impuesto: 0"?></br>
							<?php echo "TOTAL: ".$suma?></br>
							</div>
                        </td> 
                    </tr>
                </tbody> 
        </table>
    </body>
