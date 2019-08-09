        <body cz-shortcut-listen="true">
        <table border="0" width="100%"> 
            <thead> 
                <tr> 
                <th style="width:100%">
						<div class="row">                                                    <div class="col-print-2 pull-right">
									<strong>Folio Nº 197</strong>
								</div>
						</div>
						<div>
							<div class="row">
								<div class="col-print-4">
									<img src="https://image.freepik.com/psd-gratis/logo-corporativo-plantilla-vector_63-2549.jpg" class="img-responsive" />
								</div>
								<div class="col-print-6">
									<strong>Decreto 859/07-PEN</strong><br />
									<strong>Autorizada provisoriamente según lo previsto en ley Nº 24521, mediante el decreto</strong><br />
									<strong>PEN Nº 859/07 de fecha 4 de Julio de 2007</strong>
								</div>
							</div>    
						</div>  
                </th> 
            </tr> 
            <tr> 
                <th><hr style="color:#000080"/></th> 
            </tr> 
            </thead> 
            <tbody> 
                    <tr> 
                        <td width="100%"> 

						Código: <?= $filas[0]->codigo_servicio  ?></br>
						Fecha: <?= $filas[0]->fecha_servicio  ?></br>
						Cliente: <?= $filas[0]->nombrecliente ?></br>

                            <table class="table table-bordered" style="font-size: smaller">
                                <tbody>
                                    <tr>
                                        <td class="padding0"><strong>#</strong></td>
                                        <td class="padding0"><strong>Servicio</strong></td>
                                        <td class="padding0"><strong>Precio</strong></td>
                                        <td class="padding0"><strong>Cantidad</strong></td>
                                        <td class="padding0"><strong>Total</strong></td>
                                        <td class="padding0"><strong>Descripción</strong></td>
                                    </tr>    
									<?php $i=1;?>     									                                           
                                    <?php foreach ($filas as $fila): ?>
                                    <tr>
                                        <td><?= $i ?></td> 
                                        <td><?= $fila->nombre ?></td>
                                        <td><?= $fila->precio ?></td>
                                        <td><?= $fila->cantidad ?></td>
                                        <td><?= $fila->precio * $fila->cantidad ?></td>
                                        <td><?= $fila->descripcion?></td>										
                                    </tr>
									<?php $i++; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </td> 
                    </tr>
                </tbody> 
                <!-- <tfoot> 
                    <tr> 
                        <th style="width:100%">
                            <div class="row">
                                    <div class="col-xs-offset-9 col-print-2">
                                        <strong>Folio Nº 197</strong>
                                    </div>
                            </div>
                             <div>
                                <div class="row">
                                    <div class="col-print-6">
                                        <img src="https://www.google.com.ar/images/branding/googlelogo/2x/googlelogo_color_272x92dp.png" class="img-responsive" />
                                    </div>
                                    <div class="col-print-6">
                                        <strong>Decreto 859/07-PEN</strong><br />
                                        <strong>Autorizada provisoriamente según lo previsto en ley Nº 24521, mediante el decreto</strong><br />
                                        <strong>PEN Nº 859/07 de fecha 4 de Julio de 2007</strong>
                                    </div>
                                </div>    
                            </div>   
                        </th> 
                    </tr> 
                </tfoot>  -->
        </table>
    </body>
