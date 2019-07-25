<?php echo form_open(uri_string());?>

<div class="container-fluid">
	<div class="col-xs-12 col-md-8">

            <div class="panel panel-primary">
                  <div class="panel-heading">
                  <h3 class="panel-title">Editar Usuario</h3>
            </div>
            
            <div id="infoMessage"><?php echo $message;?></div>

            <div class="panel-body">
                  <div class="form-group">
                        <?php echo lang('edit_user_fname_label', 'first_name');?> <br />
                        <?php echo form_input($first_name);?>
                  </div>

                  <div class="form-group">
                        <?php echo lang('edit_user_lname_label', 'last_name');?> <br />
                        <?php echo form_input($last_name);?>
                  </div>

                  <div class="form-group">
                        <?php echo lang('edit_user_company_label', 'company');?> <br />
                        <?php echo form_input($company);?>
                  </div>

                  <div class="form-group">
                        <?php echo lang('edit_user_phone_label', 'phone');?> <br />
                        <?php echo form_input($phone);?>
                  </div>

                  <div class="form-group">
                        <?php echo lang('edit_user_password_label', 'password');?> <br />
                        <?php echo form_input($password);?>
                  </div>

                  <div class="form-group">
                        <?php echo lang('edit_user_password_confirm_label', 'password_confirm');?><br />
                        <?php echo form_input($password_confirm);?>
                  </div>

                  <div class="form-group" style="margin-left: 25px;">
                        <?php if ($this->ion_auth->is_admin()): ?>

                        <h3><?php echo lang('edit_user_groups_heading');?></h3>
                        <?php foreach ($groups as $group):?>
                        <label class="checkbox">
                        <?php
                              $gID=$group['id'];
                              $checked = null;
                              $item = null;
                              foreach($currentGroups as $grp) {
                                    if ($gID == $grp->id) {
                                    $checked= ' checked="checked"';
                                    break;
                                    }
                              }
                        ?>
                        <input type="checkbox" name="groups[]" value="<?php echo $group['id'];?>"<?php echo $checked;?>>
                        <?php echo htmlspecialchars($group['name'],ENT_QUOTES,'UTF-8');?>
                        </label>
                        <?php endforeach?>
                  </div>                  
                        <?php endif ?>

                        <?php echo form_hidden('id', $user->id);?>
                        <?php echo form_hidden($csrf); ?>

            </div>
            <div class="panel-footer">
                  <input class="btn btn-info" type="submit" value="Guardar">
            </div>
	</div>
</div>            
<!-- 

      <p><?php echo form_submit('submit', lang('edit_user_submit_btn'));?></p> -->

<?php echo form_close();?>
