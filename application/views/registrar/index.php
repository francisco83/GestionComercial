<?php  $this->load->view("partial/encabezado"); ?>
<style>
  .custom-combobox {
    position: relative;
    display: inline-block;
  }
  .custom-combobox-toggle {
    position: absolute;
    top: 0;
    bottom: 0;
    margin-left: -1px;
    padding: 0;
  }
  .custom-combobox-input {
    margin: 0;
    padding: 5px 10px;
  }
  .celda{	  
	  	/* border-top: 1px solid black;
    	border-right: 1px solid black; */
		border: 1px solid black;
		height: 36px;
  }
  </style> 

<div class="container">


			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h4>Registrar servicios</h4>
					</div>
					<div class="panel-body">

						<div class="form-group">
								<label>Cliente:</label>
								<input type="text" class="form-control" id="combocliente" placeholder="Buscar Cliente">
						</div>
						<div class="form-group">
								<label for="fecha">Fecha:</label>
								<input class="form-control" name="fecha" required type="date" id="fechaHoy">
						</div> 
						<!-- <div class="form-group">
								<label for="servicio">Tipo Servicio:</label>
								<select class="form-control" id ="comboservicio"></select> 								
						</div> -->
						<a onclick="agregarFila()" class="btn btn-success"><i class="glyphicon glyphicon-plus"></i>Agregar</a>

  					<form action="" id="form_insert">
					<div class="form-group" id="detalle">
  						<div class="row">
						<div class="col-xs-2 celda">Fecha</div>
						<div class="col-xs-4 celda">Tipo Servicio</div>
						<div class="col-xs-1 celda">Precio</div>
						<div class="col-xs-1 celda">Cantidad</div>
						<div class="col-xs-1 celda">Total</div>
						<div class="col-xs-2 celda">Detalle</div>
						<div class="col-xs-1 celda"></div>
					</div>


						<!-- <div class="col-xs-2 celda"><input class="form-control" name="fecha[]" type="date" id="fecha_for"></div>
						<div class="col-xs-4 celda">
								<select class="form-control" id ="comboservicio"></select> 								
						</div>
						<div class="col-xs-1 celda">100</div>
						<div class="col-xs-1 celda"><input class="form-control" name="cantidad[]" required type="number" id="cantidad_for" value="1"></div>
						<div class="col-xs-1 celda">100</div>
						<div class="col-xs-2 celda"><input class="form-control" name="detalle[]" required type="text" id="detalle_for" value="Pruaba de escritura de detalles ...pppppp."></div>
						<div class="col-xs-1 celda">
						<a onclick="borrarFila()"><i class="glyphicon glyphicon-trash"></i></a></div>


						<div class="col-xs-2 celda"><input class="form-control" name="fecha[]" type="date" id="fecha_for"></div>
						<div class="col-xs-4 celda">
						
						<select class="form-control" id ="comboservicio"></select> 	
						
						</div>
						<div class="col-xs-1 celda">100</div>
						<div class="col-xs-1 celda"><input class="form-control" name="cantidad[]" required type="number" id="cantidad_for" value="1"></div>
						<div class="col-xs-1 celda">100</div>
						<div class="col-xs-2 celda"><input class="form-control" name="detalle[]" required type="text" id="detalle_for" value="Solo una"></div>
						<div class="col-xs-1 celda">
						<a onclick="borrarFila()"><i class="glyphicon glyphicon-trash"></i></a></div>
						 -->
					</div>			
  					<input type="submit" value="Guardar">		
					</form>


						<!-- <table id ="tableRegistrar" class="table table-bordered table-hover">
							<thead>
								<tr>	
									<th>#</th>							
									<th>Fecha</th>
									<th>Tipo Servicio</th>
									<th>Monto</th>		
									<th>Cantidad</th>
									<th>Total</th>
									<th>Detalle</th>	
									<th></th>	
								</tr>							
							</thead>
							<tbody>
							</tbody>
						</table> -->

						</div><!-- fin pbody -->

					</div>
				</div>
			</div>
