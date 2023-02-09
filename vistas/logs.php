<!DOCTYPE html>
<html lang="es">
<?php require_once 'includes/head.php' ?>

<body>
  <?php require_once 'includes/nav.php' ?>
  <div class="container center">
    <?php foreach ($parametros["mensajes"] as $mensaje) : ?>
      <div class="alert alert-<?= $mensaje["tipo"] ?>"><?= $mensaje["mensaje"] ?></div>
    <?php endforeach; ?>

    <h1>Listado de logs</h1>
    <table class="table table-striped">
      <tr>
        <th>Operación</th>
        <th>Usuario</th>
        <th>Fecha</th>
        <th>Hora</th>
        <th></th>
      </tr>

      <?php foreach($parametros['datos'] as $dato): ?>
        <tr>
          <td><?= $dato['operacion'] ?></td>
          <td><?= $dato['usuario'] ?></td>
          <td><?=  date("d-m-Y",strtotime($dato['fecha'])) ?></td>
          <td><?= $dato['hora'] ?></td>
          <td><a class="btn btn-danger" data-toggle="modal" data-target=<?= '#modal-' . $dato['id'] ?>>Eliminar</a></td>
        </tr>
        <!-- Ventana modal -->
        <div class="modal" id=<?= 'modal-' . $dato['id'] ?>>
          <div class="modal-dialog">
            <div class="modal-content">

              <!-- Modal Header -->
              <div class="modal-header">
                <h4 class="modal-title">Eliminar registro de log</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>

              <!-- Modal body -->
              <div class="modal-body">
                ¿Seguro que quieres borrar este registro?
              </div>

              <!-- Modal footer -->
              <div class="modal-footer">
                <a class="btn btn-danger" href=<?= 'index.php?accion=eliminarLog&id=' . $dato['id'] ?>>Aceptar</a>
                <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
              </div>

            </div>
          </div>
        </div>
     <?php endforeach; ?>   
    </table>
    <br>
    <a href="index.php?accion=imprimirLogs" class="btn btn-primary">Imprimir en pdf</a>
  </div>

</body>

</html>