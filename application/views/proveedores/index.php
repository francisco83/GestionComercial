<?php  $this->load->view("partial/encabezado"); ?>

<?php if(isset($this->permisos )){
	$permisos = (array_column($this->permisos, 'accion'));
}?>

	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h4>Proveedores</h4>
						<?php if(verifPermiso('IMPRIMIR_ALL', $permisos)){echo'<a class="pull-right btn btn-primary" style="margin-top: -30px" data-toggle="tooltip" title="Imprimir" href="'.site_url().'reportes/proveedores" target="_blank"><i class="glyphicon glyphicon-print"></i></a>';}?>					
						<?php if(verifPermiso('EXPORTAR_ALL', $permisos)){echo'<a class="pull-right btn btn-primary" style="margin-top: -30px" data-toggle="tooltip" title="Excel" href="'.site_url().'proveedores/createxls"><i class="glyphicon glyphicon-floppy-save"></i></a>';}?>									
					</div>
					<div class="panel-body">						
						<div class="row">
							<div class="col-md-4">
								<strong>Mostrar por : </strong>
								<select name="cantidad" id="cantidad">
									<option value="5">5</option>
									<option value="10">10</option>
								</select>
							</div>
							<div class="col-md-4 col-xs-12 col-md-offset-2 pull-right">
								<div class="form-group has-feedback has-feedback-left">				  
									<input type="text" class="form-control" name="busqueda" id="busqueda" placeholder="Buscar" />
									<i class="glyphicon glyphicon-search form-control-feedback"></i>
								</div>				
							</div>			
						</div>
						<div class="tbl_grid">
							<table id="tbl" class="table table-bordered table-hover">
								<thead>
									<tr>
										<th>#</th>
										<th>Nombre</th>
										<th>Nombre Contacto</th>
										<th>CUIT</th>
										<th>Dirección</th>
										<th>Teléfono</th>
										<th>Email</th>
										<th>Habilitado</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
						<div class="text-center paginacion">							
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php if(verifPermiso('NUEVO', $permisos)){echo '<button class="btn btn-success" onclick="add()"><i class="glyphicon glyphicon-plus"></i></button>';}?>
		<?php if(verifPermiso('EDITAR', $permisos)){echo '<button class="btn btn-warning" onclick="action(\'edit\')"><i class="glyphicon glyphicon-edit"></i></button>';}?>
		<?php if(verifPermiso('BORRAR', $permisos)){echo '<button class="btn btn-danger" onclick="action(\'delete\')"><i class="glyphicon glyphicon-trash"></i></button>';}?>			
		<?php if(verifPermiso('HABILITAR', $permisos)){echo '<button id="btn_enabled"class="btn btn-secondary" onclick="action(\'enabled\')">Habilitar/Deshabilitar</button>';}?>	
	</div>
	
<script>

var valor, pag;
var controller ='proveedores';
var Site="<?php echo site_url()?>"



function mostrarDatos(valorBuscar,pagina,cantidad){
	valor = valorBuscar;
	pag = pagina;
	cant = cantidad;
	$.ajax({
		url : controller+"/mostrar",
		type: "POST",
		data: {buscar:valorBuscar,nropagina:pagina,cantidad:cantidad},
		dataType:"json",
		success:function(response){		
			filas = "";
			$.each(response.Proveedores,function(key,item){
				if(item.habilitado=="1")
					habilitado ='SI';		
				else
					habilitado ='NO';
				filas+="<tr>"+
				"<td>"+item.id+"</td>"+
				"<td>"+item.nombre+"</td>"+
				"<td>"+item.nombre_contacto+"</td>"+
				"<td class='r'>"+item.cuit+"</td>"+
				"<td>"+item.direccion+"</td>"+
				"<td class='r'>"+item.telefono+"</td>"+
				"<td class='r'>"+item.email+"</td>"+
				"<td class='c'>"+habilitado+"</td>"+
				"</tr>";
			});
			$("#tbl tbody").html(filas);
			cargarPaginado(response, valorBuscar,pagina,cantidad);

			$("#tbl tbody tr").click(function(){
				$(this).addClass('selected').siblings().removeClass('selected');    
				var value=$(this).find('td:first').html(); 		
				var hab = $("#tbl tr.selected td:last").html();
				if (hab=="SI")
					$("#btn_enabled").text("Deshabilitar");		
				else
					$("#btn_enabled").text("Habilitar");		

			});

		}
	});
}

