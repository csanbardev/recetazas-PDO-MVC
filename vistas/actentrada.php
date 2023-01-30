<!DOCTYPE html>
<html lang="es">
<?php require_once 'includes/head.php' ?>

<body>
  <?php require_once 'includes/nav.php' ?>

  <div class="container center">
    <?php foreach ($parametros["mensajes"] as $mensaje) : ?>
      <div class="alert alert-<?= $mensaje["tipo"] ?>"><?= $mensaje["mensaje"] ?></div>
    <?php endforeach; ?>
    <form <?='action=index.php?accion=actEntrada&id='. $_GET['id'] ?> method="POST" enctype="multipart/form-data">
      <label for="titulo">Título
        <input name="txttitulo" class="form-control" type="text" value="<?= $parametros["datos"]["txttitulo"] ?>">
      </label>
      <br>
      <label for="descripcion">Descripción
        <textarea name="txtdescripcion" class="form-control" name="" id="" cols="30" rows="10" >
        <?= $parametros["datos"]["txtdescripcion"] ?>
        </textarea>
      </label>
      <br>
      <label for="fecha">Inserta la fecha
        <input name="dtfecha" class="form-control" type="date" name="" id="" value="<?= $parametros["datos"]["dtfecha"] ?>">
      </label>
      <br>
      <?php
        if($parametros['datos']['imagen'] != null && $parametros['datos']['imagen'] != ""){
          echo 'Imagen de la entrada: <img src="images/'.$parametros['datos']['imagen'].'" width="200">';
        }
      ?>
      <br>
      <label for="imagen">Inserta la imagen
        <input name="imagen" class="form-control" type="file" name="" id="" >
      </label>
      <br>
      <label for="categoria">Elige una categoría
        <select class="form-control" name="slcategoria" id="">
          <?php
          foreach ($parametros['categorias'] as $ctg) :
          ?>
            <option value=<?= $ctg['id'] ?>><?= $ctg['nombre'] ?></option>
          <?php endforeach; ?>

        </select>
      </label>
      <br>
      <input type="hidden" name="txtid" value=<?= $_GET['id']?>>
      <input type="hidden" name="txtusuario" value="<?= $parametros["datos"]["txtusuario"] ?>">
      <input class="btn btn-primary" type="submit" name="submit">
    </form>
  </div>
</body>

</html>