<div class="container-fluid">
	<div class="col-xs-12 col-md-8">

	<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Alta de servicios</h3>
	</div>
	
		<?php if(!empty($this->session->flashdata())): ?>
			<div class="alert alert-<?php echo $this->session->flashdata('clase')?>">
				<?php echo $this->session->flashdata('mensaje') ?>
			</div>
		<?php endif; ?>
		<form method="post" action="<?php echo base_url() ?>index.php/servicios/guardar">

		<div class="panel-body">
			<div class="form-group">
				<label for="nombre">Nombre:</label>
				<input class="form-control" name="nombre" required type="text" id="nombre" placeholder="Escribe el nombre">
			</div> 		

			<div class="form-group">
				<label for="descripcion">descripcion:</label>
				<input class="form-control" id="descripcion" name="descripcion" placeholder="Ingrese el/los descripcion" class="form-control">
			</div>

			<div class="form-group">
				<label for="precio">precio:</label>
				<input class="form-control" id="precio" name="precio" placeholder="Ingrese el precio" class="form-control">
			</div>
		</div>
		<div class="panel-footer">
			<input class="btn btn-info" type="submit" value="Guardar">
			<a href="<?php echo base_url() ?>index.php/servicios" class="btn btn-info"> Volver Atrás</a>
		</div>

		</form>
	</div>
</div>
