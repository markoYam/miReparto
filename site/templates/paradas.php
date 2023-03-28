
<?php

require_once("header.php");
require_once("sidebar.php");

?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h2 class="mt-4">Nueva Parada</h2>
            <form id="nueva-parada-form" method="post">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-floating mb-3 mb-md-0">
                            <select class="form-control" id="id_Ruta" name="idRuta" >
                              <option value="value1" selected>--Seleccionar--</option>
                              <option value="value2">Value 3</option>
                            </select>
                            <label for="Ruta">Id ruta</label>
                        </div>
                    </div>
                </div>

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
                        <div class="form-floating mb-3 mb-md-0">
                            <select class="form-control" id="id_status" name="idStatus" >
                              <option value="0" selected>--Seleccionar--</option>
                              <option value="1">Generado</option>
                              <option value="2">Entregado</option>
                              <option value="3">Cancelado</option>
                            </select>
                            <label for="status">Id estatus</label>
                        </div>
                    </div>
                </div>


                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="number" class="form-control" id="id_latitud" name="latitud" required placeholder="Latitud" />
                            <label for="latitud">Latitud</label>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="number" class="form-control" id="id_longitud" name="longitud" required placeholder="Longitud" />
                            <label for="longitud">Longitud</label>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <textarea type="text" class="form-control" id="id_comentario" name="comentario" placeholder="Id Ruta" rows="10" cols="50"></textarea>
                            <label for="comentario">Comentarios</label>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="number" class="form-control" id="id_total" name="total" required placeholder="Total" />
                            <label for="total">Total</label>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="id_cliente" name="cliente" required placeholder="Cliente" />
                            <label for="Cliente">Cliente</label>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 mb-0">
                        <button type="submit" class="btn btn-primary btn-block">Guardar</button>
                </div>
            </form>

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