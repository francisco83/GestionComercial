<?php  $this->load->view("partial/encabezado"); ?>

	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">
                        <h4>Empresas</h4>
                        <a class="pull-right btn btn-primary" style="margin-top: -30px" href="<?php echo site_url()?>reportes/empresas" target="_blank"><i class="glyphicon glyphicon-print"></i></a>					
						<a class="pull-right btn btn-primary" style="margin-top: -30px" href="<?php echo site_url()?>/empresas/createxls"><i class="glyphicon glyphicon-floppy-save"></i></a>					
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
										<th>CUIT</th>
										<th>Ingreso Bruto</th>
										<th>Dirección</th>
										<th>Telefono</th>
										<th>Email</th>
										<th>Usuario ADMIN</th>
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
		<button class="btn btn-success" onclick="add()"><i class="glyphicon glyphicon-plus"></i></button>
		<button class="btn btn-warning" onclick="action('edit')"><i class="glyphicon glyphicon-edit"></i></button>
		<button class="btn btn-danger" onclick="action('delete')"><i class="glyphicon glyphicon-trash"></i></button>	
		<button id="btn_enabled"class="btn btn-secondary" onclick="action('enabled')">Habilitar/Deshabilitar</button>	
	</div>
	
<script>

var valor, pag;
var controller ='empresas';
var Site="<?php echo site_url()?>";


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
			$.each(response.Empresas,function(key,item){
				if(item.habilitado=="1")
					habilitado ='SI';		
				else
					habilitado ='NO';
				filas+="<tr>"+
				"<td>"+item.id+"</td>"+
				"<td>"+item.nombre+"</td>"+
				"<td>"+item.cuit+"</td>"+
				"<td>"+item.ingresosbrutos+"</td>"+
				"<td>"+item.direccion+"</td>"+
				"<td>"+item.telefono+"</td>"+
				"<td>"+item.email+"</td>"+
				"<td>"+item.username+"</td>"+
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
    $('.modal-title').text('Agregar empresa');
	$('.modal-backdrop').remove();
	cargar_usuarios(0);
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
            $('[name="cuit"]').val(data.cuit);
            $('[name="ingresosbrutos"]').val(data.ingresosbrutos);
            $('[name="direccion"]').val(data.direccion);
			$('[name="telefono"]').val(data.telefono);
            $('[name="email"]').val(data.email);		
			$('[name="user_id"]').val(data.user_id);	
            $('#modal_form').modal('show');
            $('.modal-title').text('Editar Empresa');
			$('.modal-backdrop').remove();
			cargar_usuarios(data.user_id);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}


function cargar_usuarios(id){

var combo_users='';

//Combo de categorias			 
$.ajax({		
	url : "<?php echo site_url('Users/get_all_array');?>",
	type: "POST",
	dataType:"json",
	success:function(response){
		combo_users = "<option value='-1'>Seleccione un usuario...</option>";	
		$.each(response,function(key,item){		
			if(id != 0 && item.id == id){
				combo_users+="<option value='"+item.id+"' selected>"+item.username+"</option>";					
			}
			else{
				combo_users+="<option value='"+item.id+"'>"+item.username+"</option>";
			}
		});		
		$("#user_id").html(combo_users);
	},
	error: function (jqXHR, textStatus, errorThrown)
	{
		$.notify({
				title: '<strong>Atención!</strong>',
				message: 'Se produjo un error al cargar los usuarios.'
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
                <h3 class="modal-title"></h3>
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
                            <label class="col-sm-2">CUIT:</label>
                            <div class="col-sm-10">
							    <input class="form-control" name="cuit" required type="text" id="cuit" placeholder="Ingrese el CUIT">
                                <span class="help-block"></span>
                            </div>   
						</div> 	 	
						<div class="form-group">
                            <label class="col-sm-2">Ingreso Brutos:</label>
                            <div class="col-sm-10">
							    <input class="form-control" name="ingresosbrutos" required type="text" id="ingresosbrutos" placeholder="Ingrese el ingreso bruto">
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
						<div class="form-group">
                            <label class="col-sm-2">Usuario Admin:</label>
                            <div class="col-sm-10">
							<select  class="form-control" name="user_id" id="user_id">
							</select>							    
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
