<?php  $this->load->view("partial/encabezado"); ?>
<div class="container">
	<div class="row">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h4>Cocheras</h4>
			</div>					
			<div class="panel-body">


            <div class=" col-md-12 cochera" id='id1'>1<div class="cochera_contenido">Automóvil</div></div>
            <div class=" col-md-12 cochera" id='id2'>2<div class="cochera_contenido ocupada">Automóvil</div></div>
            <div class=" col-md-12 cochera" id='id3'>3<div class="cochera_contenido">Automóvil</div></div>
            <div class=" col-md-12 cochera" id='id4'>4<div class="cochera_contenido ocupada">Automóvil</div></div>
            <div class=" col-md-12 cochera" id='id5'>5<div class="cochera_contenido ocupada">Camioneta</div></div>
            <div class=" col-md-12 cochera">6<div class="cochera_contenido ">Motocicleta</div></div>
            <div class=" col-md-12 cochera">7<div class="cochera_contenido ">Motocicleta</div></div>
            <div class=" col-md-12 cochera">8<div class="cochera_contenido ">Motocicleta</div></div>
            <div class=" col-md-12 cochera">9<div class="cochera_contenido ">Motocicleta</div></div>
			<div class=" col-md-12 cochera">10<div class="cochera_contenido ">Motocicleta</div></div>
 
            <div class=" col-md-12 cochera">11<div class="cochera_contenido">Automóvil</div></div>
            <div class=" col-md-12 cochera">12<div class="cochera_contenido ocupada">Automóvil</div></div>
            <div class=" col-md-12 cochera">13<div class="cochera_contenido">Automóvil</div></div>
            <div class=" col-md-12 cochera">14<div class="cochera_contenido">Automóvil</div></div>
            <div class=" col-md-12 cochera">15<div class="cochera_contenido">Camioneta</div></div>
            <div class=" col-md-12 cochera">16<div class="cochera_contenido ">Motocicleta</div></div>
            <div class=" col-md-12 cochera">17<div class="cochera_contenido ">Motocicleta</div></div>
            <div class=" col-md-12 cochera">18<div class="cochera_contenido ">Camioneta</div></div>
            <div class=" col-md-12 cochera">19<div class="cochera_contenido ocupada">Motocicleta</div></div>
            <div class=" col-md-12 cochera">20<div class="cochera_contenido ocupada">Motocicleta</div></div>

			</div>
		</div>
	</div>
</div>


<style>	
    .cochera{
        border: 1px solid black;
        width: 90px;
        height: 160px;
        margin-left: 10px;
        margin-right: 10px;
        margin-top: 40px;
        
        text-align: center;
        background-color: darkorange;
        border-radius: 5%;
    }
    .cochera_contenido{        
        height: 70%;
        background-color: white;
    }
    .ocupada{
        background-color: gray;
        color: white;
    }
</style>

    <div class="container">
             
    </div>
    


<div id="ModalConfig" class="modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="panel-body">
                <h3 style="text-align:center">Datos Cochera</h3>
                <span class="help-block"></span>
                <input type="text" hidden id="CommandId" />
                <label>Cliente:</label></br>
                <label>Fecha de Alta:</label></br>
                <label>Fecha Último Pago:</label></br>
                <label>Estado:</label></br>
                <div class="modal-footer">                
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    for(i=0;i<=19;i++){
        $("#id"+i).click(function(){
            console.log("ingreso");
            $("#ModalConfig").modal("show");
        });
    }
    
</script>
