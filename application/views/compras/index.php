<?php  $this->load->view("partial/encabezado"); ?>
<div class="container">
	<div class="row">
		<div class="panel panel-danger">
			<div class="panel-heading">
				<h4>Compras</h4>
			</div>					
			<div class="panel-body">

				<form action="" id="form_insert">

					<div class="row">
						<div class="col-md-2 col-xs-6">
							<div class="form-group">
									<label for="fecha">Fecha:</label>
									<input class="form-control" id="fechahoy" name="fechahoy" required type="date">
							</div>  						
						</div>
						<div class="col-md-6 col-xs-6">
							<div class="form-group">
									<label>Proveedor:</label>
									<input type ="text" id="proveedorid" name="proveedorid" hidden value="">
									<input type="text" class="form-control" id="comboproveedor" name="proveedor" placeholder="Buscar Proveedor">
							</div>
						</div>	
						<div class="col-md-1 col-xs-6">
							<div class="form-group">
								<label></label>
								<a onclick="nuevoProveedor()" class="form-control btn btn-info"data-toggle="tooltip" title="Nuevo Proveedor" ><i class="glyphicon glyphicon-user"></i></a>						
							</div>
						</div>		
						<div class="col-md-2 col-xs-6">
							<div class="form-group">
								<label></label>
								<a onclick="nuevaCompra()" class="form-control btn btn-success"><i class="glyphicon glyphicon-plus"></i>Nueva compra</a>						
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
								<input  class="form-control only_number"  id="cantidadid" name="cantidadid" value='1'>									
							</div>
						</div>
						<div class="col-md-2 col-xs-3">						
							<label></label>
							<a onclick="agregarFila()" class="form-control btn btn-success"><i class="glyphicon glyphicon-shopping-cart"></i>Agregar</a>						
						</div>						
					</div>

					<div class="tableFixHead">
						<table id="tbl" class="table table-bordered table-hover">
							<thead>
								<tr>
									<th>#</th>
									<th>Id Pro</th>
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
									<th></th>
									<th class='r'>Total</th>
									<th class='r' id="totalCompra"></th>
									<th></th>
								</tr>
						</table>
					</div> 

			
			<div class="col-md-12" style="background-color: lightgrey; margin-top:10px;padding: 10px;">
				<div class="col-md-6" id="detalle_moneda..." style="border: 1px solid #eeeeee; padding:10px; font-size: 20px;">				
					<div class="row" id="mon0">										
						<div class='col-xs-4'><select class="form-control" name="moneda[]" required id ="combomoneda"></select></div>
						<div class="col-xs-3"><input type="text"  class="moneda" name="monedaMonto[]"  id="input_moneda0" value="0" size='7'></div>						
						<div class="col-xs-1"></div>			
						<div class="col-xs-1"><a class="btn btn-sm btn-success" onclick="agregarMoneda()" title="Agregar moneda"><i class="glyphicon glyphicon-plus"></i></a></div>			
					</div>
					<div id="detalle_moneda"></div>
					<div class="row" style="margin-top:10px; font-weight:bold;">										
						<div class='col-xs-4'>Total Pago:</div>
						<div class="col-xs-3" id="total_moneda">0</div>						
					</div>
				</div>	
				<div class="col-md-3" style="font-size: 20px;">
					<div class="row">				
						<div class="col-xs-4">Total:$</div><div class="col-xs-8"><input type="text" id="totalCompraFinal" name="totalCompraFinal" value="0" size="7" readonly></div> 
					</div>	
					<div class="row" style="margin-top:10px;">				
						<div class="col-xs-4">Pago:$</div><div class="col-xs-8"><input type="text" id="totalPagoFinal" name="totalPagoFinal" value="0" size="7" readonly></div>
					</div>
					<div class="row" style="margin-top:10px;">				
					<div class="col-xs-4">Vuelto:$</div><div class="col-xs-8"><input type="text" id="totalVueltoFinal" name="totalVueltoFinal" value="0" size="7" readonly></div>
					</div>
				</div>
				<div class="col-md-3">					
					<!-- <a onclick="" class="form-control btn btn-primary"><i class="glyphicon glyphicon-shopping-cart"></i>Finalizar Compra</a>					 -->
					<input class="btn btn-primary" type="submit" value="Finalizar" style="height: 60px;width: 180px;margin-top: 30px;">
				</div>
			</div>	

			</div><!-- fin pbody -->
			
			</form>
			</div>

			</div>
		</div>
	</div>
