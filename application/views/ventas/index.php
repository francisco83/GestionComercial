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
						<div class="col-xs-3">						
							<label></label>
							<a onclick="agregarFila()" class="form-control btn btn-success"><i class="glyphicon glyphicon-shopping-cart"></i>Agregar</a>						
						</div>						
					</div>


<!-- 					
					<div class="row" style="background-color:black; color:white;">
						<div class="col-xs-1">#</div>
						<div class="col-xs-2">Codigo</div>
						<div class="col-xs-2">Nombre</div>
						<div class="col-xs-2">Precio</div>
						<div class="col-xs-1">Cantidad</div>
						<div class="col-xs-2">Total</div>
						<div class="col-xs-2"></div>
					</div>	
					<div class="tablaVenta">	 -->
						<!-- <div class="row">
							<div class="col-xs-1">123</div>
							<div class="col-xs-2">123456</div>
							<div class="col-xs-2">Mermelada de durazno</div>
							<div class="col-xs-2">1234</div>
							<div class="col-xs-2">10</div>
							<div class="col-xs-2">12340</div>
							<div class="col-xs-2"></div>
						</div>						
						<div class="row">
							<div class="col-xs-1">1234</div>
							<div class="col-xs-2">1234565555</div>
							<div class="col-xs-2">Mermelada de durazno</div>
							<div class="col-xs-2">12344</div>
							<div class="col-xs-2">10</div>
							<div class="col-xs-2">123440</div>
							<div class="col-xs-2"></div>
						</div>	 -->
						<!-- <div id="detalle"></div> -->
					<!-- </div> -->

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
				<div class="col-md-6" id="detalle_moneda" style="border: 1px solid #000000; border-radius:10px">				
					<div class="row" id="mon0">										
						<div class='col-xs-4'><select class="form-control" name="moneda[]" required id ="combomoneda"></select></div>
						<div class="col-xs-3"><input type="text"  class="form-control" value="0"></div>						
						<div class="col-xs-1"><a class="btn btn-sm btn-danger" onclick="borrarMoneda(0)"><i class="glyphicon glyphicon-trash"></i></a></div>			
						<div class="col-xs-3"><a onclick="agregarMoneda()" class="form-control btn btn-success">Agregar</a></div>
					</div>
				</div>	
			</div>	

			<div class="container" >
				<div class="col-md-6" id="detalle_moneda" style="border: 1px solid #000000; border-radius:10px">				
				<div class="row" style="margin-top:30px;">				
					<div class="col-xs-3">Total:</div>
					<div class="col-xs-3">$100</div>				
					<div class="col-xs-3">Diferencia:</div>
					<div class="col-xs-3">$0</div>
				</div>	
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
	

		// var filas=	
		// 		"<div class='row' id='fila"+i+"'>"+
		// 		"<div class='col-xs-1' id=id"+i+">"+i+"</div>"+
		// 		"<div class='col-xs-2'>"+codigoProducto+"</div>"+
		// 		"<div class='col-xs-2'>"+$("#comboproducto").val()+"</div>"+
		// 		"<div class='col-xs-2'>"+precioVenta+"</div>"+
		// 		"<div class='col-xs-1'>"+cantidad+"</div>"+
		// 		"<div class='col-xs-2 total_fila"+i+"'>"+cantidad*precioVenta+"</div>"+
		// 		"<div class='col-xs-2'>"+'<a class="btn btn-sm btn-danger" onclick="borrarFila('+i+')"><i class="glyphicon glyphicon-trash"></i></a>'+"</div>"
		// 		"</div>";	

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
	//$("#detalle .row").each(function(){

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





function agregarMoneda() {  

	var nueva_moneda = "<div class='row' id=mon"+mon+">"+						
		"<div class='col-xs-4'><select class='form-control' name='moneda[]' required id ='combomoneda"+mon+"'></select></div>"+
		"<div class='col-xs-3'><input type='text' class='form-control'  value='0'></div>"+
		"<div class='col-xs-1'><a class='btn btn-sm btn-danger' onclick='borrarMoneda("+mon+")'><i class='glyphicon glyphicon-trash'></i></a></div>"+
		"</div>";

		//$('#detalle_moneda').append(nueva_moneda)

		$.when($('#detalle_moneda').append(nueva_moneda)).then
	{
		$("#combomoneda"+mon).html(registro_moneda);
		//$("#combomoneda"+i).combobox();

		// $("#comboservicio"+i).combobox({ 
        // select: function (event, ui) { 
		// 	var a = $('.selected').find('div:first').html();
		// 	$("#precio"+a).val($("#comboservicio"+a+ " option:selected").attr("precio"));
		// 	$("#total"+a).val($("#precio"+a).val());

        // 	} 
    	// });

		mon++;

	}

}

function borrarMoneda(id) {  
	$("#mon"+id).remove();
}



</script>
