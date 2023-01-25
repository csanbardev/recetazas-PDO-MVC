<!DOCTYPE html>
<html lang="es">
<?php require_once 'includes/head.php' ?>

<body>
  <?php  require_once 'includes/nav.php' ?>
  <div class="container center">
    <?php foreach ($parametros["mensajes"] as $mensaje) : ?>
      <div class="alert alert-<?= $mensaje["tipo"] ?>"><?= $mensaje["mensaje"] ?></div>
    <?php endforeach; ?>
    


    <?php 
    $datos = $parametros['datos'];
    foreach ($datos as $dato) : 
    ?>

      <div class="shadow-lg card" style="width:400px">
        <img class="card-img-top" src=<?='images/'.$dato['imagen'] ?> alt="Card image">
        <div class="card-body">
          <h4 class="card-title"><?= $dato['titulo'] ?></h4>
          <p class="card-text"><?= $dato['descripcion'] ?>.</p>
          <span class="badge badge-primary">Autor: <?= $dato['nick'] ?></span><br>
          <span class="badge badge-secondary"><?= $dato['nombre'] ?></span>
          <span class="badge badge-secondary"><?= $dato['fecha'] ?></span>
          <div class="pt-4">
            <button class="btn btn-secondary">Modificar</button>
            <button class="btn btn-danger">Borrar</button>
          </div>
        </div>
        

    <?php endforeach; 
    ?>
  </div>
</body>

</html>