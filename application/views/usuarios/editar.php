<div class="container-fluid">
	<div class="col-xs-12 col-md-8">

	<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Editar Usuarios</h3>
	</div>
	<form method="post" action="<?php echo base_url() ?>index.php/usuarios/guardarCambios">
	<div class="panel-body">
		<input name="id" type="hidden" value="<?php echo $usuarios->id ?>">
		<div class="form-group">
			<label for="nombres">Nombres:</label>
			<input value="<?php echo $usuarios->nombres ?>" class="form-control" name="nombres" required type="text" id="nombres" placeholder="Escribe el nombre">
		</div>
		<div class="form-group">
			<label for="apellidos">Apellidos:</label>
			<input  value="<?php echo $usuarios->apellidos ?>" required id="apellidos" name="apellidos"  class="form-control">
		</div>
		<div class="form-group">
			<label for="dni">DNI:</label>
			<input value="<?php echo $usuarios->dni ?>" class="form-control" name="dni" required type="number" id="dni" placeholder="dni">
		</div>
			<div class="form-group">
			<label for="email">Email:</label>
			<input value="<?php echo $usuarios->email ?>" class="form-control" name="email" required type="text" id="email" placeholder="Email">
		</div>
		<div class="form-group">
			<label for="celular">Celular:</label>
			<input value="<?php echo $usuarios->celular ?>" class="form-control" name="celular" required type="text" id="celular" placeholder="telefono">
		</div>
	</div>		
	<div class="panel-footer">
		<br><br>
		<input type="submit" class="btn btn-primary"  value="Guardar">
		<a href="<?php echo base_url() ?>index.php/usuarios" class="btn btn-info"> Volver Atrás</a>
		</div>		
	</form>
</div>
