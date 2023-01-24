<?php
  require_once 'controladores/controlador.php';

  $controlador = new controlador();

  if(isset($_GET) && isset($_GET["accion"])){
    $accion = (string)filter_input(INPUT_GET, "accion", FILTER_UNSAFE_RAW);

    if(method_exists($controlador, $accion)){
      if($accion == "actentrada" || $accion == "delentrada" || $accion == "listadoUsuario"){
        $id = filter_input(INPUT_GET, "id", FILTER_UNSAFE_RAW);
        $controlador->$accion($id);
      }else{
        $controlador->$accion();
      }
    }else{
      $controlador->index();
    }
  }else{
    $controlador->index();
  }

?>