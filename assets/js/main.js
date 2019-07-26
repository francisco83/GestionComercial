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
				$.notify({
                   title: '<strong>Error!</strong>',
                   message: 'Se produjo un error al eliminar el registro.'
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
