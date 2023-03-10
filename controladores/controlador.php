<?php
require_once 'modelos/modelo.php';
require_once './vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;

class controlador
{
  private $modelo;
  private $mensajes;

  public function __construct()
  {
    $this->modelo = new modelo();
    $this->mensajes = [];
    session_start(); // prepara la web para aceptar sesiones
  }

  /**
   * Muestra la pestaña de inici con el listado completo de las entradas sin operaciones
   */
  public function index()
  {
    $orden = "desc"; // por defecto, el orden será descendente

    // Almacenamos en el array 'parametros[]'los valores que vamos a mostrar en la vista
    $parametros = [
      "tituloventana" => "Recetazas | Últimas entradas",
      "datos" => null,
      "mensajes" => [],
      "paginacion" => null
    ];

    // si se especifica el orden, lo aplicamos aquí
    if (isset($_GET['orden'])) {
      $orden = $_GET['orden'];
    }


    // Realizamos la consulta y almacenmos los resultados en la variable $resultModelo
    $resultModelo = $this->modelo->listarEntradas($orden);
    // Si la consulta se realizó correctamente transferimos los datos obtenidos
    // de la consulta del modelo ($resultModelo["datos"]) a nuestro array parámetros
    // ($parametros["datos"]), que será el que le pasaremos a la vista para visualizarlos
    if ($resultModelo["correcto"]) :
      $parametros["datos"] = $resultModelo["datos"];
      $parametros["paginacion"] = $resultModelo['paginacion'];
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

  /**
   * Muestra las entradas del usuario que haya iniciado sesión
   */
  public function listadoUsuario($id)
  {
    $orden = "desc"; // por defecto el orden será descendente
    // Almacenamos en el array 'parametros[]'los valores que vamos a mostrar en la vista
    $parametros = [
      "tituloventana" => "Recetazas | Usuario",
      "datos" => NULL,
      "mensajes" => [],
      "paginacion" => null
    ];
    if (isset($id) && is_numeric($id)) {
      // comprobamos que se haya especificado un orden
      if (isset($_GET['orden'])) {
        $orden = $_GET['orden'];
      }
      // Realizamos la consulta y almacenmos los resultados en la variable $resultModelo
      $resultModelo = $this->modelo->listarEntradasUsuario($id, $orden);
      // Si la consulta se realizó correctamente transferimos los datos obtenidos
      // de la consulta del modelo ($resultModelo["datos"]) a nuestro array parámetros
      // ($parametros["datos"]), que será el que le pasaremos a la vista para visualizarlos
      if ($resultModelo["correcto"]) :
        $parametros["datos"] = $resultModelo["datos"];
        $parametros['paginacion'] = $resultModelo['paginacion'];
        //Definimos el mensaje para el alert de la vista de que todo fue correctamente
        $this->mensajes[] = [
          "tipo" => "success",
          "mensaje" => "El listado se realizó correctamente"
        ];

        // inserto el registro de logs
        $resultModelo = $this->modelo->insertarlog([
          "fecha" => date('y-m-d'),
          'hora' => date('H:i:s'),
          "operacion" => 'listar entradas',
          "usuario" => $_SESSION['nick']
        ]);


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

  /**
   * Muestra todas las entradas de todos los usuarios para la vista del administrador
   */
  public function listadoAdmin()
  {
    $orden = "desc"; // por defecto, el orden será descendente

    $parametros = [
      "tituloventana" => "Recetazas | Administrador",
      "datos" => null,
      "mensajes" => [],
      "paginacion" => null
    ];

    // comprobamos si se ha especificado un orden
    if (isset($_GET['orden'])) {
      $orden = $_GET['orden'];
    }

    // Realizamos la consulta y almacenmos los resultados en la variable $resultModelo
    $resultModelo = $this->modelo->listarEntradas($orden);
    // Si la consulta se realizó correctamente transferimos los datos obtenidos
    // de la consulta del modelo ($resultModelo["datos"]) a nuestro array parámetros
    // ($parametros["datos"]), que será el que le pasaremos a la vista para visualizarlos
    if ($resultModelo["correcto"]) :
      $parametros["datos"] = $resultModelo["datos"];
      $parametros['paginacion'] = $resultModelo['paginacion'];
      //Definimos el mensaje para el alert de la vista de que todo fue correctamente
      $this->mensajes[] = [
        "tipo" => "success",
        "mensaje" => "El listado se realizó correctamente"
      ];

      // inserto el registro de logs
      $resultModelo = $this->modelo->insertarlog([
        "fecha" => date('y-m-d'),
        'hora' => date('H:i:s'),
        "operacion" => 'listar entradas',
        "usuario" => $_SESSION['nick']
      ]);

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

  /**
   * Toma los datos del formulario de sesión y, si todo es correcto, crear la sesión
   * redirigiendo luego al listado correspondiente
   */
  public function iniciarSesion()
  {
    $parametros = [
      "tituloventana" => "Recetazas | Inicio",
      "datos" => null,
      "mensajes" => []
    ];

    if (isset($_POST) && !empty($_POST) && isset($_POST['submit'])) {
      $nick = $_POST['txtnick'];
      $password = $_POST['txtpass'];

      $resultModelo = $this->modelo->obtenerUsuario($nick, $password);

      if ($resultModelo['correcto']) {
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

        // inserto el registro de logs
        $resultModelo = $this->modelo->insertarlog([
          "fecha" => date('y-m-d'),
          'hora' => date('H:i:s'),
          "operacion" => 'iniciar sesión',
          "usuario" => $_SESSION['nick']
        ]);

        if (!$resultModelo['correcto']) {
          $this->mensajes[] = [
            "tipo" => "danger",
            "mensaje" => "Error al insertar logs"
          ];
        }
      } else {
        $this->mensajes[] = [
          "tipo" => "danger",
          "mensaje" => "Error al iniciar sesión <br /> ({$resultModelo["error"]})"
        ];
      }
    }

    $parametros['mensajes'] = $this->mensajes;

    if ($resultModelo['correcto']) {
      $_SESSION['rol'] == "user" ? $this->listadoUsuario($parametros['datos']['id']) : $this->listadoAdmin();
    } else {
      $this->index();
    }
  }

  /**
   * Muestra la pantalla de añadir entradas y, además, procesa los datos que se le pasen
   * por el formulario para crear la entrada nueva en la base de datos
   * 
   */
  public function addEntrada()
  {

    $errores = array();

    // Actúa si se pulsa el botón de guardar
    if (isset($_POST) && !empty($_POST) && isset($_POST['submit'])) {
      $id = $_POST['txtid'];
      $categoria = $_POST['slcategoria'];


      // VALIDO LOS CAMPOS DEL FORMULARIO QUE LO NECESITEN

      // validación del título
      if (
        !empty($_POST["txttitulo"])
        && (strlen($_POST["txttitulo"]) <= 20)
      ) {
        $titulo = trim($_POST["txttitulo"]);
        $titulo = filter_var($titulo, FILTER_UNSAFE_RAW);
      } else {
        $errores["txttitulo"] = "El título introducido no es válido :(";
      }

      // validación de la descripción
      if (
        !empty($_POST["txtdescripcion"])
        && (strlen($_POST["txtdescripcion"]) <= 300)
      ) {
        $descripcion = trim($_POST["txtdescripcion"]);
        $descripcion  = filter_var($descripcion, FILTER_UNSAFE_RAW);
      } else {
        $errores["txtdescripcion"] = "La descripcion introducida no es válida :(";
      }

      // validación de la fecha (que se haya asignado)
      if (!empty($_POST['dtfecha'])) {
        $fecha = $_POST['dtfecha'];
      } else {
        $errores["dtfecha"] = "Introduce una fecha";
      }


      // Cargamos la imagen al servidor
      $imagen = null;

      if (isset($_FILES['imagen']) && !empty($_FILES['imagen']['tmp_name'])) {

        // compruebo que exista el directorioa y si no lo creo
        if (!is_dir('images')) {
          $dir = mkdir('images', 0777, true);
        } else {
          $dir = true;
        }

        if ($dir) {
          $nombrefichimg = time() . "_" . $_FILES['imagen']['name'];

          $movfichimg = move_uploaded_file($_FILES['imagen']['tmp_name'], "images/" . $nombrefichimg);
          $imagen = $nombrefichimg;

          if ($movfichimg) {
            $imagendescargada = true;
          } else {
            $imagencargada = false;
            $this->mensajes[] = [
              "tipo" => "danger",
              "mensaje" => "Error: la imagen no se ha cargado"
            ];
            $errores['imagen'] = "Error: la imagen no se ha cargado";
          }
        }
      } else { // si no hay imagen, lanza el error
        $errores["imagen"] = "Introduce una imagen";
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
          // inserto el registro de logs
          $resultModelo = $this->modelo->insertarlog([
            "fecha" => date('y-m-d'),
            'hora' => date('H:i:s'),
            "operacion" => 'añadir',
            "usuario" => $_SESSION['nick']
          ]);



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



    $parametros = [
      "tituloventana" => "Recetazas | Añadir",
      "datos" => [
        "txttitulo" => isset($titulo) ? $titulo : "",
        "txtdescripcion" => isset($descripcion) ? $descripcion : "",
        "dtfecha" => isset($fecha) ? $fecha : "",
        "slcategoria" => isset($categoria) ? $categoria : "",
        "imagen" => isset($imagen) ? $imagen : ""
      ],
      "categorias" => null,
      "mensajes" => [],
      "errores" => $errores
    ];

    $resultModelo = $this->modelo->listarCategorias();

    if ($resultModelo['correcto']) {
      $this->mensajes[] = [
        "tipo" => "success",
        "mensaje" => "Sesión iniciada con éxito"
      ];
      $parametros["categorias"] = $resultModelo["datos"];
    } else {
      $this->mensajes[] = [
        "tipo" => "danger",
        "mensaje" => "Error al iniciar sesión <br /> ({$resultModelo["error"]})"
      ];
    }

    $parametros['mensajes'] = $this->mensajes;
    include_once 'vistas/addentrada.php';
  }

  /**
   * Elimina la entrada que se le pase por GET y redirige al listado del usuario correspondiente
   * 
   */
  public function delEntrada()
  {
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
        // inserto el registro de logs
        $resultModelo = $this->modelo->insertarlog([
          "fecha" => date('y-m-d'),
          'hora' => date('H:i:s'),
          "operacion" => 'eliminar',
          "usuario" => $_SESSION['nick']
        ]);


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
    $_SESSION['rol'] == "user" ? $this->listadoUsuario($_SESSION['id']) : $this->listadoAdmin();
  }

  /**
   * Borra las variables de sesión, la cierra y redirige a la página de inicio
   */
  public function cerrarSesion()
  {
    unset($_SESSION['id']);
    unset($_SESSION['nick']);
    unset($_SESSION['iniciada']);
    session_destroy();

    $this->index();
  }

  /**
   * Actualiza la entrada con los datos que se le pasen por el formulario
   * 
   */
  public function actEntrada()
  {
    $errores = array();

    $valtitulo = "";
    $valdescripcion = "";
    $valfecha = null;
    $valcategoria = "";
    $valimagen = "";
    $valusuario = "";

    // si el usuario pulsa en actualizar
    if (isset($_POST['submit'])) {
      $id = $_POST['txtid']; // id de la entrada
      $valusuario = $_POST['txtusuario']; // id del usuario de la entrada

      // campos validables
      $nuevotitulo = $_POST['txttitulo'];
      $nuevadescripcion = $_POST['txtdescripcion'];
      $nuevafecha = $_POST['dtfecha'];
      $nuevacategoria = $_POST['slcategoria'];
      $nuevaimagen = "";

      // VALIDO LOS CAMPOS DEL FORMULARIO

      // validación del título
      if (
        !empty($_POST["txttitulo"])
        && (strlen($_POST["txttitulo"]) <= 20)
      ) {
        $nuevotitulo = trim($_POST["txttitulo"]);
        $nuevotitulo = filter_var($nuevotitulo, FILTER_UNSAFE_RAW);
      } else {
        $errores["txttitulo"] = "El título introducido no es válido :(";
      }

      // validación de la descripción
      if (
        !empty($_POST["txtdescripcion"])
        && (strlen($_POST["txtdescripcion"]) <= 300)
      ) {
        $nuevadescripcion = trim($_POST["txtdescripcion"]);
        $nuevadescripcion  = filter_var($nuevadescripcion, FILTER_UNSAFE_RAW);
      } else {
        $errores["txtdescripcion"] = "La descripcion introducida no es válida :(";
      }





      $imagen = null;

      if (isset($_FILES['imagen']) && !empty($_FILES['imagen']['tmp_name'])) {
        if (!is_dir("images")) {
          $dir = mkdir("images", 0777, true);
        } else {
          $dir = true;
        }

        // tras verificar la carpeta, movemos el fichero
        if ($dir) {
          $nombrefichimg = time() . "-" . $_FILES["imagen"]["name"];
          // Movemos el fichero de la carpeta temportal a la nuestra
          $movfichimg = move_uploaded_file($_FILES["imagen"]["tmp_name"], "images/" . $nombrefichimg);
          $imagen = $nombrefichimg;
          // Verficamos la carga
          if ($movfichimg) {
            $imagencargada = true;
          } else {

            $imagencargada = false;
            $errores["imagen"] = "Error: La imagen no se cargó correctamente! :(";
            $this->mensajes[] = [
              "tipo" => "danger",
              "mensaje" => "Error: La imagen no se cargó correctamente! :("
            ];
          }
        }
      } else {
        $errores['imagen'] = "Introduce una imagen para la entrada";
      }

      $nuevaimagen = $imagen;


      if (count($errores) == 0) {
        $resultModelo = $this->modelo->actentrada([
          'id' => $id,
          'titulo' => $nuevotitulo,
          "descripcion" => $nuevadescripcion,
          'fecha' => $nuevafecha,
          'categoria_id' => $nuevacategoria,
          'imagen' => $nuevaimagen,
          'usuario_id' => $valusuario
        ]);

        if ($resultModelo['correcto']) {
          $this->mensajes[] = [
            "tipo" => "success",
            "mensaje" => "La entrada se ha actualizado correctamente"
          ];

          // inserto el registro de logs
          $resultModelo = $this->modelo->insertarlog([
            "fecha" => date('y-m-d'),
            'hora' => date('H:i:s'),
            "operacion" => 'actualizar',
            "usuario" => $_SESSION['nick']
          ]);
        } else {
          $this->mensajes[] = [
            "tipo" => "danger",
            "mensaje" => "Datos de registro de entrada erróneos!! :("
          ];
        }
      } else { // ha encontrado errores de validación
        $this->mensajes[] = [
          "tipo" => "danger",
          "mensaje" => "Rellena bien todos los campos"
        ];
      }

      $valtitulo = $nuevotitulo;
      $valdescripcion = $nuevadescripcion;
      $valfecha = $nuevafecha;
      $valcategoria = $nuevacategoria;
      $valimagen = $nuevaimagen;
    } else { // solo está mostrando el formulario de edición con los datos
      if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = $_GET['id'];

        // obtengo los datos de la entrada que se intenta actualizar
        $resultModelo = $this->modelo->listarEntrada($id);

        if ($resultModelo['correcto']) {
          $this->mensajes[] = [
            "tipo" => "success",
            "mensaje" => "Los datos de la entrada se obtuvieron correctamente!! :)"
          ];

          $valtitulo = $resultModelo['datos']["titulo"];
          $valdescripcion = $resultModelo['datos']['descripcion'];
          $valfecha = $resultModelo['datos']['fecha'];
          $valcategoria = $resultModelo['datos']['categoria_id'];
          $valimagen = $resultModelo['datos']['imagen'];
          $valusuario = $resultModelo['datos']['usuario_id'];
        } else {
          $this->mensajes[] = [
            "tipo" => "danger",
            "mensaje" => "No se pudieron obtener los datos de la entrada!! :( <br/>({$resultModelo["error"]})"
          ];
        }
      }
    }

    $parametros = [
      "tituloventana" => "Recetazas | Actualizar",
      "datos" => [
        "txttitulo" => $valtitulo,
        "txtdescripcion"  => $valdescripcion,
        "dtfecha"  => $valfecha,
        "slcategoria"  => $valcategoria,
        "imagen"    => $valimagen,
        "txtusuario" => $valusuario
      ],
      "categorias" => null,
      "mensajes" => $this->mensajes,
      "errores" => $errores
    ];

    $resultModelo = $this->modelo->listarCategorias();

    if ($resultModelo['correcto']) {
      $this->mensajes[] = [
        "tipo" => "success",
        "mensaje" => "Sesión iniciada con éxito"
      ];
      $parametros["categorias"] = $resultModelo["datos"];
    } else {
      $this->mensajes[] = [
        "tipo" => "danger",
        "mensaje" => "Error al iniciar sesión <br /> ({$resultModelo["error"]})"
      ];
    }

    $parametros['mensajes'] = $this->mensajes;
    //Mostramos la vista actentrada
    include_once 'vistas/actentrada.php';
  }

  /**
   * Recupera todas las entradas de la base de datos y las imprime en pdf
   */
  public function imprimirEntradas()
  {
    $parametros = [
      "tituloventana" => "Blog | Últimas entradas",
      "datos" => null,
      "mensajes" => []
    ];
    // Realizamos la consulta y almacenmos los resultados en la variable $resultModelo
    $resultModelo = $this->modelo->listarTodas();
    
    if ($resultModelo["correcto"]) :
      $parametros["datos"] = $resultModelo["datos"];
      //Definimos el mensaje para el alert de la vista de que todo fue correctamente
      $this->mensajes[] = [
        "tipo" => "success",
        "mensaje" => "El listado se realizó correctamente"
      ];

      // hacemos la impresión en pdf
      $html2pdf = new Html2Pdf();
      $html2pdf->writeHTML("<h1>Listado de entradas</h1>");

      foreach ($parametros["datos"] as $dato) {
        $html2pdf->writeHTML('Titulo: ' . $dato['titulo'] . '<br>');
        $html2pdf->writeHTML('Descripción: ' . $dato['descripcion'] . '<br>');
        $html2pdf->writeHTML('Autor: ' . $dato['nick'] . '<br>');
        $html2pdf->writeHTML('Categoría: ' . $dato['nombre'] . '<br>');
        $html2pdf->writeHTML('Fecha de publicación: ' . $dato['fecha'] . '<br>');
        // $html2pdf->writeHTML('Imagen: '. '<img style="width:100px;height:100px;" src=images/' . $dato['imagen']. 'alt="Card image">');
        $html2pdf->writeHTML('_______________________________________<br>');
      }



      $html2pdf->output();
    else :
      
      $this->mensajes[] = [
        "tipo" => "danger",
        "mensaje" => "El listado no pudo realizarse correctamente!! :( <br/>({$resultModelo["error"]})"
      ];
    endif;
    
    $parametros["mensajes"] = $this->mensajes;

    include_once 'vistas/inicio.php';
  }

  /**
   * Recupera los datos de logs de la base de datos y los muestra en una tabla
   */
  public function listarLogs()
  {
    $parametros = [
      "tituloventana" => "Recetazas | Logs",
      "datos" => null,
      "mensajes" => [],
      "paginacion" => null
    ];
    $orden = "desc"; // por defecto, el orden será descendente

    // si se especifica el orden, lo aplicamos aquí
    if (isset($_GET['orden'])) {
      $orden = $_GET['orden'];
    }
    $resultModelo = $this->modelo->listarLogs($orden);

    if ($resultModelo['correcto']) {
      $parametros['datos'] = $resultModelo['datos'];
      $parametros["paginacion"] = $resultModelo['paginacion'];
      $this->mensajes[] = [
        "tipo" => "success",
        "mensaje" => "El listado se realizó correctamente"
      ];
    } else {
      $this->mensajes[] = [
        "tipo" => "danger",
        "mensaje" => "El listado no pudo realizarse correctamente!! :( <br/>({$resultModelo["error"]})"
      ];
    }

    $parametros["mensajes"] = $this->mensajes;

    include_once 'vistas/logs.php';
  }

  /**
   * Elimina un registro de log
   */
  public function eliminarLog()
  {
    if (isset($_GET['id']) && (is_numeric($_GET['id']))) {
      $id = $_GET["id"];
      //Realizamos la operación de suprimir el usuario con el id=$id
      $resultModelo = $this->modelo->eliminarLog($id);
      //Analizamos el valor devuelto por el modelo para definir el mensaje a 
      //mostrar en la vista listado
      if ($resultModelo["correcto"]) :
        $this->mensajes[] = [
          "tipo" => "success",
          "mensaje" => "Se eliminó correctamente el registro de log"
        ];
        // inserto el registro de logs
        $resultModelo = $this->modelo->insertarlog([
          "fecha" => date('y-m-d'),
          'hora' => date('H:i:s'),
          "operacion" => 'eliminar log',
          "usuario" => $_SESSION['nick']
        ]);


      else :
        $this->mensajes[] = [
          "tipo" => "danger",
          "mensaje" => "Algo ha fallado al elimninar el registro de log <br/>({$resultModelo["error"]})"
        ];
      endif;
    } else { //Si no recibimos el valor del parámetro $id generamos el mensaje indicativo:
      $this->mensajes[] = [
        "tipo" => "danger",
        "mensaje" => "Error al acceder a la id del log"
      ];
    }

    $this->listarLogs();
  }

  /**
   * Recupera todos los logs de la base de datos y los imprime en PDF
   */
  public function imprimirLogs()
  {
    $parametros = [
      "tituloventana" => "Recetazas | Logs",
      "datos" => null,
      "mensajes" => []
    ];
    $orden = "desc"; // por defecto, el orden será descendente

    // Realizamos la consulta y almacenmos los resultados en la variable $resultModelo
    $resultModelo = $this->modelo->listarLogsCompleto();
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

      // hacemos la impresión en pdf
      $html2pdf = new Html2Pdf();
      $html2pdf->writeHTML("<h1>Listado de logs</h1>");

      foreach ($parametros["datos"] as $dato) {
        $html2pdf->writeHTML('Operación: ' . $dato['operacion'] . '<br>');
        $html2pdf->writeHTML('Usuario: ' . $dato['usuario'] . '<br>');
        $html2pdf->writeHTML('Fecha: ' . $dato['fecha'] . '<br>');
        $html2pdf->writeHTML('Hora: ' . $dato['hora'] . '<br>');
        $html2pdf->writeHTML('_______________________________________<br>');
      }



      $html2pdf->output();
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

    include_once 'vistas/logs.php';
  }
}
