<?php  $this->load->view("partial/encabezado"); ?>

<div class="container">
	<div class="row">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h4>Cuenta Corriente</h4>
			</div>					
			<div class="panel-body">

				<form action="" id="form_insert">

					<div class="row">
					<!-- <div class="col-md-3 col-xs-4">
						<div class="form-group">
								<label for="fecha">Fecha:</label>
								<input class="form-control" id="fechahoy" name="fechahoy" required type="date" id="fechaHoy">
						</div>  						
					</div> -->
					<div class="col-md-6 col-xs-8">
						<div class="form-group">
								<label>Cliente:</label>
								<input type ="text" id="clienteid" name="clienteid" hidden value="<?php echo ($id)?>">
								<input type="text" class="form-control" id="combocliente" name="cliente" placeholder="Buscar Cliente">
						</div>
					</div>
					</div>
					<!-- <a onclick="agregarFila()" class="btn btn-success"><i class="glyphicon glyphicon-plus"></i>Agregar</a> -->


					<table id="tbl" class="table table-bordered table-hover">
					<thead>
						<tr>
							<th>Fecha</th>
							<th>Comprobante</th>
							<th>Concepto</th>
							<th>Debe</th>
							<th>Haber</th>
							<th>Saldo</th>
							<th>Opciones</th>
						</tr>
					</thead>
					<tbody id="detalle">
					</tbody>
						<tr>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
						</tr>
				</table>
				

				<!-- <div class="col-md-12" id="detalle">
				</div>			 -->

				<!-- <input class="btn btn-primary" type="submit" value="Guardar">		 -->
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
		//var cliente = "<?php echo ($apellido." ".$nombre)?>";
		$("#fechahoy").val(hoyFecha());
		$("#combocliente").val("<?php echo ($apellido." ".$nombre)?>");

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

function borrarFila(index){
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

</script>
