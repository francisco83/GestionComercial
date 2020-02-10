<?php  $this->load->view("partial/encabezado"); ?>
<div class="container">
	<div class="row">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h4>Ventas</h4>
			</div>					
			<div class="panel-body">

				<form action="" id="form_insert">

					<div class="row">
						<div class="col-md-2 col-xs-4">
							<div class="form-group">
									<label for="fecha">Fecha Desde:</label>
									<input class="form-control" id="fechadesde" name="fechadesde" required type="date">
							</div>  						
						</div>
						<div class="col-md-2 col-xs-4">
							<div class="form-group">
									<label for="fecha">Fecha Hasta:</label>
									<input class="form-control" id="fechahasta" name="fechahasta" required type="date">
							</div>  						
						</div>	
						<div class="col-md-2 col-xs-4">
							<div class="form-group">
							<label></label>
							<a onclick="Filtra_ventas()" class="form-control btn btn-info"><i class="glyphicon glyphicon-search"></i>Filtrar</a>						
							</div>  						
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
		$("#fechadesde").val(hoyFecha());
		$("#fechahasta").val(hoyFecha());
	});

</script>
