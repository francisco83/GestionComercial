<nav class="navbar navbar-default">
	  	<div class="container-fluid">
	    	<!-- Brand and toggle get grouped for better mobile display -->
	    	<div class="navbar-header">
	      		<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
	        		<span class="sr-only">Toggle navigation</span>
	        		<span class="icon-bar"></span>
	        		<span class="icon-bar"></span>
	        		<span class="icon-bar"></span>
	      		</button>
	      		<a class="navbar-brand" href="<?php echo base_url();?>">Gestion Comercial</a>
	    	</div>

	    	<!-- Collect the nav links, forms, and other content for toggling -->
	    	<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	      		<ul class="nav navbar-nav">	        					
				  	<li><a href="<?php echo base_url();?>clientes">Clientes</a></li>	
					<li><a href="<?php echo base_url();?>productos">Productos</a></li>	
					<li><a href="<?php echo base_url();?>proveedores">Proveedores</a></li>	
					<li><a href="<?php echo base_url();?>registrar/ver">Ver servicios <span class="sr-only">(current)</span></a></li>
					<li class="dropdown">
						<a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="glyphicon glyphicon-shopping-cart"></i> Ventas
						<span class="caret"></span>
						</a>						
						<ul class="dropdown-menu">					
							<li><a href="<?php echo base_url();?>ventas">Nueva <span class="sr-only">(current)</span></a></li>
							<li><a href="<?php echo base_url();?>ventas\ver">Ver por cliente <span class="sr-only">(current)</span></a></li>
						</ul>
					</li>
					<li class="dropdown">
						<a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="glyphicon glyphicon-list-alt"></i> Reportes
						<span class="caret"></span>
						</a>						
						<ul class="dropdown-menu">					
							<li><a href="<?php echo base_url();?>reportes\ventas"> Ventas <span class="sr-only">(current)</span></a></li>
						</ul>
					</li>
					<li class="dropdown">
						<a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="glyphicon glyphicon-cog"></i> Configuración
						<span class="caret"></span>
						</a>						
						<ul class="dropdown-menu">					
							<li><a href="<?php echo base_url();?>empresas">Empresas <span class="sr-only">(current)</span></a></li>
							<li><a href="<?php echo base_url();?>sucursales">Sucursales <span class="sr-only">(current)</span></a></li>
							<li><a href="<?php echo base_url();?>users">Usuarios <span class="sr-only">(current)</span></a></li>					
							<li><a href="<?php echo base_url();?>groups">Grupos Usuarios <span class="sr-only">(current)</span></a></li>
							<li><a href="<?php echo base_url();?>tipos_servicios">Tipos de Servicios <span class="sr-only">(current)</span></a></li>					
							<li><a href="<?php echo base_url();?>tipos_pagos">Tipos de Pagos <span class="sr-only">(current)</span></a></li>					
							<li><a href="<?php echo base_url();?>tipos_monedas">Tipos de Monedas <span class="sr-only">(current)</span></a></li>					
							<li><a href="<?php echo base_url();?>categorias_productos">Categorias de Productos <span class="sr-only">(current)</span></a></li>					
						</ul>
					</li>
					<li class="dropdown active">					
						<a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="glyphicon glyphicon-user"></i> Usuario: <?php $user = $this->ion_auth->user()->row();echo $user->first_name;?>
						<span class="caret"></span>
						</a>						
						<ul class="dropdown-menu">					
							<li><a href="<?php echo base_url();?>auth/change_password">Cambiar Contraseña <span class="sr-only">(current)</span></a></li>
							<li><a href="<?php echo base_url();?>auth/logout"><i class="glyphicon glyphicon-off"></i> Cerrar Sesión <span class="sr-only">(current)</span></a></li>							
						</ul>
					</li>
	      		</ul>
	     	</div><!-- /.navbar-collapse -->
	  	</div><!-- /.container-fluid -->
	</nav>
