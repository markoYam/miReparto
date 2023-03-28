
<?php

require_once("header.php");
require_once("sidebar.php");

?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
		    <h2 class="mt-4">Nueva Ruta</h2>
		    <form id="nueva-ruta-form" method="post">
			    <div class="row mb-3">
			        <div class="col-md-6">
			            <div class="form-floating mb-3 mb-md-0">
			                <input class="form-control" id="fecha" name="fecha" required type="date" placeholder="Fecha" />
			                <label for="fecha">Fecha</label>
			            </div>
			        </div>
			    </div>
			    <div class="row mb-3">
			        <div class="col-md-6">
			            <div class="form-floating">
			                <input type="text" class="form-control" id="Repartidor" name="Repartidor" required placeholder="Repartidor" />
			                <label for="repartidor">Repartidor</label>
			            </div>
			        </div>
			    </div>
			    <div class="row mb-3">
			        <div class="col-md-6">
			            <div class="form-floating">
			                 <input type="text" class="form-control" id="idRuteo" name="idRuteo" placeholder="Id Ruta" required />
			        		<label for="idRuteo">Id ruta</label>
			            </div>
			        </div>
			    </div>
			    
			    
			    <div class="mt-4 mb-0">
			        	<button type="submit" class="btn btn-primary btn-block">Guardar</button>
			    </div>
			</form>


		    <!--<form id="nueva-ruta-form" method="post">
		      <div class="form-group">
		        <label for="fecha">Fecha:</label>
		        <input type="date" class="form-control" id="fecha" name="fecha" required>
		      </div>
		      <div class="form-group">
		        <label for="repartidor">Repartidor:</label>
		        <input type="text" class="form-control" id="Repartidor" name="Repartidor" required>
		      </div>
		      <div class="form-group">
		        <label for="idRuteo">ID Ruta:</label>
		        <input type="text" class="form-control" id="idRuteo" name="idRuteo" required>
		      </div>
		      <button type="submit" class="btn btn-primary">Guardar</button>
		    </form>-->
        </div>
    </main>



<?php
require_once("footer.php");
?>

<script>
	$(document).ready(function(){
	  $('#nueva-ruta-form').submit(function(event) {
	    event.preventDefault();
	    $.ajax({
	      type: "POST",
	      url: "<?=URL_BASE?>ws/rutas.php",
	      data: $(this).serialize(),
	      success: function(response)
	      {
	        // Manejar la respuesta del servidor
	        console.log(response);
	      },
	      error: function(xhr, status, error)
	      {
	        // Manejar errores de la solicitud AJAX
	        console.log(xhr.responseText);
	      }
	    });
	  });
	});
</script>