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
										<td class="padding0"><strong>Código</strong></td>
										<td class="padding0"><strong>Nombre</strong></td>
                                        <td class="padding0"><strong>Descripcion</strong></td>    
                                        <td class="padding0"><strong>Categoria</strong></td>    
                                        <td class="padding0"><strong>Precio Venta</strong></td>                                        
                                        <td class="padding0"><strong>Precio Compra</strong></td>
                                        <td class="padding0"><strong>Existencia</strong></td>
                                    </tr>                                                    
                                    <?php foreach ($filas as $fila): ?>
                                    <tr>
                                        <td class="r"><?= $fila->id ?></td>
                                        <td><?= $fila->codigo ?></td>
                                        <td><?= $fila->nombre ?></td>
                                        <td><?= $fila->descripcion ?></td>
                                        <td><?= $fila->categoria ?></td>
                                        <td class="r"><?= $fila->precioVenta ?></td>                                        
                                        <td class="r"><?= $fila->precioCompra ?></td>
                                        <td class="r"><?= $fila->existencia ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </td> 
                    </tr>
                </tbody> 
        </table>
    </body>
