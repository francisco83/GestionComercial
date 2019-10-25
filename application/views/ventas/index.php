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
					</div>	
					<div class="row">
						<div class="col-md-6 col-xs-6">
							<div class="form-group">
									<label>Productos:</label>
									<input type ="text" id="productoid" name="productoid" hidden>
									<input type="text" class="form-control" id="comboproducto" name="producto" placeholder="Buscar Producto">
							</div>
						</div>
						<div class="col-md-1 col-xs-2">	
							<div class="form-group">					
								<label>Cantidad:</label>
								<input type ="number" class="form-control"  id="cantidadid" name="cantidadid" value='1'>									
							</div>
						</div>
						<div class="col-md-2 col-xs-3">						
							<label></label>
							<a onclick="agregarFila()" class="form-control btn btn-success"><i class="glyphicon glyphicon-plus"></i>Agregar</a>						
						</div>						
					</div>

				<div class="tableFixHead">
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
				</div> 

				</form>

				<div></div>

			</div><!-- fin pbody -->



			<div class="container" >
				<div class="col-md-6" id="detalle_moneda..." style="border: 1px solid #eeeeee; padding:10px">				
					<div class="row" id="mon0">										
						<div class='col-xs-4'><select class="form-control" name="moneda[]" required id ="combomoneda"></select></div>
						<div class="col-xs-3"><input type="text"  class="moneda" id="input_moneda0" value="0"></div>						
						<div class="col-xs-1"></div>			
						<div class="col-xs-1"><a class="btn btn-sm btn-success" onclick="agregarMoneda()" title="Agregar moneda"><i class="glyphicon glyphicon-plus"></i></a></div>			
					</div>
					<div id="detalle_moneda"></div>
					<div class="row" style="margin-top:10px; font-weight:bold;">										
						<div class='col-xs-4'>Total Pago:</div>
						<div class="col-xs-3">0</div>						
					</div>
				</div>	
				<div class="col-md-3" style="font-size: 25px;">
					<div class="row">				
						<div class="col-xs-4">Total:$</div><div class="col-xs-8" id="totalVentaFinal">0</div>
					</div>	
					<div class="row" style="margin-top:10px;">				
						<div class="col-xs-4">Pago:$</div><div class="col-xs-8" id="totalPagoFinal">0</div>
					</div>
					<div class="row" style="margin-top:10px;">				
					<div class="col-xs-4">Vuelto:$</div><div class="col-xs-8" id="totalVueltoFinal">0</div>
					</div>
				</div>
				<div class="col-md-3">					
					<a onclick="" class="form-control btn btn-primary"><i class="glyphicon glyphicon-shopping-cart"></i>Finalizar Venta</a>					
				</div>
			</div>	
			
			</div>

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
	var mon = 1;
	var registro_moneda;

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

		//Combo de Monedas			 
		$.ajax({
			url : "<?php echo site_url('Tipos_Monedas/get_all');?>",
			type: "POST",
			dataType:"json",
			success:function(response){
				//registro_moneda = "<option value='-1'></option>";	
				$.each(response,function(key,item){
					registro_moneda+="<option value='"+item.id+"'>"+item.nombre+"</option>";
				});											
				$("#combomoneda").html(registro_moneda);
			}
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
					"<td class='c'>"+'<a class="btn btn-sm btn-danger" onclick="borrarFila('+i+')"><i class="glyphicon glyphicon-trash"></i></a>'+"</td>"+				
					"</tr>";			

		$('#productoid').val('');
		$("#comboproducto").val('');		
		$("#comboproducto").focus();
		$('#cantidadid').val('1');
		
		precioVenta = 0;
		codigoProducto = 0;		
		recorrer_monedas()

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
	 $('#totalVentaFinal').text(total_venta);
}



function recorrer_monedas(){
	var j=0;
	var total_pago = 0;
	var parcial = 0;
	$(".moneda").each(function(){
 		 console.log("valor",j,$('#input_moneda'+j).val());
		 parcial = parseFloat($('#input_moneda'+j).val());
		 if (!isNaN(parcial)){			 
		 	total_pago = total_pago + parcial;
		 }
		 j++;
 	});	 
	 $('#totalPagoFinal').text(total_pago);		
	 $('#totalVueltoFinal').text(total_pago - parseFloat($('#totalVentaFinal').text()));	
}




function agregarMoneda() {  

	var nueva_moneda = "<div class='row' id=mon"+mon+">"+						
		"<div class='col-xs-4'><select class='form-control' name='moneda[]' required id ='combomoneda"+mon+"'></select></div>"+
		"<div class='col-xs-3'><input type='text' class='moneda' id='input_moneda"+mon+"' value='0'></div>"+
		"<div class='col-xs-1'><a class='btn btn-sm btn-danger' onclick='borrarMoneda("+mon+")'><i class='glyphicon glyphicon-trash'></i></a></div>"+
		"</div>";

	$.when($('#detalle_moneda').append(nueva_moneda)).then
	{
		$("#combomoneda"+mon).html(registro_moneda);
		mon++;

		$(".moneda").on('change',function(){
			recorrer_monedas();
		});
	}

}

function borrarMoneda(id) {  
	$("#mon"+id).remove();
	mon--;
	recorrer_monedas();
}

//para la primera moneda por defecto
$(".moneda").on('change',function(){
			recorrer_monedas();
		});



</script>
