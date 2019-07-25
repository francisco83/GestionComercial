<div class="container-fluid">
	<div class="col-xs-12 col-md-8">

            <div class="panel panel-primary">
            <div class="panel-heading">
                  <h3 class="panel-title">Cambiar Contraseña</h3>
                  </div>

                        <div id="infoMessage"><?php echo $message;?></div>

                        <?php echo form_open("auth/change_password");?>

                        <div class="panel-body">
                              <div class="form-group">
                                    <?php echo lang('change_password_old_password_label', 'old_password');?> <br />
                                    <?php echo form_input($old_password);?>
                              </div>

                              <div class="form-group">
                                    <label for="new_password"><?php echo sprintf(lang('change_password_new_password_label'), $min_password_length);?></label> <br />
                                    <?php echo form_input($new_password);?>
                              </div>

                              <div class="form-group">
                                    <?php echo lang('change_password_new_password_confirm_label', 'new_password_confirm');?> <br />
                                    <?php echo form_input($new_password_confirm);?>
                              </div>

                              <?php echo form_input($user_id);?>                             
                              <input class="btn btn-info" type="submit" value="Cambiar">
                        </div>
                        <?php echo form_close();?>
            </div>

      </div>            
</div>      