</div>

	<script src="<?php echo base_url();?>assets/js/combos.js"></script>
	<script>
	
	var i = 1;
	
	$(function() {

		$("#fechaHoy").val(hoyFecha());

		//Buscar Cliente
		$( "#combocliente" ).autocomplete({
		source: "<?php echo site_url('Clientes/get_autocomplete/?');?>",
		autoFill:true,
		select: function(event, ui){
			$('#clienteid').val(ui.item.id);
			$("#combocliente").val(ui.item.value);
		},
		});
		
		//Combo de Servicios			 
		$.ajax({
			url : "<?php echo site_url('Registrar/listar');?>",
			type: "POST",
			dataType:"json",
			success:function(response){
				filas = "<option value='-1'></option>";	
				$.each(response,function(key,item){
					filas+="<option value='"+item.id+"'>"+item.nombre+"</option>";
				});
				//$("#comboservicio").html(filas);		
				//$("#comboservicio").combobox();		
			}
		});
	

		jQuery(document).on('submit','#form_insert',function(event)
		{
			event.preventDefault();
			jQuery.ajax({
				url:"<?php echo site_url('Registrar/insertar');?>",
				type: 'POST',
				datetype: 'json',
				data: $(this).serialize(),
			})
			.done(function(respuesta)
			{
				console.log(respuesta);
			})
			.fail(function(resp)
			{
				console.log("Error");
			})

		}
		)

});




// function agregarFila() {   
//    var htmlTags = '<tr id="fila'+i+'">'+
// 		'<td>' +i+'</td>'+
//         '<td>' + $("#fecha").val() + '</td>'+
//         '<td>' + $("#comboservicio").children("option:selected").text() + '</td>'+
//         '<td>' + '<span id="Monto" contenteditable>200</span>' + '</td>'+
// 		'<td>' + '<span id="cantidad" contenteditable>1</span>'+'</td>'+
// 		'<td>' + 0 + '</td>'+
// 		'<td>' + '<span id="detalle" contenteditable>1</span>'+ '</td>'+
// 		'<td>'+'<a onclick="borrarFila('+i+')"><i class="glyphicon glyphicon-trash"></i></a>'+'</td>'+
//       '</tr>';     
//    $('#tableRegistrar tbody').append(htmlTags);
//    i++;
// }


function agregarFila() {   
	var htmlTags =
				'<div class="row" id="fila'+i+'">'+		 
				'<div class="col-xs-2 celda">'+ '<input class="form-control" name="fecha[]" required type="date" id="fecha'+i+'">' + '</div>'+
				'<div class="col-xs-4 celda">'+'<select class="form-control target'+i+'" name="servicio[]" id ="comboservicio'+i+'"></select> ' + '</div>'+
				'<div class="col-xs-1 celda">'+'<input class="form-control" name="precio[]" required type="number" id="precio'+i+'" value="">'+'</div>'+
				'<div class="col-xs-1 celda">'+'<input class="form-control" name="cantidad[]" required type="number" id="cantidad'+i+'" value="1">'+'</div>'+
				'<div class="col-xs-1 celda">'+'<input class="form-control" name="total[]" required type="number" id="total'+i+'" value="">'+'</div>'+
				'<div class="col-xs-2 celda">'+'<input class="form-control" name="detalle[]" required type="text" id="detalle_for" value="">'+'</div>'+
				'<div class="col-xs-1 celda">'+'<a onclick="borrarFila('+i+')"><i class="glyphicon glyphicon-trash"></i></a>'+'</div>'+
				'</div>';

	$.when($('#detalle').append(htmlTags)).then
	{
		$("#comboservicio"+i).html(filas);
		$("#comboservicio"+i).combobox();
		var fecha = $("#fechaHoy").val();
		var precio =100;
		$("#fecha"+i).val(fecha);


		$("#cantidad"+i).bind('change',function(){
  			//alert("The text has been changed.");
			  console.log("cambios");
			  $("#total"+i).val("222");  
		}
		);

		$("#comboservicio"+i).combobox({ 
        select: function (event, ui) { 
            //alert("the select event has fired!"); 
			//delay(550);
			
			//console.log(i,ui,precio,"#precio"+i,$("#precio"+i-1))
				$.when(console.log("the select event has fired!")).then
				{
					$("#precio"+i-1).val(precio);
				}
        	} 
    	});

	}

   i++;
}

function borrarFila(index){
	console.log("ingreso");
	$("#fila"+index).remove();

}

</script>
