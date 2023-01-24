<?php
require_once 'modelos/modelo.php';


class controlador
{
  private $modelo;
  private $mensajes;

  public function __construct()
  {
    $this->modelo = new modelo();
    $this->mensajes = [];
    session_start();
  }

  public function index()
  {
    // Almacenamos en el array 'parametros[]'los valores que vamos a mostrar en la vista
    $parametros = [
      "tituloventana" => "Blog | Últimas entradas",
      "datos" => null,
      "mensajes" => []
    ];
    // Realizamos la consulta y almacenmos los resultados en la variable $resultModelo
    $resultModelo = $this->modelo->listarEntradas();
    // Si la consulta se realizó correctamente transferimos los datos obtenidos
    // de la consulta del modelo ($resultModelo["datos"]) a nuestro array parámetros
    // ($parametros["datos"]), que será el que le pasaremos a la vista para visualizarlos
    if ($resultModelo["correcto"]) :
      $parametros["datos"] = $resultModelo["datos"];
      //Definimos el mensaje para el alert de la vista de que todo fue correctamente
      $this->mensajes[] = [
        "tipo" => "success",
        "mensaje" => "El listado se realizó correctamente"
      ];
    else :
      //Definimos el mensaje para el alert de la vista de que se produjeron errores al realizar el listado
      $this->mensajes[] = [
        "tipo" => "danger",
        "mensaje" => "El listado no pudo realizarse correctamente!! :( <br/>({$resultModelo["error"]})"
      ];
    endif;
    //Asignanis al campo 'mensajes' del array de parámetros el valor del atributo 
    //'mensaje', que recoge cómo finalizó la operación:
    $parametros["mensajes"] = $this->mensajes;

    include_once 'vistas/inicio.php';
  }

  public function listadoUsuario($id)
  {
    // Almacenamos en el array 'parametros[]'los valores que vamos a mostrar en la vista
    $parametros = [
      "tituloventana" => "Blog | Usuario",
      "datos" => NULL,
      "mensajes" => []
    ];
    if (isset($id) && is_numeric($id)) {
      // Realizamos la consulta y almacenmos los resultados en la variable $resultModelo
      $resultModelo = $this->modelo->listarEntradasUsuario($id);
      // Si la consulta se realizó correctamente transferimos los datos obtenidos
      // de la consulta del modelo ($resultModelo["datos"]) a nuestro array parámetros
      // ($parametros["datos"]), que será el que le pasaremos a la vista para visualizarlos
      if ($resultModelo["correcto"]) :
        $parametros["datos"] = $resultModelo["datos"];
        //Definimos el mensaje para el alert de la vista de que todo fue correctamente
        $this->mensajes[] = [
          "tipo" => "success",
          "mensaje" => "El listado se realizó correctamente"
        ];
      else :
        //Definimos el mensaje para el alert de la vista de que se produjeron errores al realizar el listado
        $this->mensajes[] = [
          "tipo" => "danger",
          "mensaje" => "El listado no pudo realizarse correctamente!! :( <br/>({$resultModelo["error"]})"
        ];
      endif;
    }
    //Asignanis al campo 'mensajes' del array de parámetros el valor del atributo 
    //'mensaje', que recoge cómo finalizó la operación:
    $parametros["mensajes"] = $this->mensajes;
    // Incluimos la vista en la que visualizaremos los datos o un mensaje de error
    include_once 'vistas/listado.php';
  }

  public function listadoAdmin(){
    $parametros = [
      "tituloventana" => "Blog | Administrador",
      "datos" => null,
      "mensajes" => []
    ];
    // Realizamos la consulta y almacenmos los resultados en la variable $resultModelo
    $resultModelo = $this->modelo->listarEntradas();
    // Si la consulta se realizó correctamente transferimos los datos obtenidos
    // de la consulta del modelo ($resultModelo["datos"]) a nuestro array parámetros
    // ($parametros["datos"]), que será el que le pasaremos a la vista para visualizarlos
    if ($resultModelo["correcto"]) :
      $parametros["datos"] = $resultModelo["datos"];
      //Definimos el mensaje para el alert de la vista de que todo fue correctamente
      $this->mensajes[] = [
        "tipo" => "success",
        "mensaje" => "El listado se realizó correctamente"
      ];
    else :
      //Definimos el mensaje para el alert de la vista de que se produjeron errores al realizar el listado
      $this->mensajes[] = [
        "tipo" => "danger",
        "mensaje" => "El listado no pudo realizarse correctamente!! :( <br/>({$resultModelo["error"]})"
      ];
    endif;
    //Asignanis al campo 'mensajes' del array de parámetros el valor del atributo 
    //'mensaje', que recoge cómo finalizó la operación:
    $parametros["mensajes"] = $this->mensajes;

    include_once 'vistas/listado.php';
  }

  public function iniciarSesion(){
    $parametros = [
      "tituloventana" => "Blog | Inicio",
      "datos" => null,
      "mensajes" => []
    ];

    if(isset($_POST) && !empty($_POST) && isset($_POST['submit'])){
      $nick = $_POST['txtnick'];
      $password = $_POST['txtpass'];

      $resultModelo = $this->modelo->obtenerUsuario($nick, $password);

      if($resultModelo['correcto']){
        $this->mensajes[] = [
          "tipo" => "success",
          "mensaje" => "Sesión iniciada con éxito"
        ];
        $parametros["datos"] = $resultModelo["datos"];
        //session_start();
        $_SESSION['nick'] = $_POST["txtnick"];
        $_SESSION['id'] = $parametros['datos']['id'];
        $_SESSION['iniciada'] = true;

      }else{
        $this->mensajes[] = [
          "tipo" => "danger",
          "mensaje" => "Error al iniciar sesión <br /> ({$resultModelo["error"]})"
        ];
      }
    }

    $parametros['mensajes'] = $this->mensajes;

    if($resultModelo['correcto']){
      $_POST['txtnick'] == "user"? $this->listadoUsuario($parametros['datos']['id']) : $this->listadoAdmin();
    }else{
      $this->index();
    }
  }
}
