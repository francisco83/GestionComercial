<?php  $this->load->view("partial/encabezado"); ?>

<?php if(isset($this->permisos )){
	$permisos = (array_column($this->permisos, 'accion'));
}?>

	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h4>Clientes</h4>						
                        <?php if(verifPermiso('IMPRIMIR_ALL', $permisos)){echo'<a class="pull-right btn btn-primary" style="margin-top: -30px" data-toggle="tooltip" title="Imprimir" href="'.site_url().'reportes/clientes" target="_blank"><i class="glyphicon glyphicon-print"></i></a>';}?>					
						<?php if(verifPermiso('EXPORTAR_ALL', $permisos)){echo'<a class="pull-right btn btn-primary" style="margin-top: -30px" data-toggle="tooltip" title="Excel" href="'.site_url().'clientes/createxls"><i class="glyphicon glyphicon-floppy-save"></i></a>';}?>					
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
										<th>Apellido</th>										
										<th>DNI/CUIT</th>
										<th>Email</th>
										<th>Telefono</th>
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
		<?php if(verifPermiso('NUEVO', $permisos)){echo '<button class="btn btn-success" onclick="add()" data-toggle="tooltip" title="Nuevo"><i class="glyphicon glyphicon-plus"></i></button>';} ?>
		<?php if(verifPermiso('EDITAR', $permisos)){echo '<button class="btn btn-warning" onclick="action(\'edit\')" data-toggle="tooltip" title="Editar"><i class="glyphicon glyphicon-edit"></i></button>';} ?>
		<?php if(verifPermiso('BORRAR', $permisos)){echo '<button class="btn btn-danger" onclick="action(\'delete\')" data-toggle="tooltip" title="Eliminar"><i class="glyphicon glyphicon-trash"></i></button>';} ?>	
		<?php if(verifPermiso('REGISTRAR_SERVICIO', $permisos)){echo '<button class="btn btn-info" onclick="Accion(\'registrar\')"><i class="glyphicon glyphicon-tasks"></i> Reg. Servicio</button>';} ?>
		<?php if(verifPermiso('CTACTE', $permisos)){echo '<button class="btn btn-primary" onclick="Accion(\'ctacte\')"><i class="glyphicon glyphicon-briefcase"></i> Cta. Cte.</button>';} ?>
		<?php if(verifPermiso('HABILITAR', $permisos)){echo '<button id="btn_enabled"class="btn btn-secondary" onclick="action(\'enabled\')">Habilitar/Deshabilitar</button>';} ?>
	</div>
	
<script>

var valor, pag;
var controller ='clientes';
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
			$.each(response.Clientes,function(key,item){
				if(item.habilitado=="1")
					habilitado ='SI';		
				else
					habilitado ='NO';
				filas+="<tr>"+
				"<td>"+item.id+"</td>"+
				"<td>"+item.nombre+"</td>"+
				"<td>"+item.apellido+"</td>"+
				"<td class='r'>"+item.dni+"</td>"+
				"<td>"+item.email+"</td>"+
				"<td class='r'>"+item.telefono+"</td>"+
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
    $('.modal-title').text('Agregar Clientes');
	$('.modal-backdrop').remove();
	cargar_provincias(0);
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
			$('[name="apellido"]').val(data.apellido);
            $('[name="nombre"]').val(data.nombre);
			$('[name="dni"]').val(data.dni);
			$('[name="email"]').val(data.email);
			$('[name="telefono"]').val(data.telefono);			
			$('[name="direccion"]').val(data.direccion);			
            $('#modal_form').modal('show');
            $('.modal-title').text('Editar Cliente');
			$('.modal-backdrop').remove();
			cargar_provincias(data.provinciaId);
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


function Accion(tipo){
	var id = $("#tbl tr.selected td:first").html();
	if (id !=  undefined){
		switch(tipo){
			case "registrar":
				location.href ="<?php echo base_url().'registrar/index/'?>"+id;
				break;
			case "ctacte":
				location.href ="<?php echo base_url().'Clientes/ctacte/'?>"+id;
				break;
			default:
			 	break;
		}
	}	
	else{
		$.notify({
                   title: '<strong>Atención!</strong>',
                   message: 'Seleccione una fila.'
               },{
                   type: 'warning'
               });
	}	
}

</script>


<div class="modal" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Cliente</h3>
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
                            <label class="col-sm-2">Apellido:</label>
                            <div class="col-sm-10">
							    <input class="form-control" name="apellido" required type="text" id="apellido" placeholder="Ingrese el apellido">
                                <span class="help-block"></span>
                            </div>   
						</div> 	 	
						<div class="form-group">
                            <label class="col-sm-2">DNI/CUIT:</label>
                            <div class="col-sm-10">
							    <input class="form-control only_number" name="dni" required type="text" id="dni" placeholder="Ingrese el DNI">
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
						<div class="form-group">
                            <label class="col-sm-2">Teléfono:</label>
                            <div class="col-sm-10">
							    <input class="form-control" name="telefono" required type="text" id="telefono" placeholder="Ingrese el teléfono">
                                <span class="help-block"></span>
                            </div>   
						</div> 	 
						<div class="form-group">
                            <label class="col-sm-2">Provincia:</label>							
							<div class="col-sm-10">
							<select  class="form-control" name="provinciaId" id="provinciaId">
							</select>							    
                                <span class="help-block"></span>
                            </div> 
						</div> 	
						<div class="form-group">
                            <label class="col-sm-2">Dirección:</label>
                            <div class="col-sm-10">
							    <input class="form-control" name="direccion" required type="text" id="direccion" placeholder="Ingrese una dirección">
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


</body>
</html>
