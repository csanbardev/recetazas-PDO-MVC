<?php
  // hehehe
?>

<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
  <ul class="navbar-nav">
    <li class="nav-item active">
      <a class="nav-link" href="#">Active</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#">Link</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#">Link</a>
    </li>
    <li class="nav-item">
      <a class="nav-link disabled" href="#">Disabled</a>
    </li>
    <?php
    if (isset($_SESSION['iniciada']) && $_SESSION['iniciada']) {
      echo
      '
        <li class="nav-item">
          <a class="nav-link" href="#">Hola</a>
        </li>
      ';
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