<?php  $this->load->view("partial/encabezado"); ?>

<div class="container">

		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h4>Registrar servicios</h4>
					</div>
					<div class="panel-body">	

					<div style="width:520px;margin:0px auto;margin-top:30px;height:250px;">
					<h2>Select</h2>
					<select class="itemName form-control" id= "itemName" style="width:500px" name="itemName"></select>

					<select class="itemName2" name="state">
						<option value="AL">Alabama</option>
						<option value="WY">Wyoming</option>
						<option value="W">apache</option>
						<option value="Y">servia</option>
					</select>

					</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group has-feedback has-feedback-left">				  
									<input type="text" class="form-control" name="busqueda" placeholder="Buscar Cliente" />
									<i class="glyphicon glyphicon-search form-control-feedback"></i>
								</div>				
						</div>
						<div class="row">
						<div class="col-md-12">
							<a href="<?php echo base_url().'index.php/servicios/agregar/'?>" class="btn btn-success"><i class="glyphicon glyphicon-plus"></i>Agregar Servicio</a>
							</div>
						</div>						
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
															
					<button class="btn btn-warning" onclick="Editar()"><i class='glyphicon glyphicon-edit'></i> Editar</button>
					<button class="btn btn-danger" onclick="Eliminar()"><i class='glyphicon glyphicon-trash'></i> Eliminar</button>
			</div>
		</div>
	</div>




<script src="<?php echo base_url();?>assets/js/jquery-3.3.1.min.js"></script>
	<script src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script>
	<script src="<?php echo base_url();?>assets/js/main.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>


<script type="text/javascript">


$(function() {
	$('.itemName2').select2();  
});

// $(document).ready(function() {
//     $('.itemName').select2();
// });

$('.itemName').select2({
  placeholder: '--- Select Item ---',
  ajax: {
	url: 'Search/index',
	dataType: 'json',
	delay: 250,
	processResults: function (data) {
	  return {
		results: data
	  };
	},
	cache: true
  }
});
</script>
