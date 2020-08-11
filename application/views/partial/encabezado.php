<!DOCTYPE html>
<html lang="ES">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Gestión</title>

	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/style.css">
	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/jquery-ui.min.css">

	<script src="<?php echo base_url();?>assets/js/jquery-3.3.1.min.js"></script>
	<script src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script>
	<script src="<?php echo base_url().'assets/js/jquery-ui.min.js'?>"></script>
	<script src="<?php echo base_url();?>assets/js/main.js"></script>
	<script src="<?php echo base_url();?>assets/js/bootstrap-notify.min.js"></script>
</head>
<body>

<?php  
if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
{	
	$this->load->view("partial/menu_user"); 
}
else{
	$this->load->view("partial/menu_admin"); 
}
?>

<script>


////jQuery(document).ready(function($) {

// if (window.history && window.history.pushState) {

//   window.history.pushState('forward', null, './#forward');

//   $(window).on('popstate', function() {
// 	alert('Back button was pressed.');
//   });

// }
//});

// $(function() {
// console.log("-->");


// if (window.history && window.history.pushState) {
// 	console.log("Toma el evento",window);

// //Agrega entradas al historial
// //window.history.pushState('forward', null, './#forward');

// //history.pushState({}, '', '/newpage');
// $(window).on('popstate', function() {
//   alert('Back button was pressed.');
// });

// }



// window.addEventListener('popstate', function(event)
// {
// 	console.log("ingreso");
// alert("location: " + document.location + ", state: " + JSON.stringify(event.state));
// });

// window.onpopstate = function(event)
// {
//     alert("location: " + document.location + ", state: " + JSON.stringify(event.state));
// };

// window.onpopstate = function(event) {
//   alert("location:,,,, " + document.location + ", state: " + JSON.stringify(event.state));
// };

// window.onhashchange = function(e) {
//   var oldURL = e.oldURL.split('#')[1];
//   var newURL = e.newURL.split('#')[1]

//   console.log("mensaje",oldURL,newURL);

// //   if (oldURL == 'share') {
// //     $('.share').fadeOut();
// //     e.preventDefault();
// //     return false;
//   }



//});
</script>
