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
  .fila{
	border: 1px solid lightgray;
	margin-top: 20px;
	padding: 10px;


  }
  </style> 

<div class="container">


			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h4>Registrar servicios</h4>
					</div>					
					<div id="messages"></div>

					<div class="panel-body">

						<form action="" id="form_insert">
						<input type="text" id="serviceid" name="serviceid" value="<?php echo ($servicioId)?>" hidden>
							<div class="col-md-12">
							<div class="col-md-3">
								<div class="form-group">
										<label for="fecha">Fecha:</label>
										<input class="form-control" id="fechahoy" name="fechahoy" required type="date" id="fechaHoy">
								</div>  						
							</div>
							<div class="col-md-9">
								<div class="form-group">
										<label>Cliente:</label>
										<input type ="text" id="clienteid" name="clienteid" hidden  value="">
										<input type="text" class="form-control" id="combocliente" name="cliente" placeholder="Buscar Cliente">
								</div>
							</div>
							</div>
							<a onclick="agregarFila()" class="btn btn-success"><i class="glyphicon glyphicon-plus"></i>Agregar</a>


						<div class="col-md-12" id="detalle">

						</div>			

						<input class="btn btn-primary" type="submit" value="Guardar">		
						</form>

					</div><!-- fin pbody -->

					</div>
				</div>
			</div>
</div>





<script src="<?php echo base_url();?>assets/js/combos.js"></script>
<script>


