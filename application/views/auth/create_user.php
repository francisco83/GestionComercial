<?php echo form_open("auth/create_user");?>


<div class="container-fluid">
	<div class="col-xs-12 col-md-8">

	<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Alta de Usuario</h3>
	</div>
      
      <div id="infoMessage"><?php echo $message;?></div>

		<div class="panel-body">
			<div class="form-group">
                        <?php echo lang('create_user_fname_label', 'first_name');?>                      
                        <?php echo form_input($first_name);?>  
                  </div> 
                  
                  <div class="form-group">
                        <?php echo lang('create_user_lname_label', 'last_name');?>
                        <?php echo form_input($last_name);?>  
			</div> 

			<div class="form-group">
                        <?php echo lang('create_user_company_label', 'company');?> 
                        <?php echo form_input($company);?>                           
			</div>

			<div class="form-group">
                        <?php echo lang('create_user_email_label', 'email');?> 
                        <?php echo form_input($email);?>
                  </div>                  
                  
			<div class="form-group">
                        <?php echo lang('create_user_phone_label', 'phone');?>
                        <?php echo form_input($phone);?>                              
                  </div>

                  <div class="form-group">                        
                        <?php echo lang('create_user_password_label', 'password');?>                                          
                        <?php echo form_input($password);?>
                  </div>

                  <div class="form-group">
                        <?php echo lang('create_user_password_confirm_label', 'password_confirm');?>
                        <?php echo form_input($password_confirm);?>  
                  </div>
                  
		</div>
		<div class="panel-footer">
			<input class="btn btn-info" type="submit" value="Guardar">
		</div>


	</div>
</div>

     
      <?php
      if($identity_column!=='email') {
          echo '<p>';
          echo lang('create_user_identity_label', 'identity');
          echo '<br />';
          echo form_error('identity');
          echo form_input($identity);
          echo '</p>';
      }
      ?>

<?php echo form_close();?>






