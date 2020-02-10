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
					<div class="col-md-6 col-xs-8">
						<div class="form-group">
								<label>Cliente:</label>
								<input type ="text" id="clienteid" name="clienteid" hidden value="<?php echo count($filas)>0 ? ($filas[0]->clienteId) : 0?>">
								<input type="text" class="form-control" id="combocliente" name="cliente" placeholder="Buscar Cliente">
						</div>
					</div>
					</div>
					<div class="tableFixHead">
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

					<?php 
					if (count($filas)>0){
					$debe=0;
					$haber=0;
					$saldo=0;
					foreach ($filas as $fila): ?>
						<tr>			
							<td class="c"><?= date("d/m/Y", strtotime($fila->fecha_venta ));?></td>				
							<td><?= $fila->codigo_venta ?></td>
							<td>venta</td>							
							<td class="r"><?= round($fila->total,2) ?></td>                                        
							<td class="r"><?= round(floatval($fila->monto) - floatval($fila->vuelto),2) ?></td>
							<td class="r"><?= round($fila->monto - $fila->total - $fila->vuelto,2) ?></td>
							<td>
								<a class='btn btn-sm btn-warning'  href='javascript:verDetallePagos(<?php echo $fila->codigo_venta ?>)'><i class='glyphicon glyphicon-eye-open'></i></a>								
								<a class='btn btn-sm btn-primary'  href='<?php echo site_url()?>reportes/ver_venta_ctacte/<?php echo $fila->codigo_venta ?>'target="_blank"><i class='glyphicon glyphicon-print'></i></a>
							</td>
						</tr>
						<?php $debe = round(floatval($debe +  $fila->total),2); 
							  $haber = round(floatval($haber + ($fila->monto - $fila->vuelto)),2);
							  $saldo = round(floatval($saldo + ($fila->monto - $fila->total - $fila->vuelto)),2);
						?>
                    <?php endforeach; }?>


					</tbody>

				</table>
				</div>
				<div style="background-color: lightgray;font-weight: bold;">
					<table>
						<tbody>
							<tr>			
								<td></td>				
								<td></td>
								<td></td>	
								<?php if (count($filas)>0) { ?>					
								<td class="c">Debe:<?php echo round(floatval($debe),2)?></td>                                        
								<td class="c">Haber:<?php echo round(floatval($haber),2)?></td>
								<td class="c">Saldo:<?php echo round(floatval($saldo),2)?></td>
								<?php }
									else{
										echo"<td></td><td></td><td></td>";
									 } ?>
								<td>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
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
		$("#combocliente").val("<?php echo count($filas)>0?($filas[0]->apellido." ".$filas[0]->nombre):''?>");	

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



function verDetallePagos(IdVenta)
{

	var i=1;
	var detallepagos="";
	var sumaPagos = 0;
	var sumaVueltos = 0;
	detallepagos+="<tr>";
	detallepagos+="<th class='r padding0'><strong>#</strong></th>";
	detallepagos+="<th class='padding0'><strong>Fecha de pago</strong></th>";
	detallepagos+="<th class='padding0'><strong>Moneda</strong></th>";
	detallepagos+="<th class='padding0'><strong>Monto</strong></th>";
	detallepagos+="<th class='padding0'><strong>Vuelto</strong></th>";
	detallepagos+="<th class='padding0'><strong>Total</strong></th>";
	detallepagos+="</tr>"; 
	
	//Recupero los pagos			 
	$.ajax({
		url : "<?php echo site_url('Ventas/verDetallePagos')?>",
		type: "POST",
		dataType:"json",
		data: {IdVenta: IdVenta},
		success:function(response){						
			$.each(response,function(key,item){
				detallepagos+="<tr>";					
				detallepagos+="<td class='r'>"+i+"</td>"; 
				detallepagos+="<td>"+StrToFecha(item.fecha_pago)+"</td>";
				detallepagos+="<td>"+item.nombre+"</td>";
				detallepagos+="<td class='r'>"+parseFloat(item.monto)+"</td>";									
				detallepagos+="<td class='r'>"+parseFloat(item.vuelto)+"</td>";									
				detallepagos+="<td class='r'>"+parseFloat(item.monto-item.vuelto)+"</td>";									
				detallepagos+="</tr>"
				i++;
				sumaPagos = sumaPagos + parseFloat(item.monto);
				sumaVueltos = sumaVueltos + parseFloat(item.vuelto);
			});				
			detallepagos+="<tr>";
			detallepagos+="<td></td>"; 
			detallepagos+="<td></td>";
			detallepagos+="<td class='r'><strong>TOTAL:</strong></td>";
			detallepagos+="<td class='r'>"+sumaPagos+"</td>";	
			detallepagos+="<td class='r'>"+sumaVueltos+"</td>";	
			detallepagos+="<td class='r' style='font-weight: bold;'>"+(parseFloat(sumaPagos)-parseFloat(sumaVueltos))+"</td>";									
			detallepagos+="</tr>";
									
			$('#tbodypagos').html(detallepagos);
		}
	});

	
	$('.help-block').empty();
    $('#modal_form').modal('show'); 
    $('.modal-title').text('Detalle de pagos');
	$('.modal-backdrop').remove();
}

</script>


<!-- Bootstrap modal -->
<div class="modal" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Detalle de Pagos</h3>
            </div>
            <div class="modal-body form">
			<table class="table table-bordered" style="font-size: smaller">
				<tbody id="tbodypagos">  									
				</tbody>
			</table>

            </div>
            <div class="modal-footer">                
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
