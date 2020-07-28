<?php  $this->load->view("partial/encabezado"); ?>
<div class="container">
	<div class="row">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h4>Reporte de Ventas por Productos</h4>
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
										<th>Cod. Producto</th>										
										<th>Producto</th>										
										<th>Cantidad</th>
										<th>Total Venta</th>
										<th>Total Costo</th>
										<th>Ganancia</th>
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
	var totalVenta = 0;
	var totalCompra = 0;
	var ganancia = 0;
	$('#detalle').html('');
	$.ajax({
		url : "../ventas/ventasProductosXFechas",
		type: "POST",
		data: {fecha_desde:fecha_desde,fecha_hasta:fecha_hasta},
		dataType:"json",
		success:function(response){			
			filas = "";
			$.each(response,function(key,item){				
				 filas+="<tr>"+
				 "<td class='r'>"+i+"</td>"+
			     "<td class='c'>"+StrToFecha(item.fecha)+"</td>"+
				 "<td>"+item.codigoproducto+"</td>"+
				 "<td>"+item.nombre+"</td>"+		
				 "<td class='r'>"+item.cantidad+"</td>"+		
				 "<td class='r'>"+item.precioventa+"</td>"+		
				 "<td class='r'>"+item.preciocompra+"</td>"+		
				 "<td class='r'>"+item.diferencia+"</td>"+		
				 "</tr>";
				 i++;
				 totalVenta = totalVenta + parseFloat(item.precioventa);
				 totalCompra = totalCompra+ parseFloat(item.preciocompra);
				 ganancia= ganancia + parseFloat(item.diferencia);
			});
			filas+="<tr>"+
				 "<td></td>"+
			     "<td></td>"+
				 "<td></td>"+
				 "<td></td>"+
				 "<td><strong>TOTAL</strong></td>"+	
				 "<td class='r'><strong>"+totalVenta.toFixed(2)+"</strong></td>"+		
				 "<td class='r'><strong>"+totalCompra.toFixed(2)+"</strong></td>"+		
				 "<td class='r'><strong>"+ganancia.toFixed(2)+"</strong></td>"+		
				 "</tr>";

			$('#detalle').append(filas);
		}
	});
	}
	function filtrar_venta_print(fecha_desde,fecha_hasta){
		location.href = 'ventasproductosXFechas?fecha_desde='+fecha_desde+'&fecha_hasta='+fecha_hasta+'';
	}
	function filtrar_venta_excel(fecha_desde,fecha_hasta){
		location.href = '../Ventas/createXLSventasproductos?fecha_desde='+fecha_desde+'&fecha_hasta='+fecha_hasta+'';

	}

</script>
