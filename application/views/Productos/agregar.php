<div class="container-fluid">
	<div class="col-xs-12 col-md-8">

	<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Alta de Productos</h3>
	</div>
	
		<?php if(!empty($this->session->flashdata())): ?>
			<div class="alert alert-<?php echo $this->session->flashdata('clase')?>">
				<?php echo $this->session->flashdata('mensaje') ?>
			</div>
		<?php endif; ?>
		<form method="post" action="<?php echo base_url() ?>index.php/productos/guardar">

		<div class="panel-body">
			<div class="form-group">
				<label for="codigo">Código de barras:</label>
				<input class="form-control" name="codigo" required type="text" id="codigo" placeholder="Escribe el código">
			</div> 		

			<div class="form-group">
				<label for="descripcion">Descripción:</label>
				<textarea required id="descripcion" name="descripcion" cols="30" rows="5" class="form-control"></textarea>
			</div>

			<div class="form-group">
				<label for="precioVenta">Precio de venta:</label>
				<input class="form-control" name="precioVenta" required type="number" id="precioVenta" placeholder="Precio de venta">
			</div>

			<div class="form-group">
				<label for="precioCompra">Precio de compra:</label>
				<input class="form-control" name="precioCompra" required type="number" id="precioCompra" placeholder="Precio de compra">
			</div>

			<div class="form-group">
				<label for="existencia">Existencia:</label>
				<input class="form-control" name="existencia" required type="number" id="existencia" placeholder="Cantidad o existencia">
			</div>

			
		</div>
		<div class="panel-footer">
			<input class="btn btn-info" type="submit" value="Guardar">
		</div>

		</form>
	</div>
</div>
