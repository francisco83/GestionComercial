<?php  $this->load->view("partial/encabezado"); ?>
<div class="container">
	<div class="row">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h4>Reportes de Productos</h4>
			</div>					
			<div class="panel-body">

				<form action="" id="form_insert">

					<div class="row">
						<div class="col-md-3 col-xs-3">
							<div class="form-group">
									<label for="fecha">Categoría de productos:</label>
									<select  class="form-control" name="tipo_categoria_id" id="tipo_categoria_id">
									</select>	
							</div>  						
						</div>
						<div class="col-md-2 col-xs-3">
							<div class="form-group">
								<label></label>
								<a onclick="filtrar($('#tipo_categoria_id').val())" class="form-control btn btn-info"><i class="glyphicon glyphicon-search"></i>Filtrar</a>												
							</div>
						</div>	
						<div class="col-md-1 col-xs-3">
							<div class="form-group">
								<label></label>
								<a onclick="filtrar_print($('#tipo_categoria_id').val())" class="form-control btn btn-primary"><i class="glyphicon glyphicon-print"></i></a>												
							</div>
						</div>	
						<div class="col-md-1 col-xs-3">
							<div class="form-group">
								<label></label>
								<a onclick="filtrar_excel($('#tipo_categoria_id').val())" class="form-control btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i></a>												
							</div>
						</div>									
					</div>	
					<div class="row">
						<div class="tbl_grid">
							<table id="tbl" class="table table-bordered table-hover">
								<thead>
									<tr>
										<th>#</th>
										<th>Id</th>
										<th>Código</th>
										<th>Nombre</th>										
										<th>Descripcion</th>
										<th>Categoria</th>
										<th>Precio Venta</th>
										<th>Precio Compra</th>
										<th>Existencia</th>
										<th>Habilitado</th>
									</tr>
								</thead>
								<tbody id="detalle">
								</tbody>
							</table>
						</div>
					</div>


			</div><!-- fin pbody -->
			
			</form>

			</div>

			</div>
		</div>
	</div>
</div>

<script>
	$(function() {	
		//cargar las categorias
		cargar_categorias(0);
	});


function cargar_categorias(id){
	console.log("valor",id);
	var combo_categorias='';

	//Combo de categorias			 
	$.ajax({		
		url : "<?php echo site_url('Categorias_Productos/get_all_array');?>",
		type: "POST",
		dataType:"json",
		success:function(response){
			combo_categorias = "<option value='-1'>Todas...</option>";	
			$.each(response,function(key,item){
				if(id != 0 && item.id == id){
					combo_categorias+="<option value='"+item.id+"' selected>"+item.nombre+"</option>";					
				}
				else{
					combo_categorias+="<option value='"+item.id+"'>"+item.nombre+"</option>";
				}
			});		
			$("#tipo_categoria_id").html(combo_categorias);
		},
		error: function (jqXHR, textStatus, errorThrown)
		{
			$.notify({
					title: '<strong>Atención!</strong>',
					message: 'Se produjo un error al cargar las categorias'
				},{
					type: 'danger'
				});
		}
	});
}


	function filtrar(categoria){
	var i=1;		
	$('#detalle').html('');
	$.ajax({
		url : "../productos/productosXcategorias",
		type: "POST",
		data: {categoria:categoria},
		dataType:"json",
		success:function(response){			
			filas = "";
			$.each(response,function(key,item){
				 filas+="<tr>"+
				 "<td class='r'>"+i+"</td>"+
				 "<td class='r'>"+item.id+"</td>"+
				 "<td class='r'>"+item.codigo+"</td>"+
				 "<td>"+item.nombre+"</td>"+
				 "<td>"+item.descripcion+"</td>"+
				 "<td>"+item.categoria+"</td>"+				 
				 "<td class='r'>"+item.precioVenta+"</td>"+				 
				 "<td class='r'>"+item.precioCompra+"</td>"+				 
				 "<td class='r'>"+item.existencia+"</td>"+				 
				 "<td class='c'>"+(item.habilitado==1?"SI":"NO")+"</td>"+				 
				 "</tr>";
				 i++;				 
			});

			$('#detalle').append(filas);
		}
	});
	}
	function filtrar_print(categoria){
		location.href = 'productosXCategoria?categoria='+categoria;
	}
	function filtrar_excel(categoria){
		location.href = '../Productos/createXLSxCategoria?categoria='+categoria;

	}

</script>
