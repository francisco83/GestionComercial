//$(document).on("ready", main);
//main();


// function main(){

// 	console.log("ingreso");
// 	mostrarDatos("",1,5);

	
// 	$("input[name=busqueda]").keyup(function(){
// 		textobuscar = $(this).val();
// 		valoroption = $("#cantidad").val();
// 		mostrarDatos(textobuscar,1,valoroption);
// 	});

// 	$("body").on("click",".paginacion li a",function(e){
// 		e.preventDefault();
// 		valorhref = $(this).attr("href");
// 		valorBuscar = $("input[name=busqueda]").val();
// 		valoroption = $("#cantidad").val();
// 		mostrarDatos(valorBuscar,valorhref,valoroption);
// 	});

// 	$("#cantidad").change(function(){
// 		valoroption = $(this).val();
// 		valorBuscar = $("input[name=busqueda]").val();
// 		mostrarDatos(valorBuscar,1,valoroption);
// 	});
// }
function mostrarDatos(valorBuscar,pagina,cantidad){
	$.ajax({
		url : "clientes/mostrar",
		type: "POST",
		data: {buscar:valorBuscar,nropagina:pagina,cantidad:cantidad},
		dataType:"json",
		success:function(response){
			
			filas = "";
			$.each(response.clientes,function(key,item){
				filas+="<tr><td>"+item.id+"</td><td>"+item.codigo+"</td><td>"+item.nombres+"</td><td>"+item.apellidos+"</td><td>"+item.dni+"</td><td>"+item.email+"</td><td>"+item.celular+"</td></tr>";
			});
			$("#tbclientes tbody").html(filas);
			cargarPaginado(response, valorBuscar,pagina,cantidad);

			// $("#tbclientes tbody").html(filas);
			// linkseleccionado = Number(pagina);
			// //total registros
			// totalregistros = response.totalregistros;
			// //cantidad de registros por pagina
			// cantidadregistros = response.cantidad;

			// numerolinks = Math.ceil(totalregistros/cantidadregistros);
			// paginador = "<ul class='pagination'>";
			// if(linkseleccionado>1)
			// {
			// 	paginador+="<li><a href='1'>&laquo;</a></li>";
			// 	paginador+="<li><a href='"+(linkseleccionado-1)+"' '>&lsaquo;</a></li>";

			// }
			// else
			// {
			// 	paginador+="<li class='disabled'><a href='#'>&laquo;</a></li>";
			// 	paginador+="<li class='disabled'><a href='#'>&lsaquo;</a></li>";
			// }
			// //muestro de los enlaces 
			// //cantidad de link hacia atras y adelante
 			// cant = 2;
 			// //inicio de donde se va a mostrar los links
			// pagInicio = (linkseleccionado > cant) ? (linkseleccionado - cant) : 1;
			// //condicion en la cual establecemos el fin de los links
			// if (numerolinks > cant)
			// {
			// 	//conocer los links que hay entre el seleccionado y el final
			// 	pagRestantes = numerolinks - linkseleccionado;
			// 	//defino el fin de los links
			// 	pagFin = (pagRestantes > cant) ? (linkseleccionado + cant) :numerolinks;
			// }
			// else 
			// {
			// 	pagFin = numerolinks;
			// }

			// for (var i = pagInicio; i <= pagFin; i++) {
			// 	if (i == linkseleccionado)
			// 		paginador +="<li class='active'><a href='javascript:void(0)'>"+i+"</a></li>";
			// 	else
			// 		paginador +="<li><a href='"+i+"'>"+i+"</a></li>";
			// }
			// //condicion para mostrar el boton sigueinte y ultimo
			// if(linkseleccionado<numerolinks)
			// {
			// 	paginador+="<li><a href='"+(linkseleccionado+1)+"' >&rsaquo;</a></li>";
			// 	paginador+="<li><a href='"+numerolinks+"'>&raquo;</a></li>";

			// }
			// else
			// {
			// 	paginador+="<li class='disabled'><a href='#'>&rsaquo;</a></li>";
			// 	paginador+="<li class='disabled'><a href='#'>&raquo;</a></li>";
			// }
			
			// paginador +="</ul>";
			// $(".paginacion").html(paginador);

		}
	});
}
