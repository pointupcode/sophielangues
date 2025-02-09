<?php	


if(!isset($_SESSION)){session_start();} 

function getBrowser() { 
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";
    
    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
      $platform = 'linux';
    }elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
      $platform = 'mac';
    }elseif (preg_match('/windows|win32/i', $u_agent)) {
      $platform = 'windows';
    }

    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)){
      $bname = 'Internet Explorer';
      $ub = "MSIE";
    }elseif(preg_match('/Firefox/i',$u_agent)){
      $bname = 'Mozilla Firefox';
      $ub = "Firefox";
    }elseif(preg_match('/OPR/i',$u_agent)){
      $bname = 'Opera';
      $ub = "Opera";
    }elseif(preg_match('/Chrome/i',$u_agent) && !preg_match('/Edge/i',$u_agent)){
      $bname = 'Google Chrome';
      $ub = "Chrome";
    }elseif(preg_match('/Safari/i',$u_agent) && !preg_match('/Edge/i',$u_agent)){
      $bname = 'Apple Safari';
      $ub = "Safari";
    }elseif(preg_match('/Netscape/i',$u_agent)){
      $bname = 'Netscape';
      $ub = "Netscape";
    }elseif(preg_match('/Edge/i',$u_agent)){
      $bname = 'Edge';
      $ub = "Edge";
    }elseif(preg_match('/Trident/i',$u_agent)){
      $bname = 'Internet Explorer';
      $ub = "MSIE";
    }
    
    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
      // we have no matching number just continue
    }
    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
      //we will have two since we are not using 'other' argument yet
      //see if version is before or after the name
      if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
        $version= $matches['version'][0];
      }else {
        $version= $matches['version'][1];
      }
    }else {
      $version= $matches['version'][0];
    }
    
    // check if we have a number
    if ($version==null || $version=="") {$version="?";}
    
    return array(
      'userAgent' => $u_agent,
      'name'      => $bname,
      'version'   => $version,
      'platform'  => $platform,
      'pattern'    => $pattern
    );
    } 
    
   $ua=getBrowser();

