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
						<div class="col-md-6 col-xs-6">
							<div class="form-group">
									<label>Cliente:</label>
									<!-- <input type ="text" id="clienteid" name="clienteid" hidden value="<?php echo ($id)?>"> -->
									<input type="text" class="form-control" id="combocliente" name="cliente" placeholder="Buscar Cliente">
							</div>
						</div>
						<div class="col-md-2 col-xs-2">	
						<div class="form-group">
							<label></label>										
							<a onclick="agregarFila()" class="forma-control btn btn-danger"><i class="glyphicon glyphicon-floppy-disk"></i>Finalizar</a>
						</div>
						</div>
					</div>	
					<div class="row">
						<div class="col-md-6 col-xs-6">
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
							<label></label>
							<a onclick="agregarFila()" class="form-control btn btn-success"><i class="glyphicon glyphicon-shopping-cart"></i>Agregar</a>						
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
	recorrer_tabla();
}

function agregarFila() {   

	cantidad = $('#cantidadid').val();

	if ($('#productoid').val()!="")
	{
		var filas="<tr id='fila"+i+"'>"+
					"<td id=id"+i+">"+i+"</td>"+
					"<td>"+codigoProducto+"</td>"+
					"<td>"+$("#comboproducto").val()+"</td>"+
					"<td class='r'>"+precioVenta+"</td>"+
					"<td class='r'>"+cantidad+"</td>"+
					"<td class='r tot total_fila"+i+"'>"+cantidad*precioVenta+"</td>"+
					"<td class='r'>"+'<a class="btn btn-sm btn-danger" onclick="borrarFila('+i+')"><i class="glyphicon glyphicon-trash"></i></a>'+"</td>"+				
					"</tr>";				
		//total = total + (cantidad * precioVenta);
		//$('#totalVenta').text(total);			
		$('#productoid').val('');
		$("#comboproducto").val('');		
		$("#comboproducto").focus();
		$('#cantidadid').val('1');
		
		precioVenta = 0;
		codigoProducto = 0;		

		i++;

	}else{
		$.notify({
                   title: '<strong>Atención!</strong>',
                   message: 'Debe buscar el producto.'
               },{
                   type: 'info'
               });
		$("#comboproducto").focus();
	}		

	$.when($('#detalle').append(filas)).then
	{	
		recorrer_tabla();
	}
}

function recorrer_tabla(){
	var j=1;
	var total_venta = 0;
	var parcial = 0;
	var n = 0;
	$("td").each(function(){
 		 console.log("valor",i,$('.total_fila'+j).text());
		 parcial = parseFloat($('.total_fila'+j).text());
		 if (!isNaN(parcial)){
			 n++;
		 	total_venta = total_venta + parcial;
			 console.log("fila",j,n);
			 $("#id"+j).text(n);
		 }
		 j++;
 	});	 
	 $('#totalVenta').text(total_venta);			
}

</script>
