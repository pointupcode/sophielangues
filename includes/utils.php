<?php
$db = [
  'host' => 'localhost',
  'username' => 'root',
  'password' => 'matiaspacu11',
  'db' => 'sophielangues',
  'charset' => 'utf8mb4'
];
  //Abrir conexion a la base de datos
  function connect($db)
  {
      try {
          $conn = new PDO("mysql:host={$db['host']};dbname={$db['db']}; charset=utf8", $db['username'], $db['password'],
		  array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		  

          // set the PDO error mode to exception
          $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

          return $conn;
      } catch (PDOException $exception) {
          exit($exception->getMessage());
      }
  }


 //Obtener parametros para updates
 function getParams($input)
 {
    $filterParams = [];
    foreach($input as $param => $value)
    {
            $filterParams[] = "$param=:$param";
    }
    return implode(", ", $filterParams);
	}

  //Asociar todos los parametros a un sql
	function bindAllValues($statement, $params)
  {
		foreach($params as $param => $value)
    {
				$statement->bindValue(':'.$param, $value);
		}
		return $statement;
   }
 ?>