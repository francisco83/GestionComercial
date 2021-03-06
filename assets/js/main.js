function main(){
	mostrarDatos("",1,5);

	
	$("input[name=busqueda]").keyup(function(){
		textobuscar = $(this).val();
		valoroption = $("#cantidad").val();
		mostrarDatos(textobuscar,1,valoroption);
	});

	$("body").on("click",".paginacion li a",function(e){
		e.preventDefault();
		valorhref = $(this).attr("href");
		valorBuscar = $("input[name=busqueda]").val();
		valoroption = $("#cantidad").val();
		mostrarDatos(valorBuscar,valorhref,valoroption);
	});

	$("#cantidad").change(function(){
		valoroption = $(this).val();
		valorBuscar = $("input[name=busqueda]").val();
		mostrarDatos(valorBuscar,1,valoroption);
	});

}



function cargarPaginado(response,valorBuscar,pagina,cantidad){
	linkseleccionado = Number(pagina);
	//total registros
	totalregistros = response.totalregistros;
	//cantidad de registros por pagina
	cantidadregistros = response.cantidad;
	
	numerolinks = Math.ceil(totalregistros/cantidadregistros);
	paginador = "<ul class='pagination'>";
	if(linkseleccionado>1)
	{
		paginador+="<li><a href='1'>&laquo;</a></li>";
		paginador+="<li><a href='"+(linkseleccionado-1)+"' '>&lsaquo;</a></li>";
	
	}
	else
	{
		paginador+="<li class='disabled'><a href='#'>&laquo;</a></li>";
		paginador+="<li class='disabled'><a href='#'>&lsaquo;</a></li>";
	}
	//muestro de los enlaces 
	//cantidad de link hacia atras y adelante
	 cant = 2;
	 //inicio de donde se va a mostrar los links
	pagInicio = (linkseleccionado > cant) ? (linkseleccionado - cant) : 1;
	//condicion en la cual establecemos el fin de los links
	if (numerolinks > cant)
	{
		//conocer los links que hay entre el seleccionado y el final
		pagRestantes = numerolinks - linkseleccionado;
		//defino el fin de los links
		pagFin = (pagRestantes > cant) ? (linkseleccionado + cant) :numerolinks;
	}
	else 
	{
		pagFin = numerolinks;
	}
	
	for (var i = pagInicio; i <= pagFin; i++) {
		if (i == linkseleccionado)
			paginador +="<li class='active'><a href='javascript:void(0)'>"+i+"</a></li>";
		else
			paginador +="<li><a href='"+i+"'>"+i+"</a></li>";
	}
	//condicion para mostrar el boton sigueinte y ultimo
	if(linkseleccionado<numerolinks)
	{
		paginador+="<li><a href='"+(linkseleccionado+1)+"' >&rsaquo;</a></li>";
		paginador+="<li><a href='"+numerolinks+"'>&raquo;</a></li>";
	
	}
	else
	{
		paginador+="<li class='disabled'><a href='#'>&rsaquo;</a></li>";
		paginador+="<li class='disabled'><a href='#'>&raquo;</a></li>";
	}
	
	paginador +="</ul>";
	$(".paginacion").html(paginador);
	
	}
	

	function NoSelect()
{
    Message("Por favor, seleccione un registro.")
}

/*Fecha Actual*/ 
function addZero(i) {
    if (i < 10) {
        i = '0' + i;
    }
    return i;
}

function hoyFecha(){
	var hoy = new Date();
	var dd = hoy.getDate();
	var mm = hoy.getMonth()+1;
	var yyyy = hoy.getFullYear();
	
	dd = addZero(dd);
	mm = addZero(mm);

	//return dd+'/'+mm+'/'+yyyy;
	return yyyy+'-'+mm+'-'+dd;
}
/*fin fecha actual*/

/*Devuelve la fecha en el formato correcto*/
function StrToFecha(fecha){
	//var hoy = fecha;
	var dd = fecha.substr(8,2);
	var mm = fecha.substr(5,2);
	var yyyy = fecha.substr(0,4);

	return dd+'/'+mm+'/'+yyyy;
}

/*Codigo para los ABM*/

