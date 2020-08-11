<?php $this->load->view("partial/encabezado"); ?>

<style>
  .custom-combobox {
    position: relative;
    display: inline-block;
  }
  .custom-combobox-toggle {
    position: absolute;
    top: 0;
    bottom: 0;
    margin-left: -1px;
    padding: 0;
  } 
  .custom-combobox-input {
    margin: 0;
    padding: 5px 10px;
  }
  .fila{
	border: 1px solid lightgray;
	margin-top: 20px;
	padding: 10px;


  }
  </style> 

<div class="container">
<div class="row">
			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h4>Ver Servicios</h4>
					</div>
					
					<div class="panel-body">
					<form action="" id="form_insert">
						<div class="col-md-12">
						<div class="col-md-12">
							<div class="form-group">
									<input type ="text" id="clienteid" name="clienteid" hidden>
									<div class="row">
										<div class="col-md-6 col-xs-12">
											<input type="text" class="form-control" id="combocliente" name="cliente" placeholder="Buscar Cliente">										
										</div>																						
										<div class="col-md-2 col-xs-12">
											<input class="btn btn-primary btn-block" value="Buscar" onclick="Filtrar($('#clienteid').val())"  >	
										</div>
									</div>							
							</div>
						</div>
						</div>

						<div class="col-md-12" id="detalle">
						</div>			

						<!-- <input class="btn btn-primary" type="submit" value="Guardar">		 -->
					</form>
						<p>
							<strong>Mostrar por : </strong>
							<select name="cantidad" id="cantidad">
								<option value="5">5</option>
								<option value="10">10</option>
							</select>
						</p>
						<div class="tbl_grid">
							<table id="tbl" class="table table-bordered table-hover">
								<thead>
									<tr>
										<th>#</th>
										<th>Codigo</th>
										<th>Fecha</th>
										<th>Opciones</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
						<div class="text-center paginacion">							
						</div>

  						<h3>Detalle</h3>
						  <p>
							<strong>Mostrar por : </strong>
							<select name="cantidadDetalle" id="cantidadDetalle">
								<option value="5">5</option>
								<option value="10">10</option>
							</select>
						</p>
						<div class="tbl_grid">
							<table id="tbldetalle" class="table table-bordered table-hover">
								<thead>
									<tr>
										<th>#</th>
										<th>Servicio</th>
										<th>Precio</th>
										<th>Cantidad</th>
										<th>Descripcion</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
						<div class="text-center paginacionDetalle">							
						</div>


					</div><!-- fin pbody -->

					</div>
				</div>
			</div>
	</div>
</div>

	<script src="<?php echo base_url();?>assets/js/combos.js"></script>
	<script>
	
	var i = 1;
	var controller ='registrar';
	var Site="<?php echo site_url()?>"
	
	$(function() {
			//Buscar Cliente
			$( "#combocliente" ).autocomplete({
			source: "<?php echo site_url('Clientes/get_autocomplete/?');?>",
			autoFill:true,
			select: function(event, ui){
				console.log(ui);
				$('#clienteid').val(ui.item.id);
				$("#combocliente").val(ui.item.value);

				Filtrar(ui.item.id);
				$("#tbldetalle tbody").html('');				
			},
			});	

			$(document).keyup(function(e) {     
				if(e.keyCode== 27) {
					$("#combocliente").val(''); 
					$("#tbldetalle tbody").html('');
					$("#tbl tbody").html('');
					$("#combocliente").focus();
				} 
			});

		});

		
	function mostrarDatosCliente(clienteId,valorBuscar,pagina,cantidad){
		console.log("Ingreso a mostrar datos",clienteId, valorBuscar,pagina,cantidad);
	$.ajax({
		url : "../registrar/mostrarXcliente",
		type: "POST",
		data: {clienteId:clienteId,buscar:valorBuscar,nropagina:pagina,cantidad:cantidad},
		dataType:"json",
		success:function(response){
			console.log("response",response);
			
			filas = "";
			$.each(response.cli_servicios,function(key,item){
				filas+="<tr>"+
				"<td>"+item.id+"</td>"+
				"<td>"+item.id+"</td>"+
				"<td>"+StrToFecha(item.fecha)+"</td>"+
				"<td>"+
				//"<a class='btn btn-sm btn-info' onclick='FiltrarDetalle("+item.id+")'><i class='glyphicon glyphicon-tasks'></i></a>"+				
			    " <a class='btn btn-sm btn-warning'  href='<?php echo site_url()?>registrar/editar/"+item.id+"'><i class='glyphicon glyphicon-edit'></i></a>"+
				" <a class='btn btn-sm btn-danger' onclick='javascript:borrar_Servicio("+item.id+")'><i class='glyphicon glyphicon-trash'></i></a>"+				
				" <a class='btn btn-sm btn-primary'  href='<?php echo site_url()?>reportes/ver_registrar/"+item.id+"' target='_blank'><i class='glyphicon glyphicon-print'></i></a>"+
				"</td>"+
				"</tr>";
			});

			$("#tbldetalle tbody").html('');
			$("#tbl tbody").html(filas);
			cargarPaginado(response, valorBuscar,pagina,cantidad);

			$("#tbl tbody tr").click(function(){
				$(this).addClass('selected').siblings().removeClass('selected');    
				var value=$(this).find('td:first').html(); 	
				FiltrarDetalle(value);			
			});

		}
	});
}


