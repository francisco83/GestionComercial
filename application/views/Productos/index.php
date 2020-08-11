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
										<th>Código</th>
										<th>Nombre</th>
										<th>Descripción</th>
										<th>Categoria</th>
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
		<button class="btn btn-success" onclick="add()"><i class="glyphicon glyphicon-plus"></i></button>
		<button class="btn btn-warning" onclick="action('edit')"><i class="glyphicon glyphicon-edit"></i></button>
		<button class="btn btn-danger" onclick="action('delete')"><i class="glyphicon glyphicon-trash"></i></button>			
		<a class='btn btn-info'  href='javascript:verHistorico($("#tbl tr.selected td:first").html())'><i class=''></i>Histórico Precios</a>

		<button id="btn_enabled"class="btn btn-secondary" onclick="action('enabled')">Habilitar/Deshabilitar</button>	
	</div>
	
<script>

var valor, pag;
var controller ='productos';
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
				"<td>"+item.categoria+"</td>"+
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
    $('.modal-title').text('Agregar Productos');
	$('.modal-backdrop').remove();
	cargar_categorias(0);
}


function cargar_categorias(id){
	
	var combo_categorias='';

	//Combo de categorias			 
	$.ajax({		
		url : "<?php echo site_url('Categorias_Productos/get_all_array');?>",
		type: "POST",
		dataType:"json",
		success:function(response){
			combo_categorias = "<option value='-1'>Seleccione una categoria...</option>";	
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
            $('[name="tipo_categoria_id"]').val(data.tipo_categoria_id);
			$('[name="precioVenta"]').val(data.precioVenta);
			$('[name="precioCompra"]').val(data.precioCompra);
			$('[name="existencia"]').val(data.existencia);			
            $('#modal_form').modal('show');
            $('.modal-title').text('Editar Productos');
			$('.modal-backdrop').remove();
			cargar_categorias(data.tipo_categoria_id);
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



function verHistorico(IdProducto)
{
	var i=1;
	var detallehistorico="";
	

	detallehistorico+="<tr>";
	detallehistorico+="<th class='r padding0'><strong>#</strong></th>";
	detallehistorico+="<th class='padding0'><strong>Fecha</strong></th>";
	detallehistorico+="<th class='padding0'><strong>Venta Anterior</strong></th>";
	detallehistorico+="<th class='padding0'><strong>Venta Nuevo</strong></th>";
	detallehistorico+="<th class='padding0'><strong>Compra Anterior</strong></th>";
	detallehistorico+="<th class='padding0'><strong>Compra Nuevo</strong></th>";
	detallehistorico+="</tr>"; 
	
	//Recupero los pagos			 
	$.ajax({
		url : "<?php echo site_url('Productos/verdetallehistorico')?>",
		type: "POST",
		dataType:"json",
		data: {IdProducto: IdProducto},
		success:function(response){						
			$.each(response,function(key,item){
				detallehistorico+="<tr>";					
				detallehistorico+="<td class='r'>"+i+"</td>"; 
				detallehistorico+="<td>"+item.fecha+"</td>";				
				detallehistorico+="<td class='r'>"+parseFloat(item.precioventaanterior)+"</td>";									
				detallehistorico+="<td class='r'>"+parseFloat(item.precioventanuevo)+"</td>";									
				detallehistorico+="<td class='r'>"+parseFloat(item.preciocompraanterior)+"</td>";									
				detallehistorico+="<td class='r'>"+parseFloat(item.preciocompranuevo)+"</td>";																	
				detallehistorico+="</tr>"
				i++;
			});				
			$('#tbodypagos').html(detallehistorico);
		}
	});

	$('.help-block').empty();
    $('#modal_historico').modal('show'); 
    $('.modal-title').text('Histórico de precio');
	$('.modal-backdrop').remove();
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
                            <label class="col-sm-2">Codigo:</label>
                            <div class="col-sm-10">
							    <input class="form-control" name="codigo" required type="text" id="codigo" placeholder="Ingrese el codigo">
                                <span class="help-block"></span>
                            </div>   
						</div>
						<div class="form-group">
                            <label class="col-sm-2">Nombre:</label>
                            <div class="col-sm-10">
							    <input class="form-control" name="nombre" required type="text" id="nombre" placeholder="Ingrese el nombre">
                                <span class="help-block"></span>
                            </div>   
						</div> 
						<div class="form-group">
                            <label class="col-sm-2">Descripcion:</label>
                            <div class="col-sm-10">
							    <input class="form-control" name="descripcion" required type="text" id="descripcion" placeholder="Ingrese la descripcion">
                                <span class="help-block"></span>
                            </div>   
						</div> 	 	
						<div class="form-group">
                            <label class="col-sm-2">Categoria:</label>
                            <div class="col-sm-10">
							<select  class="form-control" name="tipo_categoria_id" id="tipo_categoria_id">
							</select>							    
                                <span class="help-block"></span>
                            </div>   
						</div> 
						<div class="form-group">
                            <label class="col-sm-2">Precio Venta:</label>
                            <div class="col-sm-10">
							    <input class="form-control" name="precioVenta" required type="number" id="precioVenta" placeholder="Ingrese el precio de venta">
                                <span class="help-block"></span>
                            </div>   
						</div> 
						<div class="form-group">
                            <label class="col-sm-2">Precio Compra:</label>
                            <div class="col-sm-10">
							    <input class="form-control" name="precioCompra" required type="number" id="precioCompra" placeholder="Ingrese el precio de compra">
                                <span class="help-block"></span>
                            </div>   
						</div>   
						<div class="form-group">
                            <label  class="col-sm-2">Existencia:</label>
                            <div class="col-sm-10">
							    <input class="form-control" name="existencia" required type="number" id="existencia" placeholder="Ingrese la existencia">
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


<!-- Bootstrap modal -->
<div class="modal" id="modal_historico" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Historico de Precios de Productos</h3>
            </div>
            <div class="modal-body form tableFixHead">
			<table class="table table-bordered" style="font-size: smaller">
				<tbody id="tbodypagos">  									
				</tbody>
			</table>

            </div>
            <div class="modal-footer">                
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->


</body>
</html>
