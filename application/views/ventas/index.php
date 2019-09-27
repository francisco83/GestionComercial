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
						<div class="col-md-6 col-xs-8">
							<div class="form-group">
									<label>Productos:</label>
									<input type ="text" id="productoid" name="productoid" hidden>
									<input type="text" class="form-control" id="comboproducto" name="producto" placeholder="Buscar Producto">
							</div>
						</div>
						<div class="col-xs-2">	
							<div class="form-group">					
								<label>Cantidad:</label>
								<input type ="number" class="form-control"  id="cantidadid" name="cantidadid" value='1'>									
							</div>
						</div>
						<div class="col-xs-2">						
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
						<tr>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th class='r'>Total</th>
							<th class='r' id="totalVenta"></th>
							<th></th>
						</tr>
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
	var precioVenta = 0;
	var codigoProducto = 0;
	var cantidad = 1;
	var total = 0;
	
	$(function() {

		$("#fechahoy").val(hoyFecha());

		//Combo Cliente
		$("#combocliente").autocomplete({
			source: "<?php echo site_url('Clientes/get_autocomplete/?');?>",
			autoFill:true,
			select: function(event, ui){			
				$('#clienteid').val(ui.item.id);
				$("#combocliente").val(ui.item.value);
			},
		});		

		//Combo Producto
		$("#comboproducto").autocomplete({
			source: "<?php echo site_url('Productos/get_autocomplete/?');?>",
			autoFill:true,
			select: function(event, ui){			
				$('#productoid').val(ui.item.id);
				$("#comboproducto").val(ui.item.value);
				precioVenta = ui.item.precioVenta;
				codigoProducto = ui.item.codigoProducto;
			},
		});		
});


$("#comboproducto").keypress(function(e) {
    if(e.which == 13) {
		agregarFila();
    }
});

$("#cantidadid").keypress(function(e) {
    if(e.which == 13) {
		agregarFila();
    }
});

function borrarFila(index){
	$("#fila"+index).remove();
}

function agregarFila() {   

	cantidad = $('#cantidadid').val();

	if ($('#productoid').val()!="")
	{
		var filas="<tr id='fila"+i+"'>"+
					"<td>"+i+"</td>"+
					"<td>"+codigoProducto+"</td>"+
					"<td>"+$("#comboproducto").val()+"</td>"+
					"<td class='r'>"+precioVenta+"</td>"+
					"<td class='r'>"+cantidad+"</td>"+
					"<td class='r'>"+cantidad*precioVenta+"</td>"+
					"<td class='r'>"+'<a class="btn btn-sm btn-danger" onclick="borrarFila('+i+')"><i class="glyphicon glyphicon-trash"></i></a>'+"</td>"+				
					"</tr>";
		i++;			
		total = total + (cantidad * precioVenta);
		$('#totalVenta').text(total);			
		$('#productoid').val('');
		$("#comboproducto").val('');		
		$("#comboproducto").focus();
		$('#cantidadid').val('1');
		
		precioVenta = 0;
		codigoProducto = 0;		
	}else{
		$.notify({
                   title: '<strong>Atención!</strong>',
                   message: 'Debe buscar el producto.'
               },{
                   type: 'info'
               });
		$("#comboproducto").focus();
	}

	//var total = 'total'+i;			

	$.when($('#detalle').append(filas)).then
	{
		


		// $("#fila"+i).click(function(){
		// 		$(this).addClass('selected').siblings().removeClass('selected');    
		// 		var value=$(this).find('div:first').html(); 
		// 		console.log(value);				
		// 	});

		
		// $("#cantidad"+i).on('change',function(){
		// 	  var a = $('.selected').find('div:first').html();
		// 	  console.log("cambios",a);
		// 	  Resultado = $("#precio"+a).val() * $("#cantidad"+a).val();
		// 	  $("#total"+a).val(Resultado);  
		// });

	}

   i++;
}

</script>