function borrar_Servicio(id)
{
    if(confirm('¿Esta seguro que desea eliminar el registro?'))
    {
        $.ajax({
            url : Site+controller+"/ajax_delete/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
				Filtrar($('#clienteid').val());
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


function verDetalle(servicioId,valorBuscar,pagina,cantidad){
		console.log("Servicio a ver",servicioId);
	$.ajax({
		url : "../registrar/mostrarDetalleXcliente",
		type: "POST",
		data: {servicioId:servicioId,buscar:valorBuscar,nropagina:pagina,cantidad:cantidad},
		dataType:"json",
		success:function(response){			
			filas = "";
			$.each(response.cli_servicios_detalle,function(key,item){
				filas+="<tr>"+
				"<td>"+item.id+"</td>"+
				"<td>"+item.nombre+"</td>"+
				"<td>"+item.precio+"</td>"+
				"<td>"+item.cantidad+"</td>"+
				"<td>"+item.descripcion+"</td>"+			
				"</tr>";
			});

			$("#tbldetalle tbody").html(filas);
			cargarPaginadoDetalle(response, valorBuscar,pagina,cantidad);

			$("#tbldetalle tbody tr").click(function(){
				$(this).addClass('selected').siblings().removeClass('selected');    
				var value=$(this).find('td:first').html(); 				
			});

		}
	});
}

function Filtrar($cliente_id){
	mostrarDatosCliente($cliente_id,"",1,5);

	
	$("input[name=busqueda]").keyup(function(){
		textobuscar = $(this).val();
		valoroption = $("#cantidad").val();
		mostrarDatosCliente($cliente_id,textobuscar,1,valoroption);
	});

	$("body").on("click",".paginacion li a",function(e){
		e.preventDefault();
		valorhref = $(this).attr("href");
		valorBuscar = $("input[name=busqueda]").val();
		valoroption = $("#cantidad").val();
		mostrarDatosCliente($cliente_id,valorBuscar,valorhref,valoroption);
	});

	$("#cantidad").change(function(){
		valoroption = $(this).val();
		valorBuscar = $("input[name=busqueda]").val();
		mostrarDatosCliente($cliente_id,valorBuscar,1,valoroption);
	});
}


function FiltrarDetalle($servicioId){
	verDetalle($servicioId,"",1,5);

	
	$("input[name=busqueda]").keyup(function(){
		textobuscar = $(this).val();
		valoroption = $("#cantidadDetalle").val();
		verDetalle($servicioId,textobuscar,1,valoroption);
	});

	$("body").on("click",".paginacionDetalle li a",function(e){
		e.preventDefault();
		valorhref = $(this).attr("href");
		valorBuscar = $("input[name=busqueda]").val();
		valoroption = $("#cantidadDetalle").val();
		console.log($servicioId,valorBuscar,valorhref,valoroption);
		verDetalle($servicioId,valorBuscar,valorhref,valoroption);
	});

	$("#cantidadDetalle").change(function(){
		valoroption = $(this).val();
		valorBuscar = $("input[name=busqueda]").val();
		verDetalle($servicioId,valorBuscar,1,valoroption);
	});
}
	
function cargarPaginado(response,valorBuscar,pagina,cantidad){
	linkseleccionado = Number(pagina);
	//total registros
	totalregistros = response.totalregistros;
	//cantidad de registros por pagina
	cantidadregistros = response.cantidad;
	
	numerolinks = Math.ceil(totalregistros/cantidadregistros);
	paginador = "<ul class='pagination'>";
	if(linkseleccionado>1)
	{
		paginador+="<li><a href='1'>&laquo;</a></li>";
		paginador+="<li><a href='"+(linkseleccionado-1)+"' '>&lsaquo;</a></li>";
	
	}
	else
	{
		paginador+="<li class='disabled'><a href='#'>&laquo;</a></li>";
		paginador+="<li class='disabled'><a href='#'>&lsaquo;</a></li>";
	}
	//muestro de los enlaces 
	//cantidad de link hacia atras y adelante
	 cant = 2;
	 //inicio de donde se va a mostrar los links
	pagInicio = (linkseleccionado > cant) ? (linkseleccionado - cant) : 1;
	//condicion en la cual establecemos el fin de los links
	if (numerolinks > cant)
	{
		//conocer los links que hay entre el seleccionado y el final
		pagRestantes = numerolinks - linkseleccionado;
		//defino el fin de los links
		pagFin = (pagRestantes > cant) ? (linkseleccionado + cant) :numerolinks;
	}
	else 
	{
		pagFin = numerolinks;
	}
	
	for (var i = pagInicio; i <= pagFin; i++) {
		if (i == linkseleccionado)
			paginador +="<li class='active'><a href='javascript:void(0)'>"+i+"</a></li>";
		else
			paginador +="<li><a href='"+i+"'>"+i+"</a></li>";
	}
	//condicion para mostrar el boton siguiente y ultimo
	if(linkseleccionado<numerolinks)
	{
		paginador+="<li><a href='"+(linkseleccionado+1)+"' >&rsaquo;</a></li>";
		paginador+="<li><a href='"+numerolinks+"'>&raquo;</a></li>";
	
	}
	else
	{
		paginador+="<li class='disabled'><a href='#'>&rsaquo;</a></li>";
		paginador+="<li class='disabled'><a href='#'>&raquo;</a></li>";
	}
	
	paginador +="</ul>";
	$(".paginacion").html(paginador);
	
	}
	


	function cargarPaginadoDetalle(response,valorBuscar,pagina,cantidad){
	linkseleccionado = Number(pagina);
	//total registros
	totalregistros = response.totalregistros;
	//cantidad de registros por pagina
	cantidadregistros = response.cantidad;
	
	numerolinks = Math.ceil(totalregistros/cantidadregistros);
	
	paginador = "<ul class='pagination'>";
	if(linkseleccionado>1)
	{
		paginador+="<li><a href='1'>&laquo;</a></li>";
		paginador+="<li><a href='"+(linkseleccionado-1)+"' '>&lsaquo;</a></li>";
	
	}
	else
	{
		paginador+="<li class='disabled'><a href='#'>&laquo;</a></li>";
		paginador+="<li class='disabled'><a href='#'>&lsaquo;</a></li>";
	}
	//muestro de los enlaces 
	//cantidad de link hacia atras y adelante
	 cant = 2;
	 //inicio de donde se va a mostrar los links
	pagInicio = (linkseleccionado > cant) ? (linkseleccionado - cant) : 1;
	//condicion en la cual establecemos el fin de los links
	if (numerolinks > cant)
	{
		//conocer los links que hay entre el seleccionado y el final
		pagRestantes = numerolinks - linkseleccionado;
		//defino el fin de los links
		pagFin = (pagRestantes > cant) ? (linkseleccionado + cant) :numerolinks;
	}
	else 
	{
		pagFin = numerolinks;
	}
	
	for (var i = pagInicio; i <= pagFin; i++) {
		if (i == linkseleccionado)
			paginador +="<li class='active'><a href='javascript:void(0)'>"+i+"</a></li>";
		else
			paginador +="<li><a href='"+i+"'>"+i+"</a></li>";
	}
	//condicion para mostrar el boton sigueinte y ultimo
	if(linkseleccionado<numerolinks)
	{
		paginador+="<li><a href='"+(linkseleccionado+1)+"' >&rsaquo;</a></li>";
		paginador+="<li><a href='"+numerolinks+"'>&raquo;</a></li>";
	
	}
	else
	{
		paginador+="<li class='disabled'><a href='#'>&rsaquo;</a></li>";
		paginador+="<li class='disabled'><a href='#'>&raquo;</a></li>";
	}
	
	paginador +="</ul>";
	$(".paginacionDetalle").html(paginador);
	
	}

function NoSelect()
{
    Message("Por favor, seleccione un registro.")
}

</script>
