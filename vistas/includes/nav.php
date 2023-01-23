<?php
session_start();

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
        <li class="nav-item">
          <input class="form-control" type="text" placeholder="Usuario">
        </li>
        <li class="nav-item">
          <input class="form-control" type="password" placeholder="Contraseña">
        </li>
        <li class="nav-item">
          <input type="submit" class="btn btn-light" value="Login">
        </li>
        
      ';
    }
    ?>

  </ul>
</nav>