</div>

<?php  $this->load->view("partial/proveedor_formulario"); ?>

<script src="<?php echo base_url();?>assets/js/combos.js"></script>
<script>
	
	var Site="<?php echo site_url()?>"
	var i = 1;
	var precioCompra = 0;
	var codigoProducto = 0;
	var cantidad = 1;
	var total = 0;
	var mon = 1;
	var registro_moneda;

	$(function() {
	
		$("#fechahoy").val(hoyFecha());

		//Combo Proveedor
		$("#comboproveedor").autocomplete({
			source: "<?php echo site_url('Proveedores/get_autocomplete/?');?>",
			autoFill:true,
			select: function(event, ui){			
				$('#proveedorid').val(ui.item.id);
				$("#comboproveedor").val(ui.item.value);
			},
		});		

		//Combo Producto
		$("#comboproducto").autocomplete({
			source: "<?php echo site_url('Productos/get_autocomplete/?');?>",
			autoFill:true,
			select: function(event, ui){			
				$('#productoid').val(ui.item.id);
				$("#comboproducto").val(ui.item.value);
				productoId = ui.item.id;
				precioCompra = ui.item.precioCompra;
				codigoProducto = ui.item.codigoProducto;
				existencia = ui.item.existencia
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


		jQuery(document).on('submit','#form_insert',function(event)
		{
				var mensaje_error='';

				event.preventDefault();
				jQuery.ajax({
					url:"<?php echo site_url('compras/insertar');?>",
					type: 'POST',
					datetype: 'json',
					data: $(this).serialize()
				})
				.done(function(respuesta)
				{					
					obj = JSON.parse(respuesta);
					if(obj.status)
            		{		
						$.notify({
						title: '<strong>Atención!</strong>',
						message: 'Se registro la compra.'
						},
						{
							type: 'success'
						});

						nuevaCompra();

					}
					else{
						
					
						$.notify({
							title: '<strong>Atención!</strong>',
							message: obj.error_string		
						},{
							type: 'danger'
						});	
					}			
				})
				.fail(function(resp)
				{
					console.log("Error");
				});
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
	recorrer_monedas();
}

function agregarFila() {   


	cantidad = $('#cantidadid').val();

	/*Validaciones*/
	if(parseFloat(cantidad) > parseFloat(existencia)){		
		$.notify({
                   title: '<strong>Atención!</strong>',
                   message: 'No hay stock para la cantidad ingresada. Cantidad existente: '+existencia
               },{
                   type: 'danger'
               });
		return;
	}

	if(parseFloat(cantidad) <= 0){		
		$.notify({
                   title: '<strong>Atención!</strong>',
                   message: 'Verifique la cantidad no puede ser igual o menor a cero'
               },{
                   type: 'danger'
               });
		return;
	}
	/**/ 

	if ($('#productoid').val()!="")
	{
		var filas="<tr id='fila"+i+"'>"+
					"<td id=id"+i+">"+i+"</td>"+
					"<td><input type='text' hidden name='IdProducto[]' value='"+productoId+"'>"+productoId+"</td>"+
					"<td><input type='text' hidden name='CodigoProducto[]' value='"+codigoProducto+"'>"+codigoProducto+"</td>"+
					"<td><input type='text' hidden name='NombreProducto[]' value='"+$("#comboproducto").val()+"'></div>"+$("#comboproducto").val()+"</td>"+
					"<td><input type='text' hidden name='PrecioCompra[]' value='"+precioCompra+"'>"+precioCompra+"</td>"+
					"<td><input type='text' hidden name='Cantidad[]' value='"+cantidad+"'>"+cantidad+"</td>"+
					"<td class='r tot total_fila"+i+"'>"+cantidad*precioCompra+"</td>"+
					"<td class='c'>"+'<a class="btn btn-sm btn-danger" onclick="borrarFila('+i+')"><i class="glyphicon glyphicon-trash"></i></a>'+"</td>"+				
					"</tr>";			

		$('#productoid').val('');
		$("#comboproducto").val('');		
		$("#comboproducto").focus();
		$('#cantidadid').val('1');
		
		precioCompra = 0;
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
		recorrer_monedas();
	}
}

function recorrer_tabla(){
	var j=1;
	var total_compra = 0;
	var parcial = 0;
	var n = 0;
	$("td").each(function(){

 		 console.log("valor",i,$('.total_fila'+j).text());
		 parcial = parseFloat($('.total_fila'+j).text());
		 if (!isNaN(parcial)){
			 n++;
		 	total_compra = total_compra + parcial;
			 console.log("fila",j,n);
			 $("#id"+j).text(n);
			 
		 }
		 j++;
 	});	 
	 $('#totalCompra').text(total_compra);			
	 $('#totalCompraFinal').val(total_compra);
}



function recorrer_monedas(){
	var j=0;
	var total_pago = 0;
	var parcial = 0;
	$('#totalVueltoFinal').val(0);
	$(".moneda").each(function(){
 		 console.log("valor",j,$('#input_moneda'+j).val());
		 parcial = parseFloat($('#input_moneda'+j).val());
		 if (!isNaN(parcial)){			 
		 	total_pago = total_pago + parcial;
		 }
		 j++;
 	});	 
	 $('#totalPagoFinal').val(total_pago);			
	 $('#totalVueltoFinal').val(total_pago - parseFloat($('#totalCompraFinal').val()));	
	 $('#total_moneda').text(total_pago);
}




function agregarMoneda() {  

	var nueva_moneda = "<div class='row' id=mon"+mon+">"+						
		"<div class='col-xs-4'><select class='form-control' name='moneda[]' required id ='combomoneda"+mon+"'></select></div>"+
		"<div class='col-xs-3'><input type='text' class='moneda' name='monedaMonto[]' id='input_moneda"+mon+"' value='0' size='7'></div>"+
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

function nuevaCompra(){	
	$('#totalPagoFinal').val(0);			
	$('#totalVueltoFinal').val(0);	
	$('#total_moneda').text(0);
	$("#detalle").html('');
	$("#totalCompra").text('0');
	$("#totalCompraFinal").val('0');
	$("#proveedorid").val('');
	$(".moneda").val('0');
	$("#comboproveedor").val('');
	$("#detalle_moneda").html('');
}

function nuevoProveedor()
{
    $('#form')[0].reset(); 
    $('.form-group').removeClass('has-error'); 
	$('.panel-body').removeClass('has-error'); 
    $('.help-block').empty();
    $('#modal_form').modal('show'); 
    $('.modal-title').text('Agregar Proveedores');
	$('.modal-backdrop').remove();
	cargar_provincias(0);
}


function saveProveedor()
{
    $('#btnSave').text('Guardando...'); 
    $('#btnSave').attr('disabled',true); 
    var url,men;

	url = Site+"proveedores/ajax_add";
	men="Se creo el proveedor correctamente";
 
	var formData = new FormData($('#form')[0]);
    $.ajax({
        url : url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(data)
        {			
            if(data.status)
            {
                $('#modal_form').modal('hide');
				//reload_table();
				$.notify({
                   title: '<strong>Correcto!</strong>',
                   message: men
               },{
                   type: 'success'
               });
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
            }
            $('#btnSave').text('Guardar');
            $('#btnSave').attr('disabled',false); 
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
			$.notify({
                   title: '<strong>Error!</strong>',
                   message: 'Se produjo un error al crear el usuario.'
               },{
                   type: 'danger'
               });
            $('#btnSave').text('Guardar'); 
            $('#btnSave').attr('disabled',false); 

        }
    });
}



</script>