function action(option){
	var id = $("#tbl tr.selected td:first").html();

	if (id !=  undefined){
		if (option=="edit")
			edit(id);				
		if (option =="enabled")
			enabled(id);
		if (option =="delete")
			delete_(id);	
	}	
	else{
		$.notify({
			title: '<strong>Atención!</strong>',
			message: 'Seleccione una fila.'
		},{
			type: 'warning'
		});
	}
}

function reload_table(){
	mostrarDatos(valor,pag,$("#cantidad").val());	
};


function save()
{
    $('#btnSave').text('Guardando...'); 
    $('#btnSave').attr('disabled',true); 
    var url,men;

    if(save_method == 'add') {
		url = Site+controller+"/ajax_add";
		men="Se creo el registro correctamente";
    } else {
		url = Site+controller+"/ajax_update";
		men="Se actualizo el registro correctamente";
    }

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
				reload_table();
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
                   message: 'Se produjo un error al guardar.'
               },{
                   type: 'danger'
               });
            $('#btnSave').text('Guardar'); 
            $('#btnSave').attr('disabled',false); 

        }
    });
}


function MensajesError(Mensaje) {
	var mensajeError;

	if (Mensaje.search("Error Number: 1451") != -1) {
		mensajeError = 'No se puede eliminar, existen registros asociados. Debe eliminar los registros asociados o bien deshabilitar.';
	} else {
		mensajeError = 'Se produjo un error al eliminar el registro. ';
	}
	return mensajeError;
  }


function delete_(id)
{
    if(confirm('¿Esta seguro que desea eliminar el registro?'))
    {
        $.ajax({
            url : Site+controller+"/ajax_delete/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                $('#modal_form').modal('hide');
				reload_table();
				$.notify({
                   title: '<strong>Correcto!</strong>',
                   message: 'El registro se elimino correctamente.'
               },{
                   type: 'success'
               });
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
				console.log(jqXHR, textStatus, errorThrown);

				$.notify({
                   title: '<strong>Error!</strong>',
				   message: MensajesError(jqXHR.responseText)
				   
               },{
                   type: 'danger'
               });
            }
        });

    }
}


function enabled(id)
{
        $.ajax({
            url : Site+controller+"/ajax_enabled/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
				reload_table();
				$.notify({
                   title: '<strong>Correcto!</strong>',
                   message: 'El registro se actualizo correctamente.'
               },{
                   type: 'success'
               });
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
				$.notify({
                   title: '<strong>Error!</strong>',
                   message: 'Se produjo un error al actualizar el registro.'
               },{
                   type: 'danger'
               });
            }
        });
}
/**/ 




$(function ($) {
    //Solo permite numeros positivos
    $('.only_number').keypress(function (tecla) {
        if (tecla.charCode < 47 || tecla.charCode > 57) return false;
	});

	//Editar haciendo doble clic
	$("#tbl").dblclick(function(){
		var id = $("#tbl tr.selected td:first").html();
		if (id !=  undefined){
			edit(id);
		}
	});
	
});


$(function () {
	$('[data-toggle="tooltip"]').tooltip();

	//limpia la busqueda y cierra los popup
	$(document).keyup(function(e) {   
		if(e.keyCode== 27) {
			if(!$('#modal_form').hasClass('show')){		
				$("#busqueda").val(''); 
				mostrarDatos("",1,5);
				$("#busqueda").focus();
			}
			else{
				$('#modal_form').modal('hide');
				$("#busqueda").focus();
			}
		} 
	});
  });



function cargar_provincias(id){
	
	var combo_provincias='';
		 
	$.ajax({		
		url : Site+"Provincias/get_all_array/",
		type: "POST",
		dataType:"json",
		success:function(response){
			combo_provincias = "<option value='-1'>Seleccione una Provincia...</option>";	
			$.each(response,function(key,item){
				if(id != 0 && item.Id == id){
					combo_provincias+="<option value='"+item.Id+"' selected>"+item.nombre+"</option>";					
				}
				else{
					combo_provincias+="<option value='"+item.Id+"'>"+item.nombre+"</option>";
				}
			});		
			$("#provinciaId").html(combo_provincias);
		},
		error: function (jqXHR, textStatus, errorThrown)
		{
			$.notify({
					title: '<strong>Atención!</strong>',
					message: 'Se produjo un error al cargar las provincias'
				},{
					type: 'danger'
				});
		}
	});
}

