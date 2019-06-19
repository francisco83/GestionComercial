<div class="container-fluid">
	<div class="col-xs-12 col-md-8">

	<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Editar servicios</h3>
	</div>
	<form method="post" action="<?php echo base_url() ?>index.php/servicios/guardarCambios">
	<div class="panel-body">
		<input name="id" type="hidden" value="<?php echo $servicios->id ?>">
		<div class="form-group">
			<label for="nombre">Nombre:</label>
			<input value="<?php echo $servicios->nombre ?>" class="form-control" name="nombre" required type="text" id="nombre" placeholder="Escribe el nombre">
		</div>
		<div class="form-group">
			<label for="descripcion">Descripcion:</label>
			<input  value="<?php echo $servicios->descripcion ?>" required id="descripcion" name="descripcion"  class="form-control">
		</div>
		<div class="form-group">
			<label for="precio">Precio:</label>
			<input  value="<?php echo $servicios->precio ?>" required id="precio" name="precio"  class="form-control">
		</div>
	</div>		
	<div class="panel-footer">
		<br><br>
		<input type="submit" class="btn btn-primary"  value="Guardar">
		<a href="<?php echo base_url() ?>index.php/servicios" class="btn btn-info"> Volver Atrás</a>
		</div>		
	</form>
</div>
