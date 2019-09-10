<body cz-shortcut-listen="true">
        <table border="0" width="100%"> 
			<?php $this->load->view("partial/encabezado_reporte");?>
            <tbody> 
                    <tr> 
                        <td width="100%"> 
                            <table class="table table-bordered" style="font-size: smaller">
                                <tbody>
                                    <tr>
                                        <td class="padding0"><strong>#</strong></td>
                                        <td class="padding0"><strong>Nombre</strong></td>
                                        <td class="padding0"><strong>Apellido</strong></td>
                                        <td class="padding0"><strong>DNI</strong></td>                                        
                                        <td class="padding0"><strong>Teléfono</strong></td>
                                        <td class="padding0"><strong>Email</strong></td>
                                    </tr>                                                    
                                    <?php foreach ($filas as $fila): ?>
                                    <tr>
                                        <td class="r"><?= $fila->id ?></td>
                                        <td><?= $fila->nombre ?></td>
                                        <td><?= $fila->apellido ?></td>
                                        <td class="r"><?= $fila->dni ?></td>                                        
                                        <td class="r"><?= $fila->telefono ?></td>
                                        <td><?= $fila->email ?></td>
                                    </tr>
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
