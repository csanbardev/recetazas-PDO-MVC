<?php
  // session_start();
?>

<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
  <ul class="navbar-nav">
    <li class="nav-item active">
      <a class="nav-link" href="index.php">Inicio</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#">Link</a>
    </li>
    <li class="nav-item">
      <a class="nav-link disabled" href="#">Disabled</a>
    </li>
    <?php

    
    if (isset($_SESSION['iniciada']) && $_SESSION['iniciada']) {

      $html = '<li class="nav-item dropdown"><a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">';
      $html = $html.$_SESSION['nick'];
      $html = $html.'</a>
      <div class="dropdown-menu">
        <a class="dropdown-item" href="'.
        'index.php?accion=';

      if($_SESSION['rol']=='admin'){
        $html = $html.'listadoAdmin'.'&id=';
      } else{
        $html = $html.'listadoUsuario'.'&id=';
      } 

      $html = $html.$_SESSION['id'];
      $html = $html.'">Entradas</a>';
      if($_SESSION['rol']=='user'){ // el usuario podrá añadir entradas, el admin no
        $html = $html.'<a class="dropdown-item" href="index.php?accion=addEntrada">Añadir</a>';
      }
      $html = $html.
      '<a class="dropdown-item" href="index.php?accion=cerrarSesion">Cerrar sesión</a>
    </div>
</li>';

      echo $html;
      
    }else{
      echo
      '
      <form class="nav-item form-inline" action="index.php?accion=iniciarSesion" method="post" enctype="multipart/form-data">
        
          <input name="txtnick" class="form-control" type="text" placeholder="Usuario">
        
        
          <input name="txtpass" class="form-control" type="password" placeholder="Contraseña">
        
          <input name="submit" type="submit" class="btn btn-light" value="Login">
      </form>  
      ';
    }
    ?>

  </ul>
</nav>