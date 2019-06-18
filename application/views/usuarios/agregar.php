<div class="container-fluid">
	<div class="col-xs-12 col-md-8">

	<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Alta de Usuarios</h3>
	</div>
	
		<?php if(!empty($this->session->flashdata())): ?>
			<div class="alert alert-<?php echo $this->session->flashdata('clase')?>">
				<?php echo $this->session->flashdata('mensaje') ?>
			</div>
		<?php endif; ?>
		<form method="post" action="<?php echo base_url() ?>index.php/usuarios/guardar">

		<div class="panel-body">
			<div class="form-group">
				<label for="nombres">Nombres:</label>
				<input class="form-control" name="nombres" required type="text" id="nombres" placeholder="Escribe el nombre">
			</div> 		

			<div class="form-group">
				<label for="apellidos">Apellidos:</label>
				<input class="form-control" id="apellidos" name="apellidos" placeholder="Ingrese el/los apellidos" class="form-control">
			</div>

			<div class="form-group">
				<label for="dni">DNI:</label>
				<input class="form-control" name="dni" required type="number" id="dni" placeholder="DNI del usuario">
			</div>

			<div class="form-group">
				<label for="email">Email:</label>
				<input class="form-control" name="email" required type="text" id="email" placeholder="Email">
			</div>

			<div class="form-group">
				<label for="celular">Tel. Cel.:</label>
				<input class="form-control" name="celular" required type="text" id="celular" placeholder="telefono">
			</div>

			
		</div>
		<div class="panel-footer">
			<input class="btn btn-info" type="submit" value="Guardar">
			<a href="<?php echo base_url() ?>index.php/usuarios" class="btn btn-info"> Volver Atrás</a>
		</div>

		</form>
	</div>
</div>
