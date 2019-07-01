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
  /* .celda{	  

		border: 1px solid black;
		height: 36px;
		font-size: small important!;
  }  */
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
					
					<?php if(!empty($this->session->flashdata())): ?>
					<div class="alert alert-<?php echo $this->session->flashdata('clase')?>">
						<?php echo $this->session->flashdata('mensaje') ?>
					</div>
					<?php endif; ?>

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
										<input type ="text" id="clienteid" name="clienteid" hidden>
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
	
	var i = 1;
	
	$(function() {

		$("#fechahoy").val(hoyFecha());

		//Buscar Cliente
		$( "#combocliente" ).autocomplete({
		source: "<?php echo site_url('Clientes/get_autocomplete/?');?>",
		autoFill:true,
		select: function(event, ui){
			console.log(ui);
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
					filas+="<option value='"+item.id+"' precio='"+item.precio+"'>"+item.nombre+"</option>";
					//filas+="<option value='"+item.id+"'>"+item.nombre+"</option>";

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
				// success: function(json) {
				// 	console.log("funciono",json);
                //     if (json['error']) {
                //         $('#messages').append('<div class="alert alert-danger">' + json['error'] + '</div>');
                //     }

                //     if (json['success']) {
				// 		console.log("Correcto");
                //         $('#messages').append('<div class="alert alert-success">' + json['success'] + '</div>');
                //         $('.refresh').trigger('click');
                //         $(this).tooltip('hide');
                //     }
                // },
 

			})
			// .done(function(respuesta)
			// {
			// 	console.log("Respuesta",respuesta);
			// 	$('#messages').append('<div class="alert alert-success">' + '<?php echo $this->session->flashdata('mensaje') ?>'+ '</div>');
			// 	$("#detalle").html("");
			// })
			// .fail(function(resp)
			// {
			// 	console.log("Error");
			// })

		}
		)

});

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
									'<select class="form-control" name="servicio[]" required id ="comboservicio'+i+'"></select>'+
								'</div>'+
								'<div class="col-md-2">'+
									'Precio:'+
									'<input class="form-control" name="precio[]" required type="number" id="precio'+i+'" value="">'+
								'</div>'+
								'<div class="col-md-2">'+
									'Cantidad:'+
									'<input class="form-control" name="cantidad[]" required type="number" id="cantidad'+i+'" value="1">'+
								'</div>'+
								'<div class="col-md-2">'+
									'Total:'+
									'<input class="form-control tot'+i+'" name="total[]" required type="number" id="total'+i+'" value="0">'+
								'</div>'+
							'</div>'+
							'<div class="col-md-12">'+		
								'<div class="col-md-10">'+					
									'Detalle:'+
									'<textarea  class="form-control" name="detalle[]" rows="2"></textarea>'+
								'</div>'+
								'<div class="col-md-2">'+	
									''+
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
