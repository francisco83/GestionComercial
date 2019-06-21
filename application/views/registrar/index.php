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

		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h4>Registrar servicios</h4>
					</div>
					<div class="panel-body">	

					<div class="form-group">
						<label for="fecha">Fecha:</label>
						<input class="form-control" name="fecha" required type="date" id="fecha">
					</div> 

					<div class="form-group">
						<label for="servicio">Tipo Servicio:</label>
						<br>
						<select class="form-control" id= "itemName" style="width:500px" name="itemName"></select>
						<a href="<?php echo base_url().'index.php/servicios/agregar/'?>" class="btn btn-success"><i class="glyphicon glyphicon-plus"></i>Agregar</a>
					</div> 

					<!-- <div class="form-group">
                    <label>Title</label>
                    <input type="text" class="form-control" id="title" placeholder="Title" style="width:500px;">
                  	</div> -->

					  <input type="text" id="clienteid">
					  <div class="form-group">
                    <label>Title</label>
                    <input type="text" class="form-control" id="combobox2" placeholder="Title" style="width:500px;">
                  	</div>

					  <label>Your preferred programming language: </label>
  <select id="combobox1">
    <option value="">Select one...</option>
    <option value="ActionScript">ActionScript</option>
    <option value="AppleScript">AppleScript</option>
    <option value="Asp">Asp</option>
    <option value="BASIC">BASIC</option>
    <option value="C">C</option>
    <option value="C++">C++</option>
    <option value="Clojure">Clojure</option>
    <option value="COBOL">COBOL</option>
    <option value="ColdFusion">ColdFusion</option>
    <option value="Erlang">Erlang</option>
    <option value="Fortran">Fortran</option>
    <option value="Groovy">Groovy</option>
    <option value="Haskell">Haskell</option>
    <option value="Java">Java</option>
    <option value="JavaScript">JavaScript</option>
    <option value="Lisp">Lisp</option>
    <option value="Perl">Perl</option>
    <option value="PHP">PHP</option>
    <option value="Python">Python</option>
    <option value="Ruby">Ruby</option>
    <option value="Scala">Scala</option>
    <option value="Scheme">Scheme</option>
  </select>
</div>
  
							<div class="row">
							  		<div class="col-xs-2 celda">12/12/2019</div>
							  		<div class="col-xs-4 celda">Formateo</div>
								  	<div class="col-xs-2 celda">290</div>
								  	<div class="col-xs-4 celda">Nada que poner</div>
							  </div>							  
							  <div class="row">
							  		<div class="col-xs-2 celda">12/12/2019</div>
							  		<div class="col-xs-4 celda">Instalacion de SO windows</div>
								  	<div class="col-xs-2 celda"><input type="text"></div>
								  	<div class="col-xs-4 celda">Nada que poner</div>
							  </div>

							<table class="table table-bordered table-hover">
								<thead>
									<tr>								
										<th>Fecha</th>
										<th>Tipo Servicio</th>
										<th>Monto</th>		
										<th>Detalle</th>		
									</tr>							
								</thead>
								<tbody>
									<tr>
										<td>12/12/2019</td>
										<td>Formateo</td>
										<td>150</td>
										<td></td>
									</tr>
									<tr>
										<td>06/12/2019</td>
										<td>Limpieza PC</td>
										<td><input type="text"></td>
										<td></td>
									</tr>
								</tbody>
							</table>

					</div>					
					</div>
				</div>
			</div>
		</div>
	</div>

<script type="text/javascript">
// $(function() {
// 	$('.itemName2').select2();  
// });

$('#itemName').select2({
  placeholder: 'Seleccione Servicio',
  ajax: {
	url: 'Servicios/buscar_servicios',
	dataType: 'json',
	delay: 250,
	processResults: function (data) {
	  return {
		results: data
	  };
	},
	cache: true
  }
});


$(document).ready(function(){
            $( "#combobox2" ).autocomplete({
			  source: "<?php echo site_url('Registrar/get_autocomplete/?');?>",
			  autoFill:true,
			  select: function(event, ui){
				console.log(event,ui);
				  //console.log(ui.item.id);
				  //event.preventDefault();
				  $('#clienteid').val(ui.item.id);
            	  $("#combobox2").val(ui.item.value);
			  },
            }).autocomplete();
        });

</script>


<script>

$( function() {
    $.widget( "custom.combobox", {
      _create: function() {
        this.wrapper = $( "<span>" )
          .addClass( "custom-combobox" )
          .insertAfter( this.element );
 
        this.element.hide();
        this._createAutocomplete();
        this._createShowAllButton();
      },
 
      _createAutocomplete: function() {
        var selected = this.element.children( ":selected" ),
          value = selected.val() ? selected.text() : "";
 
        this.input = $( "<input>" )
          .appendTo( this.wrapper )
          .val( value )
          .attr( "title", "" )
          .addClass( "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )
          .autocomplete({
            delay: 0,
            minLength: 0,
            source: $.proxy( this, "_source" )
          })
          .tooltip({
            classes: {
              "ui-tooltip": "ui-state-highlight"
            }
          });
 
        this._on( this.input, {
          autocompleteselect: function( event, ui ) {
            ui.item.option.selected = true;
            this._trigger( "select", event, {
              item: ui.item.option
            });
          },
 
          autocompletechange: "_removeIfInvalid"
        });
      },
 
      _createShowAllButton: function() {
        var input = this.input,
          wasOpen = false;
 
        $( "<a>" )
          .attr( "tabIndex", -1 )
          .attr( "title", "Show All Items" )
          .tooltip()
          .appendTo( this.wrapper )
          .button({
            icons: {
              primary: "ui-icon-triangle-1-s"
            },
            text: false
          })
          .removeClass( "ui-corner-all" )
          .addClass( "custom-combobox-toggle ui-corner-right" )
          .on( "mousedown", function() {
            wasOpen = input.autocomplete( "widget" ).is( ":visible" );
          })
          .on( "click", function() {
            input.trigger( "focus" );
 
            // Close if already visible
            if ( wasOpen ) {
              return;
            }
 
            // Pass empty string as value to search for, displaying all results
            input.autocomplete( "search", "" );
          });
      },
 
      _source: function( request, response ) {
        var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
        response( this.element.children( "option" ).map(function() {
          var text = $( this ).text();
          if ( this.value && ( !request.term || matcher.test(text) ) )
            return {
              label: text,
              value: text,
              option: this
            };
        }) );
      },
 
      _removeIfInvalid: function( event, ui ) {
 
        // Selected an item, nothing to do
        if ( ui.item ) {
          return;
        }
 
        // Search for a match (case-insensitive)
        var value = this.input.val(),
          valueLowerCase = value.toLowerCase(),
          valid = false;
        this.element.children( "option" ).each(function() {
          if ( $( this ).text().toLowerCase() === valueLowerCase ) {
            this.selected = valid = true;
            return false;
          }
        });
 
        // Found a match, nothing to do
        if ( valid ) {
          return;
        }
 
        // Remove invalid value
        this.input
          .val( "" )
          .attr( "title", value + " didn't match any item" )
          .tooltip( "open" );
        this.element.val( "" );
        this._delay(function() {
          this.input.tooltip( "close" ).attr( "title", "" );
        }, 2500 );
        this.input.autocomplete( "instance" ).term = "";
      },
 
      _destroy: function() {
        this.wrapper.remove();
        this.element.show();
      }
    });
 
    $( "#combobox1" ).combobox();
    // $( "#toggle" ).on( "click", function() {
    //   $( "#combobox2" ).toggle();
    // });
  } );

</script>
