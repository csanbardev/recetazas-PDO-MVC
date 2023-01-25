<?php
class modelo
{
  private $conexion;
  private $host = "localhost";
  private $user = "root";
  private $pass = "";
  private $db = "bdblog";

  public function __construct()
  {
    $this->conectar();
  }


  public function conectar()
  {
    $resultModelo = ["correcto" => FALSE, "datos" => NULL, "error" => NULL];
    try {
      $this->conexion = new PDO(
        "mysql:host=$this->host;dbname=$this->db",
        $this->user,
        $this->pass
      );
      $this->conexion->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
      );
      $resultModelo["correcto"] = TRUE;
    } catch (PDOException $ex) {
      $resultModelo["error"] = $ex->getMessage();
    }
    return $resultModelo;
  }

  public function listarEntradas()
  {
    $return = [
      "correcto" => false,
      "datos" => null,
      "error" => null
    ];

    try {
      $sql = "select * from entradas 
      inner join usuarios on entradas.usuario_id=usuarios.id
      inner join categorias on entradas.categoria_id=categorias.id";

      $resultsquery = $this->conexion->query($sql);

      if ($resultsquery) {
        $return['correcto'] = true;
        $return['datos'] = $resultsquery->fetchAll(PDO::FETCH_ASSOC);
      }
    } catch (PDOException $ex) {
      $return['error'] = $ex->getMessage();
    }

    return $return;
  }

  public function delentrada($id)
  {

    $return = [
      "correcto" => false,
      "error" => null
    ];
    //Si hemos recibido el id y es un número realizamos el borrado...
    if ($id && is_numeric($id)) {
      try {
        //Inicializamos la transacción
        $this->conexion->beginTransaction();
        //Definimos la instrucción SQL parametrizada 
        $sql = "DELETE FROM entradas WHERE id=:id";
        $query = $this->conexion->prepare($sql);
        $query->execute(['id' => $id]);
        //Supervisamos si la eliminación se realizó correctamente... 
        if ($query) {
          $this->conexion->commit();  // commit() confirma los cambios realizados durante la transacción
          $return["correcto"] = true;
        } // o no :(
      } catch (PDOException $ex) {
        $this->conexion->rollback(); // rollback() se revierten los cambios realizados durante la transacción
        $return["error"] = $ex->getMessage();
      }
    } else {
      $return["correcto"] = false;
    }

    return $return;
  }

  public function addentrada($datos){
    $return = [
      "correcto" => false,
      "error" => null
  ];

  try {
    //Inicializamos la transacción
    $this->conexion->beginTransaction();
    //Definimos la instrucción SQL parametrizada 
    $sql = "INSERT INTO entradas(usuario_id,categoria_id,titulo,imagen, descripcion, fecha)
                       VALUES (:usuario_id,:categoria_id,:titulo, :imagen, :descripcion, :fecha)";
    // Preparamos la consulta...
    $query = $this->conexion->prepare($sql);
    // y la ejecutamos indicando los valores que tendría cada parámetro
    $query->execute([
        'usuario_id' => $datos["id"],
        'categoria_id' => $datos["categoria"],
        'titulo' => $datos["titulo"],
        'imagen' => $datos["imagen"],
        'descripcion' => $datos["descripcion"],
        'fecha' => $datos["fecha"]
    ]); //Supervisamos si la inserción se realizó correctamente... 
    if ($query) {
      $this->conexion->commit(); // commit() confirma los cambios realizados durante la transacción
      $return["correcto"] = true;
    }// o no :(
  } catch (PDOException $ex) {
    $this->conexion->rollback(); // rollback() se revierten los cambios realizados durante la transacción
    $return["error"] = $ex->getMessage();
    //die();
  }

  return $return;
  }


  public function actentrada($datos){
    $return = [
      "correcto" => false,
      "error" => null
  ];

  try {
    //Inicializamos la transacción
    $this->conexion->beginTransaction();
    //Definimos la instrucción SQL parametrizada 
    $sql = "UPDATE entradas 
      SET 
        usuario_id = :usuario_id, 
        categoria_id = :categoria_id, 
        titulo = :titulo, 
        imagen = :imagen, 
        descripcion = :descripcion, 
        fecha = :fecha
          WHERE id=:id";
    $query = $this->conexion->prepare($sql);
    $query->execute([
        'id' => $datos["id"],
        'usuario_id' => $datos["usuario_id"],
        'categoria_id' => $datos["categoria_id"],
        'titulo' => $datos["titulo"],
        'imagen' => $datos["imagen"],
        'descripcion' => $datos["descripcion"],
        'fecha' => $datos["fecha"]
    ]);
    //Supervisamos si la inserción se realizó correctamente... 
    if ($query) {
      $this->conexion->commit();  // commit() confirma los cambios realizados durante la transacción
      $return["correcto"] = true;
    }// o no :(
  } catch (PDOException $ex) {
    $this->conexion->rollback(); // rollback() se revierten los cambios realizados durante la transacción
    $return["error"] = $ex->getMessage();
    //die();
  }

  return $return;
  }

  public function listarEntradasUsuario($id){
    $return = [
      "correcto" => FALSE,
      "datos" => NULL,
      "error" => NULL
  ];

  if (is_numeric($id)) {
    try {
      $sql = "select entradas.id, entradas.usuario_id, entradas.categoria_id, entradas.titulo, entradas.imagen, entradas.descripcion, entradas.fecha, usuarios.nick, categorias.nombre from entradas 
      inner join usuarios on entradas.usuario_id=usuarios.id
      inner join categorias on entradas.categoria_id=categorias.id
        where entradas.usuario_id=:id;";
      $query = $this->conexion->prepare($sql);
      $query->execute(['id' => $id]);
      //Supervisamos que la consulta se realizó correctamente... 
      if ($query) {
        $return["correcto"] = true;
        $return["datos"] = $query->fetchAll(PDO::FETCH_ASSOC);
      }// o no :(
    } catch (PDOException $ex) {
      $return["error"] = $ex->getMessage();
      //die();
    }
  }

  return $return;
  }

  public function obtenerUsuario($nick, $pass){
    $return = [
      "correcto" => false,
      "datos" => null,
      "error" => null
    ];

    if(isset($nick)&&isset($pass)){
      try{
        $sql = "select * from usuarios where nick=:nick and password=:pass";
        $query = $this->conexion->prepare($sql);
        $query->execute(['nick' => $nick, 'pass' => $pass]);

        if($query){
          $return["correcto"] = true;
          $return["datos"] = $query->fetch(PDO::FETCH_ASSOC);
        }
      }catch(PDOException $ex){
        $return["error"] = $ex->getMessage();
      }
    }

    return $return;
    
  }

  public function listarCategorias(){
    $return = [
      "correcto" => false,
      "datos" => null,
      "error" => null
    ];

    try {
      $sql = "select * from categorias";

      $resultsquery = $this->conexion->query($sql);

      if ($resultsquery) {
        $return['correcto'] = true;
        $return['datos'] = $resultsquery->fetchAll(PDO::FETCH_ASSOC);
      }
    } catch (PDOException $ex) {
      $return['error'] = $ex->getMessage();
    }

    return $return;
  }
  
}
