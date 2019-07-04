<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Gestion Comercial</title>

	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/style.css">
	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/jquery-ui.min.css">

	<script src="<?php echo base_url();?>assets/js/jquery-3.3.1.min.js"></script>
	<script src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script>
	<script src="<?php echo base_url().'assets/js/jquery-ui.min.js'?>"></script>
	<!-- <script src="<?php echo base_url();?>assets/js/main.js"></script> -->
</head>
<body>
<?php  $this->load->view("partial/menu"); ?>

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
			<div class="col-md-4 col-md-offset-2 pull-right">
				<div class="form-group has-feedback has-feedback-left">				  
				    <input type="text" class="form-control" name="busqueda" placeholder="Buscar" />
				    <i class="glyphicon glyphicon-search form-control-feedback"></i>
				</div>				
			</div>			
		</div>

			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h4>Registrar servicios</h4>
					</div>
					
					<?php if(!empty($this->session->flashdata())): ?>
					<div class="alert alert-<?php echo $this->session->flashdata('clase')?>">
						<?php echo $this->session->flashdata('mensaje') ?>
					</div>
					<?php endif; ?>

					<div id="messages"></div>

					<div class="panel-body">


					<form action="" id="form_insert">
						<div class="col-md-12">
						<div class="col-md-9">
							<div class="form-group">
									<label>Cliente:</label>
									<input type ="text" id="clienteid" name="clienteid" hidden>
									<div class="row">
										<div class="col-md-8">
											<input type="text" class="form-control" id="combocliente" name="cliente" placeholder="Buscar Cliente">
										</div>
										<div class="col-md-4">
											<input class="btn btn-primary" value="Buscar" onclick="Filtrar($('#clienteid').val())"  >	
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
						<table id="tblusuarios" class="table table-bordered table-hover">
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
						<div class="text-center paginacion">							
						</div>

					</div><!-- fin pbody -->

					</div>
				</div>
			</div>
</div>

	<script src="<?php echo base_url();?>assets/js/combos.js"></script>
	<script>
	
	var i = 1;
	
	$(function() {

		//main(); 
		//$("#fechahoy").val(hoyFecha());

		//Buscar Cliente
		$( "#combocliente" ).autocomplete({
		source: "<?php echo site_url('Clientes/get_autocomplete/?');?>",
		autoFill:true,
		select: function(event, ui){
			console.log(ui);
			$('#clienteid').val(ui.item.id);
			$("#combocliente").val(ui.item.value);
		},
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
				"<td>"+item.fecha+"</td>"+
				"<td></td>"+
				"</tr>";
			});
			$("#tblusuarios tbody").html(filas);
			cargarPaginado(response, valorBuscar,pagina,cantidad);

			$("#tblusuarios tbody tr").click(function(){
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
	$(".paginacion").html(paginador);
	
	}
	

	function NoSelect()
{
    Message("Por favor, seleccione un registro.")
}

</script>
