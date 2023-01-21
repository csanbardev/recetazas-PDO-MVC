<?php
  require_once 'modelos/modelo.php';

  
  class controlador{
    private $modelo;
    private $mensajes;

    public function __construct(){
      $this->modelo = new modelo();
      $this->mensajes = [];
    }

    public function index(){
      $parametros = ["tituloventana" => "Inicio"];

      include_once 'vistas/inicio.php';
    }
  }

?>