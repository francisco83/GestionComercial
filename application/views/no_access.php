<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>404 Page Not Found</title>
<style type="text/css">
body{    
    margin:0;
}
.wrap{
    margin:0 auto;
    width:1000px;
}
.logo{
    text-align:center;
}   

</style>
</head>
<body>
    <!-- <img src="<?php echo base_url(); ?>assets/images/label.png"/>  -->
    <div class="wrap">
       <div class="logo">
		   <p style="font-size:xxx-large;">UPS!!!</p>
		   <img src="<?php echo base_url(); ?>assets/images/exclamacion.jpg" width="400px"/>
		   	   <h2>La página que busca no existe.</h2>
               <div class="sub">
                 <p><a href="<?php echo base_url(); ?>">Regresar</a></p>
               </div>
        </div>
     </div>
</body>
</html>
