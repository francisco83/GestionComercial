<?php  $this->load->view("partial/encabezado"); ?>
<style>
	.ui-autocomplete { z-index:2147483647; }
</style>

	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">
                        <h4>Cocheras</h4>
                        <a class="pull-right btn btn-primary" style="margin-top: -30px" href="<?php echo site_url()?>reportes/cocheras" target="_blank"><i class="glyphicon glyphicon-print"></i></a>					
						<a class="pull-right btn btn-primary" style="margin-top: -30px" href="<?php echo site_url()?>/cocheras/createxls"><i class="glyphicon glyphicon-floppy-save"></i></a>					
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
										<th>Comentario</th>
										<th>Tipos Cochera</th>
										<th>Disponible</th>
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
		<button class="btn btn-info" onclick="AsingarCliente()"><i class='glyphicon glyphicon-tasks'></i> Asignar Cliente</button>
		<button id="btn_enabled"class="btn btn-secondary" onclick="action('enabled')">Habilitar/Deshabilitar</button>	
	</div>
	
<script>

var valor, pag;
var controller ='cocheras';
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
			$.each(response.Cocheras,function(key,item){
				if(item.habilitado=="1")
					habilitado ='SI';		
				else
					habilitado ='NO';

				if(item.disponible=="1")
					disponible ='SI';		
				else
					disponible ='NO';

				filas+="<tr>"+
				"<td>"+item.id+"</td>"+				
				"<td id='nombretdcochera'>"+item.nombre+"</td>"+
				"<td>"+item.comentario+"</td>"+
				"<td>"+item.tipo_cochera+"</td>"+			
				"<td class='c'>"+disponible+"</td>"+	
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

	
	$("#disponible").click(function() {  
        if($("#disponible").is(':checked')) {  			
			$("#disponible").attr( 'checked',true );
						
        } else {  			
			$("#disponible").removeAttr('checked');
        }  
    });  



});

function add()
{
    save_method = 'add';
    $('#form')[0].reset(); 
    $('.form-group').removeClass('has-error'); 
	$('.panel-body').removeClass('has-error'); 
    $('.help-block').empty();
    $('#modal_form').modal('show'); 
    $('.modal-title').text('Agregar Cocheras');
	$('.modal-backdrop').remove();
	cargar_categorias(0);
}