// En el onload
$(function() {
	main();  
	$("#busqueda").focus();
});

function add()
{
    save_method = 'add';
    $('#form')[0].reset(); 
    $('.form-group').removeClass('has-error'); 
	$('.panel-body').removeClass('has-error'); 
    $('.help-block').empty();
    $('#modal_form').modal('show'); 
    $('.modal-title').text('Agregar Proveedores');
	$('.modal-backdrop').remove();
}


function edit(id)
{
    save_method = 'update';
    $('#form')[0].reset(); 
    $('.form-group').removeClass('has-error'); 
	$('.panel-body').removeClass('has-error'); 
    $('.help-block').empty();

    $.ajax({
        url : "<?php echo site_url('/"+controller+"/ajax_edit')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('[name="id"]').val(data.id);
			$('[name="nombre"]').val(data.nombre);
            $('[name="nombre_contacto"]').val(data.nombre_contacto);
			$('[name="cuit"]').val(data.cuit);
			$('[name="direccion"]').val(data.direccion);
			$('[name="telefono"]').val(data.telefono);			
			$('[name="email"]').val(data.email);			
            $('#modal_form').modal('show');
            $('.modal-title').text('Editar Proveedor');
			$('.modal-backdrop').remove();
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
			$.notify({
                   title: '<strong>Atención!</strong>',
                   message: 'Se produjo un error.'
               },{
                   type: 'danger'
               });
        }
    });
}

</script>



<!-- Bootstrap modal -->
<div class="modal" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Servicio</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
					<input type="hidden" value="" name="id"/> 
					<div class="panel-body">
						<div class="form-group">
                            <label class="col-sm-2">Nombre:</label>
                            <div class="col-sm-10">
							    <input class="form-control" name="nombre" required type="text" id="nombre" placeholder="Ingrese el nombre">
                                <span class="help-block"></span>
                            </div>   
						</div>
						<div class="form-group">
                            <label class="col-sm-2">Nombre Contacto:</label>
                            <div class="col-sm-10">
							    <input class="form-control" name="nombre_contacto" required type="text" id="nombre_contacto" placeholder="Ingrese el nombre del contacto">
                                <span class="help-block"></span>
                            </div>   
						</div> 	 	
						<div class="form-group">
                            <label class="col-sm-2">CUIT:</label>
                            <div class="col-sm-10">
							    <input class="form-control" name="cuit" required type="text" id="cuit" placeholder="Ingrese el CUIT">
                                <span class="help-block"></span>
                            </div>   
						</div> 
						<div class="form-group">
                            <label class="col-sm-2">Dirección:</label>
                            <div class="col-sm-10">
							    <input class="form-control" name="direccion" required type="text" id="direccion" placeholder="Ingrese la dirección">
                                <span class="help-block"></span>
                            </div>   
						</div>  
						<div class="form-group">
                            <label class="col-sm-2">Teléfono:</label>
                            <div class="col-sm-10">
							    <input class="form-control" name="telefono" required type="text" id="telefono" placeholder="Ingrese el teléfono">
                                <span class="help-block"></span>
                            </div>   
						</div> 	 
						<div class="form-group">
                            <label class="col-sm-2">Email:</label>
                            <div class="col-sm-10">
							    <input class="form-control" name="email" required type="text" id="email" placeholder="Ingrese el email">
                                <span class="help-block"></span>
                            </div>   
						</div>  	
					</div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Guardar</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->


</body>
</html>
