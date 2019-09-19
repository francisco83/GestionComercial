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
                                        <td class="padding0"><strong>Email</strong></td>                                        
                                        <td class="padding0"><strong>Teléfono</strong></td>                                        
                                    </tr>                                                    
                                    <?php foreach ($filas as $fila): ?>
                                    <tr>
                                        <td class="r"><?= $fila->id ?></td>
                                        <td><?= $fila->first_name ?></td>
                                        <td><?= $fila->last_name ?></td>
                                        <td><?= $fila->email ?></td>                                        
                                        <td class="r"><?= $fila->phone ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </td> 
                    </tr>
                </tbody> 
        </table>
    </body>