var i=0;
var cant_filas = 0;
var filas;

	
	$(function() {

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
			url : "<?php echo site_url('Registrar/get_all');?>",
			type: "POST",
			dataType:"json",
			success:function(response){
				filas = "<option value='-1'></option>";	
				$.each(response,function(key,item){
					filas+="<option value='"+item.id+"' precio='"+item.precio+"'>"+item.nombre+"</option>";
				});			
			}
		});


		jQuery(document).on('submit','#form_insert',function(event)
		{			
			event.preventDefault();
			jQuery.ajax({
				url:"<?php echo site_url('Registrar/modificar');?>",
				type: 'POST',
				datetype: 'json',
				data: $(this).serialize()
			})
			.done(function(respuesta)
			{
				$("#detalle").html('');

				$.notify({
                   title: '<strong>Atención!</strong>',
                   message: 'Se registro el servicio.'
               },{
                   type: 'success'
               });

			})
			.fail(function(resp)
			{

				$.notify({
                   title: '<strong>Atención!</strong>',
                   message: 'Se produjo un ERROR al registrar el servicio.'
               },{
                   type: 'danger'
               });

			 	console.log("Error");
			 });

		});


		console.log("<?php echo ($servicioId)?>");
		var i=1;
		$.ajax({
			url : "<?php echo site_url('Registrar/get_servicios/'.$servicioId);?>",
			type: "POST",
			dataType:"json",
			success:function(response){				
				$("#fechahoy").val(response[0].fecha_servicio);
				$("#combocliente").val(response[0].apellido+" "+response[0].nombrecliente);
				$("#clienteid").val(response[0].clienteId);

				fil = '<div class="tbl_grid">'+
					'<table id="tbl" class="table table-bordered table-hover">'+
						'<thead>'+
							'<tr>'+
								'<th>#</th>'+
								'<th>Servicio</th>'+
								'<th>Precio</th>'+
								'<th>Cantidad</th>'+
								'<th>Total</th>'+
								'<th>Detalle</th>'+								
								'<th>Opciones</th>'+								
							'</tr>'+
						'</thead>'+
						'<tbody>';
				
				$.each(response,function(key,item){
					console.log("valor",item);
					fil+="<tr>"+
					"<td>"+item.id+"</td>"+
					"<td>"+item.nombre+"</td>"+
					"<td class='r'>"+item.precio+"</td>"+
					"<td class='r'>"+item.cantidad+"</td>"+
					"<td class='r'>"+item.precio * item.cantidad+"</td>"+
					"<td>"+item.descripcion+"</td>"+					
					"<td class='c'>"+"<a class='btn btn-sm btn-warning' onclick='editarFila("+item.id+")'><i class='glyphicon glyphicon-edit'></i></a> "+
					"<a class='btn btn-sm btn-danger' onclick='borrarFila("+item.id+")'><i class='glyphicon glyphicon-trash'></i></a>"+"</td>"+			
					"</tr>";

				/*fil+='<div class="row fila" id="fila'+i+'">'+
				'<div class="id_" hidden>'+i+'</div>'+
  							'<div class="col-md-12">'+
								// '<div class="col-md-4">'+
								// 	'Tipo de Servicio:'+ 
								// 	'</br>'+
								// 	'<select class="form-control" name="servicio[]" required id ="comboservicio'+i+'"></select>'+
								// '</div>'+
								'<div class="col-md-4">'+
								'Tipo de Servicio:'+ 
									'</br>'+
									item.nombre+ 
								'</div>'+
								'<div class="col-md-2">'+
									'Precio:'+
									'<input class="form-control" name="precio[]" required  id="precio'+i+'" value="'+item.precio+'">'+
								'</div>'+
								'<div class="col-md-2">'+
									'Cantidad:'+
									'<input class="form-control" name="cantidad[]" required id="cantidad'+i+'" value="'+item.cantidad+'">'+
								'</div>'+
								'<div class="col-md-2">'+
									'Total:'+
									'<input class="form-control tot'+i+'" name="total[]" required id="total'+i+'" value="'+item.precio * item.cantidad+'">'+
								'</div>'+
							'</div>'+
							'<div class="col-md-12">'+		
								'<div class="col-md-10">'+					
									'Detalle:'+
									'<textarea  class="form-control" name="detalle[]" rows="2">'+item.descripcion+'</textarea>'+
								'</div>'+
								'<div class="col-md-2">'+	
									'</br>'+
									'<a class="btn btn-sm btn-danger" onclick="borrarFila('+i+')"><i class="glyphicon glyphicon-trash"></i></a>'+
								'</div>'+	
							'</div>'+
						'</div>';

						*/

						i++;

			});
			fil+='</tbody>'+
				'</table>'+
				'</div>';
			$("#detalle").html(fil);	
			for(m=1;m<i;m++){
				cargar(m);
			}		
			}
		});
	});


	function agregarFila() {   
	var htmlTags = '<div class="row fila" id="fila'+i+'">'+
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

	var total = 'total'+i;			

	$.when($('#detalle').append(htmlTags)).then
	{
		$("#comboservicio"+i).html(filas);
		$("#comboservicio"+i).combobox();
		var fecha = $("#fechaHoy").val();
		$("#fecha"+i).val(fecha);

		$("#fila"+i).click(function(){
				$(this).addClass('selected').siblings().removeClass('selected');    
				var value=$(this).find('div:first').html(); 							
			});

		
		$("#cantidad"+i).on('change',function(){
			  var a = $('.selected').find('div:first').html();			  
			  Resultado = $("#precio"+a).val() * $("#cantidad"+a).val();
			  $("#total"+a).val(Resultado);  
		});

		$("#comboservicio"+i).combobox({ 
        select: function (event, ui) { 
			var a = $('.selected').find('div:first').html();
			$("#precio"+a).val($("#comboservicio"+a+ " option:selected").attr("precio"));
			$("#total"+a).val($("#precio"+a).val());

        	} 
    	});

	}

   i++;
}

function cargar(n){	
		$("#comboservicio"+n).html(filas);
		$("#comboservicio"+n).combobox();
		var fecha = $("#fechaHoy").val();
		$("#fecha"+n).val(fecha);

		$("#fila"+n).click(function(){
				$(this).addClass('selected').siblings().removeClass('selected');    
				var value=$(this).find('div:first').html(); 
				console.log(value);				
			});

		
		$("#cantidad"+n).on('change',function(){
			  var a = $('.selected').find('div:first').html();
			  console.log("cambios",a);
			  Resultado = $("#precio"+a).val() * $("#cantidad"+a).val();
			  $("#total"+a).val(Resultado);  
		});

		$("#comboservicio"+n).combobox({ 
        select: function (event, ui) { 
			var a = $('.selected').find('div:first').html();
			$("#precio"+a).val($("#comboservicio"+a+ " option:selected").attr("precio"));
			$("#total"+a).val($("#precio"+a).val());

        	} 
    	});
}
	function borrarFila(index){
		$("#fila"+index).remove();
	}

</script>		
