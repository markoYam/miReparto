<!DOCTYPE html>
<html>

<head>
  <title>Registro de Rutas</title>
  <!-- Latest compiled and minified CSS -->
  <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

  <!-- jQuery library -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

  <!-- Latest compiled JavaScript -->
  <!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> -->
</head>

<body>
  <div class="container">
    <h2>Nueva Ruta</h2>
    <form id="nueva-ruta-form" method="post">
      <div class="form-group">
        <label for="fecha">Fecha:</label>
        <input type="date" class="form-control" id="fecha" name="fecha" value="2021-05-01">
      </div>

      <div class="form-group mt-2">
        <label for="Folio">Folio:</label>
        <input type="text" class="form-control" id="Folio" name="Folio" value="1">
      </div>
      <!--repartidor field select-->
      <div class="form-group mt-2">
        <label for="idRepartidor">Repartidor:</label>
        <select class="form-control" id="idRepartidor" name="idRepartidor" required>
          <option value="-1">Seleccione un repartidor</option>
        </select>

        <!--estatus field select-->
        <div class="form-group mt-2">
          <label for="idEstatus">Estatus:</label>
          <select class="form-control" id="idEstatus" name="idEstatus" required>
            <option value="-1">Seleccione un estatus</option>
          </select>
        </div>
        <!--THIS SHOW ERRORS-->
        <div id="content-error" class="mt-3"></div>
        <button type="button" id="btn-submit" class="btn btn-primary mt-2">Guardar</button>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
    crossorigin="anonymous"></script>

  <script src="js/nuevaRuta.js"></script>
</body>

</html>