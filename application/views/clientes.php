<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Ejemplos en Codeigniter</title>

	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap.min.css">
	<!-- <link rel="stylesheet" href="<?php echo base_url();?>assets/css/font-awesome.min.css"> -->
</head>
<body>
	<nav class="navbar navbar-default">
	  	<div class="container-fluid">
	    	<!-- Brand and toggle get grouped for better mobile display -->
	    	<div class="navbar-header">
	      		<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
	        		<span class="sr-only">Toggle navigation</span>
	        		<span class="icon-bar"></span>
	        		<span class="icon-bar"></span>
	        		<span class="icon-bar"></span>
	      		</button>
	      		<a class="navbar-brand" href="<?php echo base_url();?>">Proyecto Ejemplos</a>
	    	</div>

	    	<!-- Collect the nav links, forms, and other content for toggling -->
	    	<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	      		<ul class="nav navbar-nav">
	        		<li><a href="<?php echo base_url();?>usuarios">Usuarios <span class="sr-only">(current)</span></a></li>
	        		<li class="active"><a href="<?php echo base_url();?>clientes">Clientes</a></li>	
	      		</ul>
	     	</div><!-- /.navbar-collapse -->
	  	</div><!-- /.container-fluid -->
	</nav>
	<div class="container">
		<div class="row">
			<div class="col-md-6">
				<a href="#" class="btn btn-success">Agregar Cliente</a>
			</div>
			<div class="col-md-4 col-md-offset-2">
				<div class="form-group has-feedback has-feedback-left">
				  
				    <input type="text" class="form-control" name="busqueda" placeholder="Buscar algo" />
				    <i class="glyphicon glyphicon-search form-control-feedback"></i>
				</div>
				
			</div>
			
		</div>
		<br>
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h4>Lista de Usuarios</h4>
					</div>
					<div class="panel-body">
						
						<p>
							<strong>Mostrar por : </strong>
							<select name="cantidad" id="cantidad">
								<option value="5">5</option>
								<option value="10">10</option>
							</select>
						</p>
						<table id="tbclientes" class="table table-bordered">
							<thead>
								<tr>
									<th>#</th>
									<th>Codigo</th>
									<th>Nombres</th>
									<th>Apellidos</th>
									<th>DNI</th>
									<th>Email</th>
									<th>Celular</th>
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
	</div>
	<script src="<?php echo base_url();?>assets/js/jquery-3.3.1.min.js"></script>
	<!-- <script src="<?php echo base_url();?>assets/js/jquery-1.11.3.min.js"></script>  -->
	<script src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script>
	<script src="<?php echo base_url();?>assets/js/main.js"></script>
	<script src="<?php echo base_url();?>assets/js/clientes.js"></script>

	<script>
// En el onload
$(function() {
	main();
});
</script>

</body>
</html>
