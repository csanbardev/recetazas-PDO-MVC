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
        $_SESSION['rol'] = $parametros['datos']['rol'];

      }else{
        $this->mensajes[] = [
          "tipo" => "danger",
          "mensaje" => "Error al iniciar sesión <br /> ({$resultModelo["error"]})"
        ];
      }
    }

    $parametros['mensajes'] = $this->mensajes;

    if($resultModelo['correcto']){
      $resultModelo['datos']['rol'] == "user"? $this->listadoUsuario($parametros['datos']['id']) : $this->listadoAdmin();
    }else{
      $this->index();
    }
  }


  public function addEntrada(){
    
    $errores = array();

    // Actúa si se pulsa el botón de guardar
    if(isset($_POST) && !empty($_POST) && isset($_POST['submit'])){
      $id = $_POST['txtid'];
      $titulo = $_POST['txttitulo'];
      $descripcion = $_POST['txtdescripcion'];
      $fecha = $_POST['dtfecha'];
      $categoria = $_POST['slcategoria'];

      // Cargamos la imagen al servidor
      $imagen = null;

      if(isset($_FILES['imagen']) && !empty($_FILES['imagen']['tmp_name'])){

        // compruebo que exista el directorioa y si no lo creo
        if(!is_dir('images')){
          $dir = mkdir('images', 0777, true);
        }else{
          $dir = true;
        }

        if($dir){
          $nombrefichimg = time(). "_" . $_FILES['imagen']['name'];

          $movfichimg = move_uploaded_file($_FILES['imagen']['tmp_name'], "images/" . $nombrefichimg);
          $imagen = $nombrefichimg;

          if($movfichimg){
            $imagendescargada = true;
          }else{
            $imagencargada = false;
            $this->mensajes[] = [
              "tipo" => "danger",
              "mensaje" => "Error: la imagen no se ha cargado"
            ];
            $errores['imagen'] = "Error: la imagen no se ha cargado";
          }
        }

        // si no hay errores, se registra la entrada
        if (count($errores) == 0) {
          $resultModelo = $this->modelo->addentrada([
              'id' => $id,
              'titulo' => $titulo,
              "descripcion" => $descripcion,
              'fecha' => $fecha,
              'categoria' => $categoria,
              'imagen' => $imagen
          ]);
          if ($resultModelo["correcto"]) :
            $this->mensajes[] = [
                "tipo" => "success",
                "mensaje" => "La entrada se registró correctamente!! :)"
            ];
          else :
            $this->mensajes[] = [
                "tipo" => "danger",
                "mensaje" => "La entrada no pudo registrarse!! :( <br />({$resultModelo["error"]})"
            ];
          endif;
        } else {
          $this->mensajes[] = [
              "tipo" => "danger",
              "mensaje" => "Datos de registro de entrada erróneos!! :("
          ];
        }

      }
    }



    $parametros = [
      "tituloventana" => "Blog | Añadir entrada",
      "datos" => [
        "txttitulo" => isset($titulo) ? $titulo : "",
        "txtdescripcion" => isset($descripcion) ? $descripcion : "",
        "dtfecha" => isset($fecha) ? $fecha : "",
        "slcategoria" => isset($categoria) ? $categoria : "",
        "imagen" => isset($imagen) ? $imagen : ""
      ],
      "categorias" => null,
      "mensajes" => []
    ];

    $resultModelo = $this->modelo->listarCategorias();

    if($resultModelo['correcto']){
      $this->mensajes[] = [
        "tipo" => "success",
        "mensaje" => "Sesión iniciada con éxito"
      ];
      $parametros["categorias"] = $resultModelo["datos"];

    }else{
      $this->mensajes[] = [
        "tipo" => "danger",
        "mensaje" => "Error al iniciar sesión <br /> ({$resultModelo["error"]})"
      ];
    }
    
    $parametros['mensajes'] = $this->mensajes;
    include_once 'vistas/addentrada.php';
  }

  public function delEntrada(){
    if (isset($_GET['id']) && (is_numeric($_GET['id']))) {
      $id = $_GET["id"];
      //Realizamos la operación de suprimir el usuario con el id=$id
      $resultModelo = $this->modelo->delEntrada($id);
      //Analizamos el valor devuelto por el modelo para definir el mensaje a 
      //mostrar en la vista listado
      if ($resultModelo["correcto"]) :
        $this->mensajes[] = [
            "tipo" => "success",
            "mensaje" => "Se eliminó correctamente la entrada"
        ];
      else :
        $this->mensajes[] = [
            "tipo" => "danger",
            "mensaje" => "Algo ha fallado al elimninar la entrada <br/>({$resultModelo["error"]})"
        ];
      endif;
    } else { //Si no recibimos el valor del parámetro $id generamos el mensaje indicativo:
      $this->mensajes[] = [
          "tipo" => "danger",
          "mensaje" => "Error al acceder a la id de la entrada"
      ];
    }
    //Relizamos el listado de los usuarios
    $_SESSION['rol'] == "user"? $this->listadoUsuario($_SESSION['id']) : $this->listadoAdmin();
  }
}
