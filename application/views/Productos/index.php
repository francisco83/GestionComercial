<?php  $this->load->view("partial/encabezado"); ?>

	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">
                        <h4>Productos</h4>
                        <a class="pull-right btn btn-primary" style="margin-top: -30px" href="<?php echo site_url()?>reportes/productos" target="_blank"><i class="glyphicon glyphicon-print"></i></a>					
						<a class="pull-right btn btn-primary" style="margin-top: -30px" href="<?php echo site_url()?>/productos/createxls"><i class="glyphicon glyphicon-floppy-save"></i></a>					
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
							<div class="col-md-4 col-md-offset-2 pull-right">
								<div class="form-group has-feedback has-feedback-left">				  
									<input type="text" class="form-control" name="busqueda" placeholder="Buscar" />
									<i class="glyphicon glyphicon-search form-control-feedback"></i>
								</div>				
							</div>			
						</div>
						<div class="tbl_grid">
							<table id="tbl" class="table table-bordered table-hover">
								<thead>
									<tr>
										<th>#</th>
										<th>Codigo</th>
										<th>Nombre</th>
										<th>Descripcion</th>
										<th>Precio Venta</th>
										<th>Precio Compra</th>
										<th>Existencia</th>
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
		<button class="btn btn-success" onclick="add()"><i class="glyphicon glyphicon-plus"></i> Nuevo</button>
		<button class="btn btn-warning" onclick="action('edit')"><i class="glyphicon glyphicon-edit"></i> Editar</button>
		<button class="btn btn-danger" onclick="action('delete')"><i class="glyphicon glyphicon-trash"></i> Eliminar</button>			
		<button id="btn_enabled"class="btn btn-secondary" onclick="action('enabled')">Habilitar/Deshabilitar</button>	
	</div>
	
<script>

var valor, pag;
var controller ='productos';
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
			$.each(response.Productos,function(key,item){
				if(item.habilitado=="1")
					habilitado ='SI';		
				else
					habilitado ='NO';
				filas+="<tr>"+
				"<td>"+item.id+"</td>"+
				"<td>"+item.codigo+"</td>"+
				"<td>"+item.nombre+"</td>"+
				"<td>"+item.descripcion+"</td>"+
				"<td class='r'>"+item.precioVenta+"</td>"+
				"<td class='r'>"+item.precioCompra+"</td>"+				
				"<td class='r'>"+item.existencia+"</td>"+	
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
			$('[name="codigo"]').val(data.codigo);
            $('[name="nombre"]').val(data.nombre);
            $('[name="descripcion"]').val(data.descripcion);
			$('[name="precioVenta"]').val(data.precioVenta);
			$('[name="precioCompra"]').val(data.precioCompra);
			$('[name="existencia"]').val(data.existencia);			
            $('#modal_form').modal('show');
            $('.modal-title').text('Editar Productos');
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
                <h3 class="modal-title">Productos</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
					<input type="hidden" value="" name="id"/> 
					<div class="panel-body">
						<div class="form-group">
                            <label for="nombre" class="col-sm-2">Codigo:</label>
                            <div class="col-sm-10">
							    <input class="form-control" name="codigo" required type="text" id="codigo" placeholder="Ingrese el codigo">
                                <span class="help-block"></span>
                            </div>   
						</div>
						<div class="form-group">
                            <label for="nombre" class="col-sm-2">Nombre:</label>
                            <div class="col-sm-10">
							    <input class="form-control" name="nombre" required type="text" id="nombre" placeholder="Ingrese el nombre">
                                <span class="help-block"></span>
                            </div>   
						</div> 
						<div class="form-group">
                            <label for="nombre" class="col-sm-2">Descripcion:</label>
                            <div class="col-sm-10">
							    <input class="form-control" name="descripcion" required type="text" id="descripcion" placeholder="Ingrese la descripcion">
                                <span class="help-block"></span>
                            </div>   
						</div> 	 	
						<div class="form-group">
                            <label for="nombre" class="col-sm-2">Precio Venta:</label>
                            <div class="col-sm-10">
							    <input class="form-control" name="precioVenta" required type="text" id="precioVenta" placeholder="Ingrese el precio de venta">
                                <span class="help-block"></span>
                            </div>   
						</div> 
						<div class="form-group">
                            <label for="nombre" class="col-sm-2">Precio Compra:</label>
                            <div class="col-sm-10">
							    <input class="form-control" name="precioCompra" required type="text" id="precioCompra" placeholder="Ingrese el precio de compra">
                                <span class="help-block"></span>
                            </div>   
						</div>   
						<div class="form-group">
                            <label for="nombre" class="col-sm-2">Existencia:</label>
                            <div class="col-sm-10">
							    <input class="form-control" name="existencia" required type="text" id="existencia" placeholder="Ingrese la existencia">
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