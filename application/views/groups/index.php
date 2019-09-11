<?php  $this->load->view("partial/encabezado"); ?>

	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h4>Lista de grupos</h4>
						<a class="pull-right btn btn-primary" style="margin-top: -30px" href="<?php echo site_url()?>reportes/groups" target="_blank"><i class="glyphicon glyphicon-print"></i></a>	
						<a class="pull-right btn btn-primary" style="margin-top: -30px" href="<?php echo site_url()?>/groups/createxls"><i class="glyphicon glyphicon-floppy-save"></i></a>					
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
										<th>Nombre</th>
										<th>Descripción</th>
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
	</div>
	
<script>

var valor, pag;
var controller ='groups';

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
            console.log(response);	
			filas = "";
			$.each(response.groups,function(key,item){
				filas+="<tr>"+
				"<td>"+item.id+"</td>"+
				"<td>"+item.name+"</td>"+
				"<td>"+item.description+"</td>"+
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
    $('.modal-title').text('Agregar Grupo');
	$('.modal-backdrop').remove();
}


function action(option){
	var id = $("#tbl tr.selected td:first").html();

	if (id !=  undefined){
		if (option=="edit")
			edit(id);				
		if (option =="enabled")
			enabled(id);
		if (option =="delete")
			delete_(id);	
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
            $('[name="name"]').val(data.name);
            $('[name="description"]').val(data.description);            
            $('#modal_form').modal('show');
            $('.modal-title').text('Editar Grupo');
			$('.modal-backdrop').remove();
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function save()
{
    $('#btnSave').text('Guardando...'); 
    $('#btnSave').attr('disabled',true); 
    var url,men;

    if(save_method == 'add') {
		url = "<?php echo site_url('"+controller+"/ajax_add')?>";
		men="Se creo el registro correctamente";
    } else {
		url = "<?php echo site_url('"+controller+"/ajax_update')?>";
		men="Se actualizo el registro correctamente";
    }

	var formData = new FormData($('#form')[0]);
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

function delete_(id)
{
    if(confirm('¿Esta seguro que desea eliminar el registro?'))
    {
        $.ajax({
            url : "<?php echo site_url('"+controller+"/ajax_delete')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                $('#modal_form').modal('hide');
				reload_table();
				$.notify({
                   title: '<strong>Correcto!</strong>',
                   message: 'El registro se elimino correctamente.'
               },{
                   type: 'success'
               });
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
				$.notify({
                   title: '<strong>Error!</strong>',
                   message: 'Se produjo un error al eliminar el registro.'
               },{
                   type: 'danger'
               });
            }
        });

    }
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
                            <label for="nombre" class="col-sm-2">Nombre:</label>
                            <div class="col-sm-10">
							    <input class="form-control" name="name" required type="text" id="name" placeholder="Ingrese el nombre">
                                <span class="help-block"></span>
                            </div>   
						</div> 		
						<div class="form-group">
                            <label for="descripcion" class="col-sm-2">Descripción:</label>
                            <div class="col-sm-10">
							    <input class="form-control" id="description" name="description" placeholder="Ingrese la descripción" class="form-control">
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
