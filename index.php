<?php
  require_once 'controladores/controlador.php';

  $controlador = new controlador();

  if($_GET && $_GET["accion"]){
    $accion = filter_input(INPUT_GET, "accion", FILTER_UNSAFE_RAW);

    if(method_exists($controlador, $accion)){
      if($accion = "actentrada" || $accion = "delentrada"){
        $id = filter_input(INPUT_GET, "id", FILTER_UNSAFE_RAW);
        $controlador->$accion($id);
      }else{
        $controlador->$accion($id);
      }
    }else{
      $controlador->index();
    }
  }else{
    $controlador->index();
  }

?>