<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Gestion Comercial</title>
  <link rel="icon" href="<?=base_url()?>/favicon.ico" type="image/gif">

  <link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap.min.css">
  <script src="<?php echo base_url();?>assets/js/jquery-3.3.1.min.js"></script>
  <script src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script>

</head>
<body>

<div class="container" style="margin-top: 50px;">
<div class="col-md-8">

   <h1>Sistema NN</h1>
 ¿Qué es Lorem Ipsum?
 Lorem Ipsum es simplemente el texto de relleno de las imprentas
 y archivos de texto. Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta) desconocido usó una galería de textos y los mezcló de tal manera que logró hacer un libro de textos especimen. No sólo sobrevivió 500 años, sino que tambien ingresó como texto de relleno en documentos electrónicos, quedando esencialmente igual al original. Fue popularizado en los 60s con la creación de las hojas "Letraset", las cuales contenian pasajes de Lorem Ipsum, y más recientemente con software de autoedición, como por ejemplo Aldus PageMaker, el cual incluye versiones de Lorem Ipsum.


 </div> 

 <div class="col-md-4" style="background-color: aliceblue; border: 1px solid lightgray;">
    <h1><?php echo lang('login_heading');?></h1>
   <p><?php echo lang('login_subheading');?></p>

   <div id="infoMessage"><?php echo $message;?></div>

   <?php echo form_open("auth/login");?>

   <div class="form-group">
         <label for="nombre"><?php echo lang('login_identity_label', 'identity');?></label>
         <input class="form-control" name="identity" id="identity" placeholder="Ingrese el Email">
         <span class="help-block"></span>
   </div>  
              
     <!-- <p>
       <?php echo lang('login_identity_label', 'identity');?>
       <?php echo form_input($identity);?>
     </p> -->

     <!-- <p>
       <?php echo lang('login_password_label', 'password');?>
       <?php echo form_input($password);?>
     </p> -->

     <div class="form-group">
         <label for="password"><?php echo lang('login_password_label', 'password');?></label>
         <input class="form-control" name="password" id="password" placeholder="Ingrese el password">
         <span class="help-block"></span>
   </div>  

     <p>
       <?php echo lang('login_remember_label', 'remember');?>
       <?php echo form_checkbox('remember', '1', FALSE, 'id="remember"');?>
     </p>

<!--
     <p><?php echo form_submit('submit', lang('login_submit_btn'));?></p> -->
     <div class="form-group">
       <input class="form-control btn btn-primary" type="submit" value="Ingresar">
     </div>


   <?php echo form_close();?>

   <p><a href="forgot_password"><?php echo lang('login_forgot_password');?></a></p>
 </div>

</div>
</body>
