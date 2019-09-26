<?php  $this->load->view("partial/encabezado"); ?>
<div class="container">
	<div class="row">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h4>Ventas</h4>
			</div>					
			<div id="messages"></div>

			<div class="panel-body">

				<form action="" id="form_insert">

					<div class="row">
						<div class="col-md-3 col-xs-4">
							<div class="form-group">
									<label for="fecha">Fecha:</label>
									<input class="form-control" id="fechahoy" name="fechahoy" required type="date" id="fechaHoy">
							</div>  						
						</div>
						<div class="col-md-6 col-xs-8">
							<div class="form-group">
									<label>Cliente:</label>
									<!-- <input type ="text" id="clienteid" name="clienteid" hidden value="<?php echo ($id)?>"> -->
									<input type="text" class="form-control" id="combocliente" name="cliente" placeholder="Buscar Cliente">
							</div>
						</div>
					</div>	
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
									<label>Productos:</label>
									<input type ="text" id="clienteid" name="productoid" hidden>
									<input type="text" class="form-control" id="comboproducto" name="producto" placeholder="Buscar Producto">
							</div>
						</div>
						<div class="col-md-3">						
							</br>
							<a onclick="agregarFila()" class="btn btn-success"><i class="glyphicon glyphicon-plus"></i>Agregar</a>
						</div>						
					</div>
					
				<table id="tbl" class="table table-bordered table-hover">
					<thead>
						<tr>
							<th>#</th>
							<th>Codigo</th>
							<th>Nombre</th>
							<th>Precio</th>
							<th>Cantidad</th>
							<th>Total</th>
							<th></th>
						</tr>
					</thead>
					<tbody id="detalle">
					</tbody>
				</table>
		

				</form>

			</div><!-- fin pbody -->

			</div>
		</div>
	</div>
</div>


<script src="<?php echo base_url();?>assets/js/combos.js"></script>
<script>
	
	var i = 1;
	
	$(function() {

		$("#fechahoy").val(hoyFecha());

		//Combo Cliente
		$( "#combocliente" ).autocomplete({
			source: "<?php echo site_url('Clientes/get_autocomplete/?');?>",
			autoFill:true,
			select: function(event, ui){			
				$('#clienteid').val(ui.item.id);
				$("#combocliente").val(ui.item.value);
			},
		});		

		//Combo Producto
		$( "#comboproducto" ).autocomplete({
			source: "<?php echo site_url('Productos/get_autocomplete/?');?>",
			autoFill:true,
			select: function(event, ui){			
				$('#productoid').val(ui.item.id);
				$("#comboproducto").val(ui.item.value);
			},
		});		
});

function agregarFila() {   



	var filas="<tr>"+
				"<td>"+i+"</td>"+
				"<td>"+2344+"</td>"+
				"<td>"+$("#comboproducto").val()+"</td>"+
				"<td>"+23+"</td>"+
				"<td>"+1+"</td>"+
				"<td class='r'>"+100+"</td>"+
				"<td class='r'>"+'<a class="btn btn-sm btn-danger" onclick="borrarFila('+i+')"><i class="glyphicon glyphicon-trash"></i></a>'+"</td>"+				
				"</tr>";
	i++;			
				
	/*var htmlTags = '<div class="row fila" id="fila'+i+'">'+
				'<div class="id_" hidden>'+i+'</div>'+
  							'<div class="col-md-12">'+
								'<div class="col-md-6">'+
									'Tipo de Servicio:'+
									'</br>'+
									'<select class="form-control" name="servicio[]" required id ="comboservicio'+i+'"></select>'+
								'</div>'+
								'<div class="col-md-2">'+
									'Precio:'+
									'<input class="form-control" name="precio[]" required  id="precio'+i+'" value="">'+
								'</div>'+
								'<div class="col-md-2">'+
									'Cantidad:'+
									'<input class="form-control" name="cantidad[]" required id="cantidad'+i+'" value="1">'+
								'</div>'+
								'<div class="col-md-2">'+
									'Total:'+
									'<input class="form-control tot'+i+'" name="total[]" required id="total'+i+'" value="0">'+
								'</div>'+
							'</div>'+
							'<div class="col-md-12">'+		
								'<div class="col-md-10">'+					
									'Detalle:'+
									'<textarea  class="form-control" name="detalle[]" rows="2"></textarea>'+
								'</div>'+
								'<div class="col-md-2">'+	
									'</br>'+
									'<a class="btn btn-sm btn-danger" onclick="borrarFila('+i+')"><i class="glyphicon glyphicon-trash"></i></a>'+
								'</div>'+	
							'</div>'+
						'</div>';
*/
	var total = 'total'+i;			

	$.when($('#detalle').append(filas)).then
	{
		//$("#comboservicio"+i).html(filas);
		//$("#comboservicio"+i).combobox();
		//var fecha = $("#fechaHoy").val();
		$("#fecha"+i).val(fecha);

		$("#fila"+i).click(function(){
				$(this).addClass('selected').siblings().removeClass('selected');    
				var value=$(this).find('div:first').html(); 
				console.log(value);				
			});

		
		$("#cantidad"+i).on('change',function(){
			  var a = $('.selected').find('div:first').html();
			  console.log("cambios",a);
			  Resultado = $("#precio"+a).val() * $("#cantidad"+a).val();
			  $("#total"+a).val(Resultado);  
		});

		// $("#comboservicio"+i).combobox({ 
        // select: function (event, ui) { 
		// 	var a = $('.selected').find('div:first').html();
		// 	$("#precio"+a).val($("#comboservicio"+a+ " option:selected").attr("precio"));
		// 	$("#total"+a).val($("#precio"+a).val());

        // 	} 
    	// });

	}

   i++;
}

</script>