function cargar_categorias(id){

	console.log("valor",id);
	var combo_categorias='';

	//Combo de categorias			 
	$.ajax({		
		url : "<?php echo site_url('Tipos_Cocheras/get_all_array');?>",
		type: "POST",
		dataType:"json",
		success:function(response){
			combo_categorias = "<option value='-1'>Seleccione una tipo de cochera...</option>";	
			$.each(response,function(key,item){
				if(id != 0 && item.id == id){
					combo_categorias+="<option value='"+item.id+"' selected>"+item.nombre+"</option>";					
				}
				else{
					combo_categorias+="<option value='"+item.id+"'>"+item.nombre+"</option>";
				}
			});		
			$("#tipo_cochera_id").html(combo_categorias);
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
			
            $('[name="nombre"]').val(data.nombre);
            $('[name="comentario"]').val(data.comentario);
			$('[name="tipo_cochera_id"]').val(data.tipo_cochera_id);
			
			$('[name="disponible"]').val(data.disponible);			
            $('#modal_form').modal('show');
            $('.modal-title').text('Editar Cocheras');
			$('.modal-backdrop').remove();

			if(data.disponible == 0)
				$("#disponible").removeAttr('checked');
			else
				$("#disponible").attr( 'checked',true );

			cargar_categorias(data.tipo_cochera_id);
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



function AsingarCliente(){
	save_method = 'add';
	var id = $("#tbl tr.selected td:first").html();
	if (id !=  undefined){
		
		$('#modal_Asignar').modal('show');
		$('#formAsignar')[0].reset(); 
	    $('.form-group').removeClass('has-error'); 
		$('.panel-body').removeClass('has-error'); 
		$('.help-block').empty();		
		$('.modal-backdrop').remove();
		$('[name="idCochera"]').val($("#tbl tr.selected td").html());
		$('[name="nombrecochera"]').val($("#tbl tr.selected #nombretdcochera").html());

		$("#fechaasignacion").val(hoyFecha());
		$("#combocliente").val('');

		//Combo Cliente
		$("#combocliente").autocomplete({
			
			source: "<?php echo site_url('Clientes/get_autocomplete/?');?>",
			autoFill:true,
			select: function(event, ui){			
				$('#clienteid').val(ui.item.id);
				$("#combocliente").val(ui.item.value);
			},			
		});		
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

function saveAsignar()
{
    $('#btnSave').text('Guardando...'); 
    $('#btnSave').attr('disabled',true); 
    var url,men;

    if(save_method == 'add') {
		url = Site+controller+"/add_asignacion";
		men="Se creo el registro correctamente";
    } else {
		url = Site+controller+"/update_asignacion";
		men="Se actualizo el registro correctamente";
    }

	var formData = new FormData($('#formAsignar')[0]);
    $.ajax({
        url : url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(data)
        {

            if(data.status)
            {
                $('#modal_form').modal('hide');
				reload_table();
				$.notify({
                   title: '<strong>Correcto!</strong>',
                   message: men
               },{
                   type: 'success'
               });

            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
            }
            $('#btnSave').text('Guardar');
            $('#btnSave').attr('disabled',false); 
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
			$.notify({
                   title: '<strong>Error!</strong>',
                   message: 'Se produjo un error al guardar.'
               },{
                   type: 'danger'
               });
            $('#btnSave').text('Guardar'); 
            $('#btnSave').attr('disabled',false); 

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
                <h3 class="modal-title">Cocheras</h3>
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
                            <label class="col-sm-2">Comentarios:</label>
                            <div class="col-sm-10">
							    <input class="form-control" name="comentario" required type="text" id="comentario" placeholder="Ingrese los comentario">
                                <span class="help-block"></span>
                            </div>   
						</div> 	 	
						<div class="form-group">
                            <label class="col-sm-2">Tipo Cochera:</label>
                            <div class="col-sm-10">
							<select  class="form-control" name="tipo_cochera_id" id="tipo_cochera_id">
							</select>							    
                                <span class="help-block"></span>
                            </div>   
						</div>  
						<div class="form-group">
                            <label  class="col-sm-2">Disponible:</label>
                            <div class="col-sm-10">
							    <!-- <input class="form-control" name="disponible" required type="number" id="disponible" placeholder="Ingrese si esta disponible"> -->
								<input type="checkbox" id="disponible" name="disponible" value='1'>
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



<!-- Bootstrap modal Asignar Cliente a Cochera-->
<div class="modal" id="modal_Asignar" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Asignar cliente a cochera</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="formAsignar" class="form-horizontal">
					<input  value="" type="hidden"  name="idCochera"/> 
					<div class="panel-body">
						<div class="form-group">
                            <label class="col-sm-2">Cochera:</label>
                            <div class="col-sm-10">
							    <input class="form-control" name="nombrecochera" required type="text" id="nombrecochera" placeholder="Ingrese el nombre" readonly>
                                <span class="help-block"></span>
                            </div>   
						</div> 
						<div class="form-group">
                            <label class="col-sm-2">Cliente:</label>
                            <div class="col-sm-10">								
									<input type ="text" id="clienteid" name="clienteid" hidden value="">
									<input type="text" class="form-control" id="combocliente" name="cliente" placeholder="Buscar Cliente">									
                            </div>   
						</div> 	 	
						<div class="form-group">
                            <label  class="col-sm-2">Fecha:</label>
                            <div class="col-sm-10">							    
								<input class="form-control" id="fechaasignacion" name="fechaasignacion" required type="date">
                                <span class="help-block"></span>
                            </div>   
						</div> 	
					</div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="saveAsignar()" class="btn btn-primary">Guardar</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->

</body>
</html>
