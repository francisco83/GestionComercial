
<!-- Bootstrap modal -->
<div class="modal" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Servicio</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
					<input type="hidden" value="" name="id"/> 
					<div class="panel-body">
						<div class="form-group">
                            <label class="col-sm-2">Nombre:</label>
                            <div class="col-sm-10">
							    <input class="form-control" name="nombre" required type="text" id="nombre" placeholder="Ingrese el nombre">
                                <span class="help-block"></span>
                            </div>   
						</div>
						<div class="form-group">
                            <label class="col-sm-2">Nombre Contacto:</label>
                            <div class="col-sm-10">
							    <input class="form-control" name="nombre_contacto" required type="text" id="nombre_contacto" placeholder="Ingrese el nombre del contacto">
                                <span class="help-block"></span>
                            </div>   
						</div> 	 	
						<div class="form-group">
                            <label class="col-sm-2">CUIT:</label>
                            <div class="col-sm-10">
							    <input class="form-control" name="cuit" required type="text" id="cuit" placeholder="Ingrese el CUIT">
                                <span class="help-block"></span>
                            </div>   
						</div> 
						<div class="form-group">
                            <label class="col-sm-2">Dirección:</label>
                            <div class="col-sm-10">
							    <input class="form-control" name="direccion" required type="text" id="direccion" placeholder="Ingrese la dirección">
                                <span class="help-block"></span>
                            </div>   
						</div>  
						<div class="form-group">
                            <label class="col-sm-2">Teléfono:</label>
                            <div class="col-sm-10">
							    <input class="form-control" name="telefono" required type="text" id="telefono" placeholder="Ingrese el teléfono">
                                <span class="help-block"></span>
                            </div>   
						</div> 	 
						<div class="form-group">
                            <label class="col-sm-2">Email:</label>
                            <div class="col-sm-10">
							    <input class="form-control" name="email" required type="text" id="email" placeholder="Ingrese el email">
                                <span class="help-block"></span>
                            </div>   
						</div>  	
					</div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="saveProveedor()" class="btn btn-primary">Guardar</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