function getUserIpAddr(){
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        //ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        //ip pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}



if(isset($_GET['au']) && $_POST['au']!=''){$au = $_POST['au'];}else{$au='';}
if(isset($_POST['email_login']) && $_POST['email_login']!=''){$usuario = $_POST['email_login'];}else{$usuario='';}
if(isset($_POST['contrasenia']) && $_POST['contrasenia']!=''){$pospas = $_POST['contrasenia'];}else{$pospas='';}
if(empty($usuario)){
$EsError = 'ERROR';
$error = '<p class="alert alert-danger AvisoError"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ¡Falta el E-MAIL!</p>';
$datos_a_enviar = array($EsError, $error);
echo json_encode($datos_a_enviar);

}else if(empty($pospas)){ 
$EsError = 'ERROR';
$error = '<p class="alert alert-danger AvisoError"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ¡Falta la CONTRASE&Ntilde;A!</p>';
$datos_a_enviar = array($EsError, $error);
echo json_encode($datos_a_enviar);

}else{

include_once "utils.php";	
$dbConn = connect($db);
$query = "SELECT id_estudiante, NombreUsuario, Contrasenia, ActivoNoActivo FROM estudiantes WHERE NombreUsuario=:usuario LIMIT 1";
$sql = $dbConn->prepare($query);
$sql->bindParam(':usuario', $usuario);
$sql->execute();
$user   = $sql->fetch(PDO::FETCH_ASSOC);
$num = $sql->rowCount();

if($num < 1 ){

$EsError = 'ERROR';
$error = '<p class="alert alert-danger AvisoError"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> El usuario que ingresaste no existe.</p>';
$datos_a_enviar = array($EsError, $error);
echo json_encode($datos_a_enviar);


}else{	

$Id_usuario_obtenido=$user["id_estudiante"];
$pQ=$user["Contrasenia"];
$ActivoNoActivo=$user["ActivoNoActivo"];

if (password_verify($pospas, $pQ)){
if($ActivoNoActivo=="PASSWORD"){
    $EsError = 'ERROR';
    $error = '<p class="alert alert-danger AvisoError"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Tu cuenta se encuentra  bloqueada porque cambiaste la contraseña. Revisá tu casilla de email. Si no lo encontrás, no olvides verificar la carpeta de SPAM.</p>';
    $datos_a_enviar = array($EsError, $error);
    echo json_encode($datos_a_enviar);
    }else if($ActivoNoActivo=="BLOQUEADO"){
$EsError = 'ERROR';
$error = '<p class="alert alert-danger AvisoError"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Tu cuenta se encuentra inactiva. Contactanos para reactivarla. :D</p>';
$datos_a_enviar = array($EsError, $error);
echo json_encode($datos_a_enviar);
}else{
   	
	$sql="SELECT E.id_estudiante,E.NombreUsuario,E.Nombre,E.Apellido,E.Email,E.Pais,E.Divisa,E.TipoUsuario,E.Avatar,E.ActivoNoActivo, A.avatar_urlimg
	FROM estudiantes E 
  LEFT JOIN avatars A ON A.id_avatar=E.Avatar
	WHERE E.id_estudiante=:us
     AND E.ActivoNoActivo = 'ACTIVO'";
$sql = $dbConn->prepare($sql);
$sql->bindValue(':us', $Id_usuario_obtenido);
$sql->execute();
$datausuario = $sql->fetchAll();

foreach ($datausuario as $row) {	
$_SESSION['loggedin'] = true;
$_SESSION['estudiante'] = $row['id_estudiante']."_".$row['NombreUsuario'];
$_SESSION['nombreusuario'] = $row['NombreUsuario'];
$_SESSION['idestudiante'] = $row['id_estudiante'];
$_SESSION['nombre'] = $row['Nombre'];
$_SESSION['apellido'] = $row['Apellido'];
$_SESSION['email'] = $row['Email'];
$_SESSION['pais'] = $row['Pais'];
$_SESSION['divisa'] = $row['Divisa'];
$_SESSION['tipousuario'] = $row['TipoUsuario'];
$_SESSION['avatar'] = $row['Avatar'];
$_SESSION['avatarURL'] = $row['avatar_urlimg'];
$_SESSION['start'] = time();
$_SESSION['expire'] = $_SESSION['start'] + (1 * 60) ;
}

mt_srand (time());
$numero_aleatorio = mt_rand(1000000,999999999);

$sqlRand = "UPDATE estudiantes
        SET cookie='$numero_aleatorio'
        WHERE id_estudiante = '$Id_usuario_obtenido'";
$sql = $dbConn->prepare($sqlRand);
if($sql->execute()){



$ipcliente=getUserIpAddr();
$userbrowser= "Navegador: ".$ua['name'] . " Versión: " . $ua['version'] . ". En SO " .$ua['platform'] . " reporta: <br >" . $ua['userAgent'];
$descrip="LOGIN";
$sqlhistorial="INSERT INTO estudiantes_historial (id_estudiante, hist_descripcion, hist_url_referencia, hist_usr_ip, hist_data_browser) 
VALUES (:idestudiante, :descrip, :urlref, :ipusr, :browser)";
$sql = $dbConn->prepare($sqlhistorial);
$sql->bindParam(':idestudiante', $_SESSION['idestudiante']);
$sql->bindParam(':descrip', $descrip);
$sql->bindParam(':urlref', $au);
$sql->bindParam(':ipusr', $ipcliente);
$sql->bindParam(':browser', $userbrowser);
$sql->execute();

$EsError = 'OK';
$error = '<p class="alert alert-success AvisoError"><i class="fa fa-check-circle" aria-hidden="true"></i> Acceso correcto para '.$_SESSION['nombre'].'</p>
<p><a href="miscursos">Ingresar a tus cursos</a></p>
';
$datos_a_enviar = array($EsError, $error);
echo json_encode($datos_a_enviar);


}



}//IF BLOQUEADO 

} else { 

$EsError = 'ERROR';
$error = '<p class="alert alert-danger AvisoError"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> CONTRASE&Ntilde;A INCORRECTA</p>';
$datos_a_enviar = array($EsError, $error);
echo json_encode($datos_a_enviar);
	
} 
} 
} 
$db = NULL; 
$dbConn = NULL; 
$sql = NULL;


?>