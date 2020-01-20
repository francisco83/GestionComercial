        <body cz-shortcut-listen="true">
        <table width="100%"> 
			<?php $this->load->view("partial/encabezado_reporte");?>
            <tbody> 
                    <tr> 
                        <td width="100%"> 
                            <table class="table table-bordered" style="font-size: smaller">
                                <tbody>
                                    <tr>
                                        <td class="padding0"><strong>#</strong></td>
                                        <td class="padding0"><strong>Nombre</strong></td>
                                        <td class="padding0"><strong>Descripción</strong></td>
                                    </tr>                                                    
                                    <?php foreach ($categorias_productos as $categorias_productos): ?>
                                    <tr>
                                        <td class="r"><?= $categorias_productos->id ?></td>
                                        <td><?= $categorias_productos->nombre ?></td>
                                        <td><?= $categorias_productos->descripcion ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </td> 
                    </tr>
                </tbody> 
        </table>
    </body>
