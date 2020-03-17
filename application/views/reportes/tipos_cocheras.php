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
                                        <td class="padding0"><strong>Comentario</strong></td>
										<td class="padding0"><strong>Habilitado</strong></td>
                                    </tr>                                                    
                                    <?php foreach ($tipos_cocheras as $tipos_cocheras): ?>
                                    <tr>
                                        <td class="r"><?= $tipos_cocheras->id ?></td>
                                        <td><?= $tipos_cocheras->nombre ?></td>
                                        <td><?= $tipos_cocheras->comentario ?></td>
                                        <td class="c"><?= $tipos_cocheras->habilitado==1?"SI":"NO" ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </td> 
                    </tr>
                </tbody> 
        </table>
    </body>
