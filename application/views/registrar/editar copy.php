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
										<input type ="text" id="clienteid" name="clienteid" hidden  value="<?php echo $filas[0]->clienteId?>">
										<input type="text" class="form-control" id="combocliente" name="cliente" placeholder="Buscar Cliente">
								</div>
							</div>
							</div>
							<a onclick="agregarFila()" class="btn btn-success"><i class="glyphicon glyphicon-plus"></i>Agregar</a>


						<div class="col-md-12" id="detalle">
						<input type="text" id="cantidad_filas">
  						<?php $j=1; ?>	
						<?php foreach ($filas as $fila): ?>
							<div class="row fila" id="fila<?= $j?>">
								<div class="id_" hidden><?= $j ?></div>
								<div class="col-md-12">
									<div class="col-md-6">
										Tipo de Servicio:
										</br>
										<!-- <select class="form-control" name="servicio[]" required id ="comboservicio<?= $j?>"></select> -->
										<input class="form-control" name="servicio[]" required  id="comboservicio<?= $j?>" value="<?= $fila->nombre ?>" >
									</div>
									<div class="col-md-2">
										Precio:
										<input class="form-control" name="precio[]" required  id="precio<?= $j?>" value="<?= $fila->precio ?>">
									</div>
									<div class="col-md-2">
										Cantidad:
										<input class="form-control" name="cantidad[]" required id="cantidad<?= $j?>" value="<?= $fila->cantidad ?>">
									</div>
									<div class="col-md-2">
										Total:
										<input class="form-control tot'+<?= $j ?>+'" name="total[]" required id="total<?= $fila->codigo_servicio ?>" value="<?= $fila->precio * $fila->cantidad ?>">
									</div>
								</div>
								<div class="col-md-12">
									<div class="col-md-10">
										Detalle:
										<textarea  class="form-control" name="detalle[]" rows="2"><?= $fila->descripcion ?></textarea>
									</div>
									<div class="col-md-2">
										</br>
										<a class="btn btn-sm btn-danger" onclick="borrarFila(<?= $j ?>)"><i class="glyphicon glyphicon-trash"></i></a>
									</div>
								</div>
							</div>				
						<?php $j++; endforeach; ?>


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

	
	$(function() {

		$("#fechahoy").val("<?php echo ($filas[0]->fecha_servicio)?>");
		$("#combocliente").val("<?php echo ($filas[0]->apellido." ".$filas[0]->nombrecliente)?>");
		$("#cantidad_filas").val("<?php echo(count($filas))?>");
		cant_filas = parseInt($("#cantidad_filas").val());
		i= cant_filas+1;

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


		asyncCall();




	});


	async function asyncCall() {
  console.log('calling');
  var result = await resolveAfter2Seconds();
  console.log(result);
  // expected output: 'resolved'
}

	function resolveAfter2Seconds() {
  return new Promise(resolve => {
    setTimeout(() => {
      //resolve('resolved');

	  for(m=1; m<=cant_filas; m++){
		  console.log("entro");
		cargar(m);

		// $("#fila"+m).click(function(){
		// 		$(this).addClass('selected').siblings().removeClass('selected');    
		// 		var value=$(this).find('div:first').html(); 
		// 		console.log(value);				
		// 	});

		// $("#cantidad"+m).on('change',function(){
		// 	  var a = $('.selected').find('div:first').html();
		// 	  console.log("cambios",a);
		// 	  Resultado = $("#precio"+a).val() * $("#cantidad"+a).val();
		// 	  $("#total"+a).val(Resultado);  
		// });
		
		 }

		 resolve('resolved');

    }, 1000);
  });
}


	function borrarFila(index){
		console.log("ingreso");
		$("#fila"+index).remove();
	}



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
				console.log(value);				
			});

		
		$("#cantidad"+i).on('change',function(){
			  var a = $('.selected').find('div:first').html();
			  console.log("cambios",a);
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
	console.log("entro a cargar",n)
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

</script>

<!-- 
	<script src="<?php echo base_url();?>assets/js/combos.js"></script>
	<script>
	
	

		

	
		jQuery(document).on('submit','#form_insert',function(event)
		{
			event.preventDefault();
			jQuery.ajax({
				url:"<?php echo site_url('Registrar/insertar');?>",
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
			 	console.log("Error");
			 });

		})

});





</script> -->
