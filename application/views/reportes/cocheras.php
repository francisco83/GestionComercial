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
									<td class="padding0"><strong>Disponible</strong></td>
									<td class="padding0"><strong>Habilitado</strong></td>
								</tr>                                                    
								<?php foreach ($cocheras as $cocheras): ?>
								<tr>
									<td class="r"><?= $cocheras->id ?></td>
									<td><?= $cocheras->nombre ?></td>
									<td><?= $cocheras->comentario ?></td>
									<td class="c"><?= $cocheras->disponible==1?"SI":"NO" ?></td>
									<td class="c"><?= $cocheras->habilitado==1?"SI":"NO" ?></td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</td> 
				</tr>
			</tbody> 
	</table>
</body>
