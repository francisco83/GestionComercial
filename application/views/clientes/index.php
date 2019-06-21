<?php  $this->load->view("partial/encabezado"); ?>
	<div class="container">
		<div class="row">
			<div class="col-md-4 col-md-offset-2 pull-right">
				<div class="form-group has-feedback has-feedback-left">				  
				    <input type="text" class="form-control" name="busqueda" placeholder="Buscar" />
				    <i class="glyphicon glyphicon-search form-control-feedback"></i>
				</div>				
			</div>			
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h4>Lista de Clientes</h4>
					</div>
					<div class="panel-body">						
						<p>
							<strong>Mostrar por : </strong>
							<select name="cantidad" id="cantidad">
								<option value="5">5</option>
								<option value="10">10</option>
							</select>
						</p>
						<table id="tbclientes" class="table table-bordered table-hover">
							<thead>
								<tr>
									<th>#</th>
									<th>Codigo</th>
									<th>Nombres</th>
									<th>Apellidos</th>
									<th>DNI</th>
									<th>Email</th>
									<th>Celular</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
						<div class="text-center paginacion">							
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
					<a href="<?php echo base_url().'index.php/clientes/agregar/'?>" class="btn btn-success"><i class="glyphicon glyphicon-plus"></i>Agregar</a>										
					<button class="btn btn-warning" onclick="Editar()"><i class='glyphicon glyphicon-edit'></i> Editar</button>
					<button class="btn btn-danger" onclick="Eliminar()"><i class='glyphicon glyphicon-trash'></i> Eliminar</button>
					<button class="btn btn-info" onclick="RegistrarServicio()"><i class='glyphicon glyphicon-tasks'></i> Registrar Servicio</button>
			</div>
		</div>
	</div>

<script>

function Editar(){
	var id = $("#tbclientes tr.selected td:first").html();
	if (id !=  undefined){
		location.href ="<?php echo base_url().'index.php/clientes/editar/'?>"+id;
	}		
}
function Eliminar(){
	var id = $("#tbclientes tr.selected td:first").html();
	if (id !=  undefined){
		location.href ="<?php echo base_url().'index.php/clientes/eliminar/'?>"+id;
	}		
}

function RegistrarServicio(){
	var id = $("#tbclientes tr.selected td:first").html();
	if (id !=  undefined){
		location.href ="<?php echo base_url().'index.php/registrar/index/'?>"+id;
	}		
}

function mostrarDatos(valorBuscar,pagina,cantidad){
	$.ajax({
		url : "clientes/mostrar",
		type: "POST",
		data: {buscar:valorBuscar,nropagina:pagina,cantidad:cantidad},
		dataType:"json",
		success:function(response){
			
			filas = "";
			$.each(response.clientes,function(key,item){
				filas+="<tr>"+
				"<td>"+item.id+"</td>"+
				"<td>"+item.codigo+"</td>"+
				"<td>"+item.nombres+"</td>"+
				"<td>"+item.apellidos+"</td>"+
				"<td>"+item.dni+"</td>"+
				"<td>"+item.Email+"</td>"+
				"<td>"+item.celular+"</td>"+
				"</tr>";
			});
			$("#tbclientes tbody").html(filas);
			cargarPaginado(response, valorBuscar,pagina,cantidad);

			$("#tbclientes tbody tr").click(function(){
				$(this).addClass('selected').siblings().removeClass('selected');    
				var value=$(this).find('td:first').html(); 				
			});

		}
	});
}
// En el onload
$(function() {
	main();
});
</script>

</body>
</html>
