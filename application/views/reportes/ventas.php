<?php  $this->load->view("partial/encabezado"); ?>
<div class="container">
	<div class="row">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h4>Reporte de Ventas</h4>
			</div>					
			<div class="panel-body">

				<form action="" id="form_insert">

					<div class="row">
						<div class="col-md-2 col-xs-3">
							<div class="form-group">
									<label for="fecha">Fecha Desde:</label>
									<input class="form-control" id="fechadesde" name="fechadesde" required type="date">
							</div>  						
						</div>
						<div class="col-md-2 col-xs-3">
							<div class="form-group">
									<label for="fecha">Fecha Hasta:</label>
									<input class="form-control" id="fechahasta" name="fechahasta" required type="date">
							</div>  						
						</div>	
						<div class="col-md-2 col-xs-3">
							<div class="form-group">
								<label></label>
								<a onclick="filtrar_venta($('#fechadesde').val(),$('#fechahasta').val())" class="form-control btn btn-info"><i class="glyphicon glyphicon-search"></i>Filtrar</a>												
							</div>
						</div>	
						<div class="col-md-1 col-xs-3">
							<div class="form-group">
								<label></label>
								<a onclick="filtrar_venta_print($('#fechadesde').val(),$('#fechahasta').val())" class="form-control btn btn-primary"><i class="glyphicon glyphicon-print"></i></a>												
							</div>
						</div>	
						<div class="col-md-1 col-xs-3">
							<div class="form-group">
								<label></label>
								<a onclick="filtrar_venta_excel($('#fechadesde').val(),$('#fechahasta').val())" class="form-control btn btn-primary"><i class="glyphicon glyphicon-floppy-save"></i></a>												
							</div>
						</div>									
					</div>	

					<div class="row">
						<div class="tbl_grid">
							<table id="tbl" class="table table-bordered table-hover">
								<thead>
									<tr>
										<th>#</th>
										<th>Fecha</th>
										<th>Cliente</th>										
										<th>Total Venta</th>
										<th>Total Pago</th>
										<th>Total Vuelto</th>
									</tr>
								</thead>
								<tbody id="detalle">
								</tbody>
							</table>
						</div>
					</div>


			</div><!-- fin pbody -->
			
			</form>

			</div>

			</div>
		</div>
	</div>
</div>

<script>
	$(function() {	
		$("#fechadesde").val(hoyFecha());
		$("#fechahasta").val(hoyFecha());
	});


	function filtrar_venta(fecha_desde,fecha_hasta){
	var i=1;		
	var total=0;
	var monto=0;
	var vuelto=0;
	$('#detalle').html('');
	$.ajax({
		url : "../ventas/ventasXFechas",
		type: "POST",
		data: {fecha_desde:fecha_desde,fecha_hasta:fecha_hasta},
		dataType:"json",
		success:function(response){			
			filas = "";
			$.each(response,function(key,item){				
				 filas+="<tr>"+
				 "<td>"+i+"</td>"+
			     "<td>"+StrToFecha(item.fecha)+"</td>"+
				 "<td>"+item.nombre+" "+item.apellido+"</td>"+
				 "<td class='r'>"+item.total+"</td>"+		
				 "<td class='r'>"+item.monto+"</td>"+		
				 "<td class='r'>"+item.vuelto+"</td>"+		
				 "</tr>";
				 i++;

				 total = total + parseFloat(item.total);
				 monto = monto + parseFloat(item.monto);
				 vuelto = vuelto + parseFloat(item.vuelto);
			});

			filas+="<tr>"+
				 "<td></td>"+
			     "<td></td>"+
				 "<td class='r'><strong>TOTAL</strong></td>"+
				 "<td class='r'><strong>"+parseFloat(total).toFixed(2)+"</strong></td>"+		
				 "<td class='r'><strong>"+parseFloat(monto).toFixed(2)+"</strong></td>"+		
				 "<td class='r'><strong>"+parseFloat(vuelto).toFixed(2)+"</strong></td>"+		
				 "</tr>";

			$('#detalle').append(filas);
		}
	});
	}
	function filtrar_venta_print(fecha_desde,fecha_hasta){
		location.href = 'ventasXFechas?fecha_desde='+fecha_desde+'&fecha_hasta='+fecha_hasta+'';
	}
	function filtrar_venta_excel(fecha_desde,fecha_hasta){
		location.href = '../Ventas/createXLS?fecha_desde='+fecha_desde+'&fecha_hasta='+fecha_hasta+'';

	}

</script>