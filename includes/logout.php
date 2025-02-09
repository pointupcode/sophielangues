<?php

if(!isset($_SESSION)){session_start();} 
include_once "../includes/utils.php";
$dbConn = connect($db);

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

$actual_page = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$ipcliente=getUserIpAddr();
$userbrowser= "Navegador: ".$ua['name'] . " Versión: " . $ua['version'] . ". En SO " .$ua['platform'] . " reporta: <br >" . $ua['userAgent'];
$descrip="LOGOUT";
$sqlhistorial="INSERT INTO estudiantes_historial (id_estudiante, hist_descripcion, hist_url_referencia, hist_usr_ip, hist_data_browser) 
VALUES (:idestudiante, :descrip, :urlref, :ipusr, :browser)";
$sql = $dbConn->prepare($sqlhistorial);
$sql->bindParam(':idestudiante', $_SESSION['idestudiante']);
$sql->bindParam(':descrip', $descrip);
$sql->bindParam(':urlref', $actual_page);
$sql->bindParam(':ipusr', $ipcliente);
$sql->bindParam(':browser', $userbrowser);
if($sql->execute()){
unset($_SESSION["estudiante"]); 
unset($_SESSION); 
session_destroy();
header("Location:inicio");

}

$db = NULL; 
$dbConn = NULL;
$sql = NULL;
?>
