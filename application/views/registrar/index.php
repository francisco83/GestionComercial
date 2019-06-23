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
	  border-top: 1px solid black;
    	border-right: 1px solid black;
    	height: 27px;
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
								<input class="form-control" name="fecha" required type="date" id="fecha">
						</div> 
						<div class="form-group">
								<label for="servicio">Tipo Servicio:</label>
								<select class="form-control" id ="comboservicio"></select> 								
						</div> 
						<a onclick="agregarFila()" class="btn btn-success"><i class="glyphicon glyphicon-plus"></i>Agregar</a>

						<table id ="tableRegistrar" class="table table-bordered table-hover">
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
						</table>

						</div><!-- fin pbody -->

					</div>
				</div>
			</div>
</div>

	<script src="<?php echo base_url();?>assets/js/combos.js"></script>
	<script>
	
	var i = 1;
	
	$(function() {

		$("#fecha").val(hoyFecha());

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
				$("#comboservicio").html(filas);		
				$("#comboservicio").combobox();	
			}
		});

});




function agregarFila() {   

// 	$("#tableRegistrar tbody tr").click(function(){
// 				$(this).addClass('selected').siblings().removeClass('selected');    
// 				var value=$(this).find('td:first').html(); 
// });

   var htmlTags = '<tr id="fila'+i+'">'+
		'<td>' +i+'</td>'+
        '<td>' + $("#fecha").val() + '</td>'+
        '<td>' + $("#comboservicio").children("option:selected").text() + '</td>'+
        '<td>' + '<span id="Monto" contenteditable>200</span>' + '</td>'+
		'<td>' + '<span id="cantidad" contenteditable>1</span>'+'</td>'+
		'<td>' + 0 + '</td>'+
		'<td>' + '<span id="detalle" contenteditable>1</span>'+ '</td>'+
		'<td>'+'<a onclick="borrarFila('+i+')"><i class="glyphicon glyphicon-trash"></i></a>'+'</td>'+
      '</tr>';     
   $('#tableRegistrar tbody').append(htmlTags);
   i++;
}

function borrarFila(index){
	console.log("ingreso");
	$("#fila"+index).remove();

}

</script>
