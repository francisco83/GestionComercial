<?php  $this->load->view("partial/encabezado"); ?>
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
									<input type="text" class="form-control" id="combocliente" name="cliente" placeholder="Buscar Cliente">
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

		main(); 
		$("#fechahoy").val(hoyFecha());

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


	function mostrarDatos(valorBuscar,pagina,cantidad){
		console.log("Ingreso a mostrar datos",valorBuscar,pagina,cantidad);
	$.ajax({
		url : "../registrar/mostrar",
		type: "POST",
		data: {buscar:valorBuscar,nropagina:pagina,cantidad:cantidad},
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

// En el onload
$(function() {
	main();  
});

</script>
