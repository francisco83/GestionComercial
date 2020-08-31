<?php  $this->load->view("partial/encabezado"); ?>

	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">
                        <h4>Clientes</h4>
                        <a class="pull-right btn btn-primary" style="margin-top: -30px" data-toggle="tooltip" title="Imprimir" href="<?php echo site_url()?>reportes/clientes" target="_blank"><i class="glyphicon glyphicon-print"></i></a>					
						<a class="pull-right btn btn-primary" style="margin-top: -30px" data-toggle="tooltip" title="Excel" href="<?php echo site_url()?>/clientes/createxls"><i class="glyphicon glyphicon-floppy-save"></i></a>					
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
		<button class="btn btn-success" onclick="add()" data-toggle="tooltip" title="Nuevo"><i class="glyphicon glyphicon-plus"></i></button>
		<button class="btn btn-warning" onclick="action('edit')" data-toggle="tooltip" title="Editar"><i class="glyphicon glyphicon-edit"></i></button>
		<button class="btn btn-danger" onclick="action('delete')" data-toggle="tooltip" title="Eliminar"><i class="glyphicon glyphicon-trash"></i></button>	
		<button class="btn btn-info" onclick="Accion('registrar')"><i class='glyphicon glyphicon-tasks'></i> Reg. Servicio</button>
		<button class="btn btn-primary" onclick="Accion('ctacte')"><i class='glyphicon glyphicon-briefcase'></i> Cta. Cte.</button>
		<button id="btn_enabled"class="btn btn-secondary" onclick="action('enabled')">Habilitar/Deshabilitar</button>	
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
                   type: 'info'
               });
	}	
}

</script>

<?php  $this->load->view("partial/cliente_formulario"); ?>

</body>
</html>
