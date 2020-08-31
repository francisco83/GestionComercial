<div class="modal" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Cliente</h3>
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
                            <label class="col-sm-2">Apellido:</label>
                            <div class="col-sm-10">
							    <input class="form-control" name="apellido" required type="text" id="apellido" placeholder="Ingrese el apellido">
                                <span class="help-block"></span>
                            </div>   
						</div> 	 	
						<div class="form-group">
                            <label class="col-sm-2">DNI/CUIT:</label>
                            <div class="col-sm-10">
							    <input class="form-control only_number" name="dni" required type="text" id="dni" placeholder="Ingrese el DNI">
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
						<div class="form-group">
                            <label class="col-sm-2">Teléfono:</label>
                            <div class="col-sm-10">
							    <input class="form-control" name="telefono" required type="text" id="telefono" placeholder="Ingrese el teléfono">
                                <span class="help-block"></span>
                            </div>   
						</div> 	 
						<div class="form-group">
                            <label class="col-sm-2">Provincia:</label>							
							<div class="col-sm-10">
							<select  class="form-control" name="provinciaId" id="provinciaId">
							</select>							    
                                <span class="help-block"></span>
                            </div> 
						</div> 	
						<div class="form-group">
                            <label class="col-sm-2">Dirección:</label>
                            <div class="col-sm-10">
							    <input class="form-control" name="direccion" required type="text" id="direccion" placeholder="Ingrese una dirección">
                                <span class="help-block"></span>
                            </div>   
						</div> 		
					</div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Guardar</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
