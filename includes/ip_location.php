<?php

if(!isset($_SESSION)){session_start();}
$ipactual=$_SERVER['REMOTE_ADDR'];


include "utils.php";
$dbConn =  connect($db);

$sqlIplist="SELECT ip_range_start,ip_range_end,country_code 
FROM ip_location 
WHERE (INET_ATON('$ipactual') BETWEEN INET_ATON(ip_range_start) AND INET_ATON(ip_range_end));
LIMIT 1";

$sql = $dbConn->prepare($sqlIplist);
$sql->execute();
$row = $sql->fetch(PDO::FETCH_ASSOC);
if(isset($row['country_code']) && $row['country_code']!=""){$country_code=$row['country_code'];}else{$country_code=NULL;}



if(isset($country_code) && $country_code!=""){
	$VisitorIP=$ipactual;
	$VisitorcountryCode=$country_code;
}else{
	$VisitorIP=$ipactual;
	$VisitorcountryCode="AR";
}

require_once('datauser.php');

$sqlInsertVisitor="INSERT INTO visitantes (ipactual, ipcliente, countrycode, actual_page,  userbrowser) VALUES (:ipactual, :ipcliente, :countrycode, :actual_page, :userbrowser)";
$sql = $dbConn->prepare($sqlInsertVisitor);
$sql->bindParam(':ipactual', $VisitorIP);
$sql->bindParam(':ipcliente', $ipcliente);
$sql->bindParam(':countrycode', $VisitorcountryCode);
$sql->bindParam(':actual_page', $actual_page);
$sql->bindParam(':userbrowser', $userbrowser);
$sql->execute();




  
if(!isset($_SESSION['idusuario'])){

	//QUERY PAISES
	$queryPaises="SELECT P.id, P.nombre, P.iso, P.continente, C.id_continente, C.nombre AS NombreContinente 
	FROM paises P 
	LEFT JOIN continentes C ON P.continente = C.id_continente
	ORDER BY P.nombre ASC";
	$sql = $dbConn->prepare($queryPaises);
	$sql->execute();
	$dataPaises = $sql->fetchAll();
	
	foreach($dataPaises AS $pais){
	
	$pais_isoP=$pais['iso'];
	
	if($VisitorcountryCode==$pais_isoP){
		$id_paisGeneral=$pais['id'];
		$paisP=$pais['nombre'];
		$NombreContinenteP=$pais['NombreContinente'];
		$id_continenteP=$pais['id_continente'];
		if($id_paisGeneral==13){$DivisaGeneral="ARS";}else{$DivisaGeneral="USD";}
	}
	}

	}else{
if(isset($_SESSION['avatarURL']) && $_SESSION['avatarURL']!=""){$AvatarURL=$_SESSION['avatarURL'];}else{$AvatarURL="images/avatars/avatar1.png";}
if(isset($_SESSION['nombre']) && $_SESSION['nombre']!=""){$nombre=$_SESSION['nombre'];}else{$nombre=NULL;}
if(isset($_SESSION['tipousuario']) && $_SESSION['tipousuario']!=""){$tipousuario=$_SESSION['tipousuario'];}else{$tipousuario=NULL;}
if(isset($_SESSION['pais']) && $_SESSION['pais']!=""){$pais=$_SESSION['pais'];}else{$pais='13';}
if(isset($_SESSION['divisa']) && $_SESSION['divisa']!=""){$DivisaGeneral=$_SESSION['divisa'];}else{$DivisaGeneral='ARS';}

//QUERY PAISES
$queryPaises="SELECT P.id, P.nombre, P.iso, P.continente, C.id_continente, C.nombre AS NombreContinente
FROM paises P 
LEFT JOIN continentes C ON P.continente = C.id_continente
WHERE P.id='$pais'";
$sql = $dbConn->prepare($queryPaises);
$sql->execute();
$dataPaises = $sql->fetchAll();

foreach($dataPaises AS $pais){
	$id_paisGeneral=$pais['id'];
	$paisP=$pais['nombre'];
	$NombreContinenteP=$pais['NombreContinente'];
	$id_continenteP=$pais['id_continente'];
  if($id_paisGeneral==13){$DivisaGeneral="ARS";}else{$DivisaGeneral="USD";}

}


}

echo '<input type="hidden" id="paisactual" value="{\'id_pais\':\''.$id_paisGeneral.'\',\'id_continente\':\''.$id_continenteP.'\',\'divisa\':\''.$DivisaGeneral.'\'}">';
// La conexion se cierra en index.php
?>
