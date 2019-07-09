<?php  $this->load->view("partial/encabezado"); ?>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h4>Lista de servicios</h4>
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
						<table id="tblservicios" class="table table-bordered table-hover">
							<thead>
								<tr>
									<th>#</th>
									<th>Nombre</th>
									<th>Descripcion</th>
									<th>Precio</th>
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
		<button class="btn btn-success" onclick="add_person()"><i class="glyphicon glyphicon-plus"></i> Nuevo</button>
		<button class="btn btn-warning" onclick="Editar_person()"><i class="glyphicon glyphicon-edit"></i> Editar</button>
		<button class="btn btn-danger" onclick="Delete_person()"><i class="glyphicon glyphicon-trash"></i> Eliminar</button>
	</div>
	
<script>


function reload_table(){
	mostrarDatos('',1,5);
};

function mostrarDatos(valorBuscar,pagina,cantidad){
	$.ajax({
		url : "servicios/mostrar",
		type: "POST",
		data: {buscar:valorBuscar,nropagina:pagina,cantidad:cantidad},
		dataType:"json",
		success:function(response){
			
			filas = "";
			$.each(response.servicios,function(key,item){
				filas+="<tr>"+
				"<td>"+item.id+"</td>"+
				"<td>"+item.nombre+"</td>"+
				"<td>"+item.descripcion+"</td>"+
				"<td>"+item.precio+"</td>"+
				"</tr>";
			});
			$("#tblservicios tbody").html(filas);
			cargarPaginado(response, valorBuscar,pagina,cantidad);

			$("#tblservicios tbody tr").click(function(){
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

function add_person()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Agregar servicio'); // Set Title to Bootstrap modal title
	
	$('.modal-backdrop').remove();
}


function Editar_person(){
	var id = $("#tblservicios tr.selected td:first").html();
	if (id !=  undefined){
		edit_person(id);
	}		
}

function Delete_person(){
	var id = $("#tblservicios tr.selected td:first").html();
	if (id !=  undefined){
		delete_person(id);
	}		
}

function edit_person(id)
{

	console.log("editar a ",id);
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string


    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('/servicios/ajax_edit')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('[name="id"]').val(data.id);
            $('[name="nombre"]').val(data.nombre);
            $('[name="descripcion"]').val(data.descripcion);
            $('[name="precio"]').val(data.precio);
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Editar Servicio'); // Set title to Bootstrap modal title
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
    $('#btnSave').text('Guardando...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;


    if(save_method == 'add') {
        url = "<?php echo site_url('servicios/ajax_add')?>";
    } else {
        url = "<?php echo site_url('servicios/ajax_update')?>";
    }

    // ajax adding data to database

	var formData = new FormData($('#form')[0]);
	console.log(formData);
    $.ajax({
        url : url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_form').modal('hide');
                reload_table();
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
            }
            $('#btnSave').text('Guardar'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSave').text('Guardar'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 

        }
    });
}

function delete_person(id)
{
    if(confirm('¿Esta seguro que desea eliminar el registro?'))
    {
        $.ajax({
            url : "<?php echo site_url('servicios/ajax_delete')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                $('#modal_form').modal('hide');
                reload_table();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error eliminando el registro');
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
							<label for="nombre">Nombre:</label>
							<input class="form-control" name="nombre" required type="text" id="nombre" placeholder="Escribe el nombre">
							<span class="help-block"></span>
						</div> 		

						<div class="form-group">
							<label for="descripcion">descripcion:</label>
							<input class="form-control" id="descripcion" name="descripcion" placeholder="Ingrese el/los descripcion" class="form-control">
							<span class="help-block"></span>
						</div>

						<div class="form-group">
							<label for="precio">precio:</label>
							<input class="form-control" id="precio" name="precio" placeholder="Ingrese el precio" class="form-control">
							<span class="help-block"></span>
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
