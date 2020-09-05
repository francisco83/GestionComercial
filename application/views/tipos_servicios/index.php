<?php  $this->load->view("partial/encabezado"); ?>

<?php if(isset($this->permisos )){
	$permisos = (array_column($this->permisos, 'accion'));
}?>

	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h4>Tipos de servicios</h4>
						<?php if(verifPermiso('IMPRIMIR_ALL', $permisos)){echo'<a class="pull-right btn btn-primary" style="margin-top: -30px" data-toggle="tooltip" title="Imprimir" href="'.site_url().'reportes/tipos_servicios" target="_blank"><i class="glyphicon glyphicon-print"></i></a>';}?>					
						<?php if(verifPermiso('EXPORTAR_ALL', $permisos)){echo'<a class="pull-right btn btn-primary" style="margin-top: -30px" data-toggle="tooltip" title="Excel" href="'.site_url().'tipos_servicios/createxls"><i class="glyphicon glyphicon-floppy-save"></i></a>';}?>										
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
										<th>Descripción</th>
										<th>Precio</th>
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
		<?php if(verifPermiso('VERHISTORICO', $permisos)){echo '<a class="btn btn-info"  href="javascript:verHistorico()">Histórico Precios</a>';}?>
		<?php if(verifPermiso('HABILITAR', $permisos)){echo '<button id="btn_enabled"class="btn btn-secondary" onclick="action(\'enabled\')">Habilitar/Deshabilitar</button>';}?>	
	</div>
	

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
				<tbody id="tbodyservicios">  									
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

<script>

var valor, pag;
var controller ='tipos_servicios';
var Site="<?php echo site_url()?>"

function reload_table(){
	mostrarDatos(valor,pag,$("#cantidad").val());	
};

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
			$.each(response.tipos_servicios,function(key,item){
				if(item.habilitado=="1")
					habilitado ='SI';		
				else
					habilitado ='NO';
				filas+="<tr>"+
				"<td class='r'>"+item.id+"</td>"+
				"<td>"+item.nombre+"</td>"+
				"<td>"+item.descripcion+"</td>"+
				"<td class='r'>"+item.precio+"</td>"+
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
    $('.modal-title').text('Agregar Tipo de servicio');
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
            $('[name="descripcion"]').val(data.descripcion);
            $('[name="precio"]').val(data.precio);
            $('#modal_form').modal('show');
            $('.modal-title').text('Editar Tipo de Servicio');
			$('.modal-backdrop').remove();
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}



function verHistorico()
{
	var IdServicio = $("#tbl tr.selected td:first").html();
	var i=1;
	var detallehistorico="";
	

	detallehistorico+="<tr>";
	detallehistorico+="<th class='r padding0'><strong>#</strong></th>";
	detallehistorico+="<th class='padding0'><strong>Fecha</strong></th>";
	detallehistorico+="<th class='padding0'><strong>Anterior</strong></th>";
	detallehistorico+="<th class='padding0'><strong>Nuevo</strong></th>";
	detallehistorico+="</tr>"; 
	
	//Recupero los pagos			 
	$.ajax({
		url : "<?php echo site_url('tipos_servicios/verdetallehistorico')?>",
		type: "POST",
		dataType:"json",
		data: {IdServicio: IdServicio},
		success:function(response){						
			$.each(response,function(key,item){
				detallehistorico+="<tr>";					
				detallehistorico+="<td class='r'>"+i+"</td>"; 
				detallehistorico+="<td>"+item.fecha+"</td>";				
				detallehistorico+="<td class='r'>"+parseFloat(item.precioanterior)+"</td>";									
				detallehistorico+="<td class='r'>"+parseFloat(item.precionuevo)+"</td>";																										
				detallehistorico+="</tr>"
				i++;
			});				
			$('#tbodyservicios').html(detallehistorico);
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
                <h3 class="modal-title">Tipo de Servicio</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
					<input type="hidden" value="" name="id"/> 
					<div class="panel-body">
						<div class="form-group">
                            <label for="nombre" class="col-sm-2">Nombre:</label>
                            <div class="col-sm-10">
							    <input class="form-control" name="nombre" required type="text" id="nombre" placeholder="Ingrese el nombre">
                                <span class="help-block"></span>
                            </div>   
						</div> 		
						<div class="form-group">
                            <label for="descripcion" class="col-sm-2">Descripción:</label>
                            <div class="col-sm-10">
							    <input class="form-control" id="descripcion" name="descripcion" placeholder="Ingrese la descripción" class="form-control">
                                <span class="help-block"></span>
                            </div>   
						</div>
						<div class="form-group">
                            <label for="precio" class="col-sm-2">Precio:</label>
                            <div class="col-sm-10">
							    <input class="form-control" id="precio" name="precio" placeholder="Ingrese el precio" class="form-control">
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
