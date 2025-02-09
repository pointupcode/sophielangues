<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');   

date_default_timezone_set("America/Argentina/Buenos_Aires");
setlocale(LC_TIME, "es_ES.UTF-8");
$FechaHoraStamp = date("Y-m-d H:i:s");
$FechaActual = date("Y-m-d");
$HoraActual = date("H:i:s");

include '../includes/send_report.php';


function getUserIpAddr(){
  if(!empty($_SERVER['HTTP_CLIENT_IP'])){
      //ip from share internet
      $ip = $_SERVER['HTTP_CLIENT_IP'];
  }else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
      //ip pass from proxy
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
  }else{
      $ip = $_SERVER['REMOTE_ADDR'];
  }
  return $ip;
}

function is_valid_email($str)
{
  $matches = null;
  return (1 === preg_match('/^[A-z0-9\\._-]+@[A-z0-9][A-z0-9-]*(\\.[A-z0-9_-]+)*\\.([A-z]{2,6})$/', $str, $matches));
}
$simbolos = array("[", "]", "{", "}", "(", ")", "+", "=", "-", "_", "\"", "'");


include "../includes/utils.php";
$dbConn =  connect($db);

if(isset($_POST['email']) && !empty($_POST['email'])){$email=addslashes($_POST['email']);}else{$email="NINGUNO";}
if(isset($_POST['contrasenia']) && !empty($_POST['contrasenia'])){$contrasenia=$_POST['contrasenia'];}else{$contrasenia="";}
if(isset($_POST['nombre']) && !empty($_POST['nombre'])){$nombre=addslashes($_POST['nombre']);}else{$nombre="NINGUNO";}
if(isset($_POST['divisa']) && !empty($_POST['divisa'])){$divisa=addslashes($_POST['divisa']);}else{$divisa="ARS";}
if(isset($_POST['pais']) && !empty($_POST['pais'])){$pais=$_POST['pais'];}else{$pais="0";}



$TipoUsuario="ESTUDIANTE";
$Bloqueado="DESBLOQUEADO";
$ipcliente=getUserIpAddr();
$mensaje="";

$sql=$dbConn->prepare("SELECT U.NombreUsuario, U.Bloqueado FROM usuarios U WHERE U.Email=:user");
$sql->bindValue(':user', $email);
$sql->execute();
$userEncontrado=$sql->fetch(PDO::FETCH_ASSOC);

if($userEncontrado!=""){

$EsError = 'ERROR';
$error = '<div class="alert alert-danger AvisoError"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Ya existe un registro con este e-mail. Por favor, intentá con otra dirección.</div>';
$datos_a_enviar = array($EsError, $error);
echo json_encode($datos_a_enviar);
       
}else if(is_valid_email($email)!=TRUE){

$EsError = 'ERROR';
$error = '<div class="alert alert-danger AvisoError"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> El e-mail no es válido o tiene errores.</div>';
$datos_a_enviar = array($EsError, $error);
echo json_encode($datos_a_enviar);

}else if($tc_tipo=="" && $MedioDePago=="Tarjetas"){

$EsError = 'ERROR';
$error = '<div class="alert alert-danger AvisoError"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Falta ingresar un medio de pago.</div>';
$datos_a_enviar = array($EsError, $error);
echo json_encode($datos_a_enviar);

}else if($tc_tipo=="NINGUNO" && $MedioDePago=="Tarjetas"){

$EsError = 'ERROR';
$error = '<div class="alert alert-danger AvisoError"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Falta ingresar un medio de pago.</div>';
$datos_a_enviar = array($EsError, $error);
echo json_encode($datos_a_enviar);

}else if($tc_marca=="" && $MedioDePago=="Tarjetas"){

$EsError = 'ERROR';
$error = '<div class="alert alert-danger AvisoError"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Falta ingresar correctamente la marca de la tarjeta en el medio de pago.</div>';
$datos_a_enviar = array($EsError, $error);
echo json_encode($datos_a_enviar);

}else if($tc_marca=="NINGUNA" && $MedioDePago=="Tarjetas"){

$EsError = 'ERROR';
$error = '<div class="alert alert-danger AvisoError"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Falta ingresar correctamente la marca de la tarjeta en el medio de pago.</div>';
$datos_a_enviar = array($EsError, $error);
echo json_encode($datos_a_enviar);

}else{


$passssube = password_hash($contrasenia, PASSWORD_BCRYPT);    

$sql="INSERT INTO usuarios(NombreUsuario, Contrasenia, TipoUsuario, Apellido, Nombre, Email, FechaNacimiento, DNItipo, DNInumero, TelefonoCodArea, Telefono, Domicilio, Nro, Piso, Depto, CodPostal, DatosAdic, Localidad, Provincia, Pais, PuntoRetiro_Sel, id_puntoderetiro, Divisa, FechaAlta, IPcliente, tc_tipo, tc_marca, tc_numero, tc_nombre, tc_dnitipo,tc_dninumero, tc_vencimiento_mes, tc_vencimiento_ano, comoconociste, comoconocisteotro) VALUES (:email, :contrasenia, :tipousuario, :apellido, :nombre, :email, :fechanacimiento, :DNItipo, :DNInumero, :telefonoCodArea, :telefono, :domicilio, :nro, :piso, :depto, :codpostal, :datosadic, :localidad, :provincia, :pais, :PuntoRetiro_Sel, :id_puntoderetiro, :divisa, :fechaalta, :ipcliente, :tc_tipo, :tc_marca,  AES_ENCRYPT(:tc_numero, '$encrypt_key'), :tc_nombre, :tc_dnitipo, :tc_dninumero, :tc_vencimiento_mes, :tc_vencimiento_ano,:comoconociste, :comoconocisteotro )";

$sql = $dbConn->prepare($sql);
$sql->bindParam(':email', $email);
$sql->bindParam(':contrasenia', $passssube);
$sql->bindParam(':tipousuario', $TipoUsuario);
$sql->bindParam(':apellido', $apellido);
$sql->bindParam(':nombre', $nombre);
$sql->bindParam(':fechanacimiento', $fechanacimiento);
$sql->bindParam(':DNItipo', $TipoDNISocio);   
$sql->bindParam(':DNInumero', $documentoSocio);
$sql->bindParam(':telefonoCodArea', $telefonoCodArea);
$sql->bindParam(':telefono', $telefono);
$sql->bindParam(':domicilio', $domicilio);
$sql->bindParam(':nro', $numero);
$sql->bindParam(':piso', $piso);
$sql->bindParam(':depto', $depto);
$sql->bindParam(':codpostal', $codigopostal);
$sql->bindParam(':datosadic', $datosadic);
$sql->bindParam(':localidad', $localidadFinal);
$sql->bindParam(':provincia', $provincia);
$sql->bindParam(':pais', $pais);
$sql->bindParam(':PuntoRetiro_Sel', $PuntoDeRetiroSINO);
$sql->bindParam(':id_puntoderetiro', $PuntoDeRetiroSeleccionado);
$sql->bindParam(':divisa', $divisa);
$sql->bindParam(':fechaalta', $FechaHoraStamp);
$sql->bindParam(':ipcliente', $ipcliente);

$sql->bindParam(':tc_tipo', $tc_tipo);
$sql->bindParam(':tc_marca', $tc_marca);
$sql->bindParam(':tc_numero', $cardNumber);
$sql->bindParam(':tc_nombre', $owner);
$sql->bindParam(':tc_dnitipo', $TipoDNI);
$sql->bindParam(':tc_dninumero', $documento);
$sql->bindParam(':tc_vencimiento_mes', $tc_vencimiento_mes);
$sql->bindParam(':tc_vencimiento_ano', $tc_vencimiento_ano);

$sql->bindParam(':comoconociste', $comoconociste);
$sql->bindParam(':comoconocisteotro', $comoconocisteotro);


if($sql->execute()){
$IdInsertado = $dbConn->lastInsertId();

$suscr_tipoFija="FIJA";

//Sucr base
$id_suscripcionBase=1;
$ValorSuscBase=0;
$SuscInfoBase="INFOBASE";


$sqlSusc="INSERT INTO usuarios_suscripciones(id_usuario, id_suscripcion, Divisa, suscr_Valor, suscr_tipo, fecha_suscripcion, usuario_suscribe, fecha_procesamiento, usuario_procesa)VALUES(:id_usuario, :id_suscripcion, :Divisa, :susc_valor, :suscr_tipo, :fecha_suscripcion, :usuario_suscribe, :fecha_procesamiento, :usuario_procesa)";
$sql = $dbConn->prepare($sqlSusc);
$sql->bindParam(':id_usuario', $IdInsertado);
$sql->bindParam(':id_suscripcion', $id_suscripcionBase);
$sql->bindParam(':Divisa', $divisa);
$sql->bindParam(':susc_valor', $ValorSuscBase);
$sql->bindParam(':suscr_tipo', $SuscInfoBase);
$sql->bindParam(':fecha_suscripcion', $FechaHoraStamp);
$sql->bindParam(':usuario_suscribe', $IdInsertado);
$sql->bindParam(':usuario_procesa', $IdInsertado);
$sql->bindParam(':fecha_procesamiento', $FechaHoraStamp);
$sql->execute();

//Costo de Envio
$id_suscripcionEnvio=920;
if($pais==13){
  $objCE = json_decode($localidad);
  $valorCE = $objCE->{'costo_envio'}; 
}else{
  $valorCE=$costo_envio_ubicacion;
}

$sqlSusc="INSERT INTO usuarios_suscripciones(id_usuario, id_suscripcion, Divisa, suscr_Valor, fecha_suscripcion, usuario_suscribe, fecha_procesamiento, usuario_procesa)VALUES(:id_usuario, :id_suscripcion, :Divisa, :susc_valor, :fecha_suscripcion, :usuario_suscribe, :fecha_procesamiento, :usuario_procesa)";
$sql = $dbConn->prepare($sqlSusc);
$sql->bindParam(':id_usuario', $IdInsertado);
$sql->bindParam(':id_suscripcion', $id_suscripcionEnvio);
$sql->bindParam(':Divisa', $divisa);
$sql->bindParam(':susc_valor', $valorCE);
$sql->bindParam(':fecha_suscripcion', $FechaHoraStamp);
$sql->bindParam(':usuario_suscribe', $IdInsertado);
$sql->bindParam(':usuario_procesa', $IdInsertado);
$sql->bindParam(':fecha_procesamiento', $FechaHoraStamp);
$sql->execute();

//Suscripciones
foreach($suscr_selected as $susc){
  $obj = json_decode($susc);
  $id_suscripcion = $obj->{'suscr_id'}; 
  $suscr_valorARS = $obj->{'suscr_valorARS'}; 
  $suscr_valorUSD = $obj->{'suscr_valorUSD'}; 
  if($pais==13){
  $id_suscripcionValor = $suscr_valorARS; 
  }else{
  $id_suscripcionValor = $suscr_valorUSD; 
  }
$sqlSusc="INSERT INTO usuarios_suscripciones(id_usuario, id_suscripcion, Divisa, suscr_Valor,  suscr_tipo, fecha_suscripcion, usuario_suscribe, fecha_procesamiento, usuario_procesa)VALUES(:id_usuario, :id_suscripcion, :Divisa, :susc_valor, :suscr_tipo, :fecha_suscripcion, :usuario_suscribe, :fecha_procesamiento, :usuario_procesa)";
$sql = $dbConn->prepare($sqlSusc);
$sql->bindParam(':id_usuario', $IdInsertado);
$sql->bindParam(':id_suscripcion', $id_suscripcion);
$sql->bindParam(':Divisa', $divisa);
$sql->bindParam(':susc_valor', $id_suscripcionValor);
$sql->bindParam(':suscr_tipo', $suscr_tipoFija);
$sql->bindParam(':fecha_suscripcion', $FechaHoraStamp);
$sql->bindParam(':usuario_suscribe', $IdInsertado);
$sql->bindParam(':usuario_procesa', $IdInsertado);
$sql->bindParam(':fecha_procesamiento', $FechaHoraStamp);
$sql->execute();

//SI ES PLUTÓN INFANTIL, INSERTA CODIGO DE DESCUENTO "FIGURITAS"
if($id_suscripcion==3 || $id_suscripcion==5){

  $suscr_id_descPI = 950; 
  $palabraclaveDBPI="FIGURITAS";
  $valorDBPI="Sorpresa";
  $valorInsertPI=0;
  $tipoDBPI="Sorpresa";
  $meses_aplicablesDBPI="1";
  $FechaHastaValidezPI=date("Y-m-10",strtotime($FechaActual."+ $meses_aplicablesDBPI months"));
  $descuento_descripcionPI="CodDes. $palabraclaveDBPI";

      $sqlSusc="INSERT INTO usuarios_suscripciones(id_usuario, id_suscripcion, Divisa, suscr_Valor, descuento_tipo, descuento_descripcion, descuento_vencimiento, fecha_suscripcion, usuario_suscribe, fecha_procesamiento, usuario_procesa)VALUES(:id_usuario, :id_suscripcion, :Divisa, :susc_valor, :descuento_tipo, :descuento_descripcion, :descuento_vencimiento, :fecha_suscripcion, :usuario_suscribe, :fecha_procesamiento, :usuario_procesa)";
      $sql = $dbConn->prepare($sqlSusc);
      $sql->bindParam(':id_usuario', $IdInsertado);
      $sql->bindParam(':id_suscripcion', $suscr_id_descPI);
      $sql->bindParam(':susc_valor', $valorInsertPI);
      $sql->bindParam(':Divisa', $divisa);
      $sql->bindParam(':descuento_tipo', $tipoDBPI);
      $sql->bindParam(':descuento_descripcion', $descuento_descripcionPI);
      $sql->bindParam(':descuento_vencimiento', $FechaHastaValidezPI);
      $sql->bindParam(':fecha_suscripcion', $FechaHoraStamp);
      $sql->bindParam(':usuario_suscribe', $IdInsertado);
      $sql->bindParam(':fecha_procesamiento', $FechaHoraStamp);
      $sql->bindParam(':usuario_procesa', $IdInsertado);
      if($sql->execute()){
        
        $mensaje.= '<p class="alert alert-success"><i class="glyphicon glyphicon-ok-sign" aria-hidden="true"></i> Por haber elegido PLUTÓN INFANTIL, fue  aplicado el código promocional <b>'.$palabraclaveDBPI.'</b>.</p>';

        }
    }
  

} //FIN Suscripciones

//PUNTO DE RETIRO
if(isset($PuntoDeRetiroSeleccionado) && $PuntoDeRetiroSeleccionado!="0"){

  $sqlPdR="SELECT  P.id_puntoderetiro, P.MedioDeEnvio, P.descripcion, P.direccion, P.nro, P.localidad, P.partido, P.provincia, P.codigopostal
FROM puntosderetiro P
WHERE P.id_puntoderetiro = '$PuntoDeRetiroSeleccionado' LIMIT 1";
$sql = $dbConn->prepare($sqlPdR);
$sql->execute();
$dataPuntos = $sql->fetchAll();
foreach($dataPuntos as $row){
  $pdr_id_puntoderetiro=$row["id_puntoderetiro"];
  $pdr_MedioDeEnvio=$row["MedioDeEnvio"];
  $pdr_descripcion=$row["descripcion"];
  $pdr_direccion=$row["direccion"];
  $pdr_nro=$row["nro"];
  if($row["direccion"]!=""){$pdr_direccion=$row["direccion"].', ';}else{$pdr_direccion="";}
  if($row["localidad"]!=""){$pdr_localidadCompleta=$row["localidad"].', ';}else{$pdr_localidadCompleta="";}
  $pdr_partidoCompleto=$row["partido"];

  $pdr_direccionCompleta=$pdr_direccion.' '.$pdr_nro.' '.$pdr_localidadCompleta.' '.$pdr_partidoCompleto.'';


if($PuntoDeRetiroSeleccionado==0){
  $mensaje.= '<p class="alert alert-warning"><i class="fa fa-truck" aria-hidden="true"></i> Elegiste recibir tu pedido en la dirección que indicaste.</p>';
}else{
  $mensaje.= '<p class="alert alert-warning"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Elegiste RETIRAR tu pedido en <b>'.$pdr_descripcion.'</b> - <i>'.$pdr_direccionCompleta.'</i>.</p>';
}

}

}

//DESCUENTOS DE SUSCRIPCIONES


if($suscr_selected_descuento!=""){

  $suscr_id_DESC = 950; 
  $suscr_DESC_valor = 10;
  $suscr_DESC_valor_tipo = "PorcentajeSusc";
  $suscr_DESC_tipo = "FIJA";
  $suscr_DESC_vencimiento = NULL;
  foreach($suscr_selected_descuento as $suscDESC){
    $objDESC = json_decode($suscDESC);
    $id_suscripcionDESC = $objDESC->{'suscr_id'}; 
    $desctexto = $objDESC->{'desctexto'}; 
    $valorDESC = $objDESC->{'valor'}; 

      $sqlSusc="INSERT INTO usuarios_suscripciones(id_usuario, id_suscripcion, id_libro, Divisa, suscr_Valor, suscr_tipo, descuento_tipo, descuento_descripcion, descuento_vencimiento, fecha_suscripcion, usuario_suscribe, fecha_procesamiento, usuario_procesa)VALUES(:id_usuario, :id_suscripcion, :id_libro, :Divisa, :suscr_Valor, :suscr_tipo, :descuento_tipo, :descuento_descripcion, :descuento_vencimiento, :fecha_suscripcion, :usuario_suscribe, :fecha_procesamiento, :usuario_procesa)";
      $sql = $dbConn->prepare($sqlSusc);
      $sql->bindParam(':id_usuario', $IdInsertado);
      $sql->bindParam(':id_suscripcion', $suscr_id_DESC);
      $sql->bindParam(':id_libro', $id_suscripcionDESC);
      $sql->bindParam(':Divisa', $divisa);
      $sql->bindParam(':suscr_Valor', $valorDESC);
      $sql->bindParam(':suscr_tipo', $suscr_DESC_tipo);
      $sql->bindParam(':descuento_tipo', $suscr_DESC_valor_tipo);
      $sql->bindParam(':descuento_descripcion', $desctexto);
      $sql->bindParam(':descuento_vencimiento', $suscr_DESC_vencimiento);
      $sql->bindParam(':fecha_suscripcion', $FechaHoraStamp);
      $sql->bindParam(':usuario_suscribe', $IdInsertado);
      $sql->bindParam(':fecha_procesamiento', $FechaHoraStamp);
      $sql->bindParam(':usuario_procesa', $IdInsertado);
      if($sql->execute()){
        
        $mensaje.= '<p class="alert alert-success"><i class="glyphicon glyphicon-ok-sign" aria-hidden="true"></i> Fue aplicado correctamente el <b>'.$suscr_DESC_valor.'% de  '.$desctexto.'</b>.</p>';

        }

}

}



//CODIGOS DE DESCUENTO
if($codigodescuento!="" && $id_codigodescuento!=""){

  $suscr_id_desc = 950; 
  $CheckCodeActivo="SELECT id_codigodescuento, palabra_clave, valor, tipo, vencimiento, meses_aplicables, uso_mes_cantidad, usos_cantidad, uso_externo_interno, ActivoNoActivo FROM codigos_descuentos WHERE ActivoNoActivo='ACTIVO' AND palabra_clave='$codigodescuento' AND uso_externo_interno='EXTERNO'";	
$sql = $dbConn->prepare($CheckCodeActivo);
    $sql->execute();
    $dataCode = $sql->fetchAll();
    $num_reg_Av = $sql->rowCount();
    if($num_reg_Av>0){
      foreach($dataCode as $row){
        $palabraclaveDB=$row["palabra_clave"];
        $valorDB=$row["valor"];
        if($valorDB=="Sorpresa"){$valorInsert=0;}else{$valorInsert=$valorDB;};
        $tipoDB=$row["tipo"];
        $meses_aplicablesDB=$row["meses_aplicables"];
        $uso_mes_cantidadDB=$row["uso_mes_cantidad"];
        $usos_cantidadDB=$row["usos_cantidad"];
        $FechaHastaValidez=date("Y-m-d",strtotime($FechaActual."+ $meses_aplicablesDB months"));
        $descuento_descripcion="CodDes. $palabraclaveDB";
      }

      $sqlSusc="INSERT INTO usuarios_suscripciones(id_usuario, id_suscripcion, Divisa, suscr_Valor,  descuento_tipo, descuento_descripcion, descuento_vencimiento, fecha_suscripcion, usuario_suscribe, fecha_procesamiento, usuario_procesa)VALUES(:id_usuario, :id_suscripcion, :Divisa, :susc_valor, :descuento_tipo, :descuento_descripcion, :descuento_vencimiento, :fecha_suscripcion, :usuario_suscribe, :fecha_procesamiento, :usuario_procesa)";
      $sql = $dbConn->prepare($sqlSusc);
      $sql->bindParam(':id_usuario', $IdInsertado);
      $sql->bindParam(':id_suscripcion', $suscr_id_desc);
      $sql->bindParam(':Divisa', $divisa);
      $sql->bindParam(':susc_valor', $valorInsert);
      $sql->bindParam(':descuento_tipo', $tipoDB);
      $sql->bindParam(':descuento_descripcion', $descuento_descripcion);
      $sql->bindParam(':descuento_vencimiento', $FechaHastaValidez);
      $sql->bindParam(':fecha_suscripcion', $FechaHoraStamp);
      $sql->bindParam(':usuario_suscribe', $IdInsertado);
      $sql->bindParam(':fecha_procesamiento', $FechaHoraStamp);
      $sql->bindParam(':usuario_procesa', $IdInsertado);

      if($uso_mes_cantidadDB=="CANTIDAD"){

        $Estado="ACTIVO";
        $QueryRestaCod="UPDATE codigos_descuentos SET usos_cantidad = usos_cantidad - 1 WHERE id_codigodescuento=:id_codigodescuento AND usos_cantidad>=1 AND ActivoNoActivo=:ActivoNoActivo";
        $sql = $dbConn->prepare($QueryRestaCod);
        $sql->bindParam(':ActivoNoActivo', $Estado, PDO::PARAM_STR);   
        $sql->bindParam(':id_codigodescuento', $id_codigodescuento, PDO::PARAM_INT);   
        if($sql->execute()){
          $mensaje.= '<p class="alert alert-success"><i class="glyphicon glyphicon-ok-sign" aria-hidden="true"></i> Fue aplicado correctamente el código <b>'.$codigodescuento.'</b></p>';
        }else{
          $mensaje.=  '<p class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></i> Lo sentimos. El Código de Descuento <b>'.$codigodescuento.'</b> no fue aplicado. Ya no se puede utilizar.</p>';
        }

      }else{
        
        if($sql->execute()){   
          $mensaje.= '<p class="alert alert-success"><i class="glyphicon glyphicon-ok-sign" aria-hidden="true"></i> Fue aplicado correctamente el código <b>'.$codigodescuento.'</b></p>';
          }
  
      }
  

    }else{
      $mensaje.="<p class='alert alert-danger'><i class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></i> Lo sentimos. El Código de Descuento <b>'.$codigodescuento.'</b> no se aplicó, porque verificamos que ya no es válido.</p>";
    }
  
}


// CREA EMAIL 

$emaildestino="escapeapluton@gmail.com";
//$emaildestino="pointup@gmail.com";
$asunto = 'Suscripción de '.$nombre.' '.$apellido.'';

$mail->From = 'contacto@escapeapluton.com.ar';
$mail->FromName = $nombre.' '.$apellido; 
$mail->AddReplyTo(''.$email.'', ''.$nombre.' '.$apellido.'');


$mail->CharSet = 'UTF-8';
$mail->Encoding = 'base64';
$mail->IsHTML(true);
$mail->Subject = ''.$asunto.'';
$body = '
<html>
<head>
<title>Suscripci&oacute;n al club</title>
</head>
<body>
<table>
</tbody>
<tr>
<td></td>
</tr>
<tr><td><img src="https://www.escapeapluton.com.ar/images/email/email_encabezado.png" alt="Escape a Plutón - Club de libros"></td></tr>
<tr>&nbsp;</tr>
<tr><td>'.$nombre.' '.$apellido.' se ha suscripto desde el sistema autom&aacute;tico:</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td><b>DATOS </b></td></tr>
<tr><td><b>Nro. de socix:</b> '.$IdInsertado.'</td></tr>
<tr><td><b>Email:</b> '.$email.'</td></tr>
<tr><td><b>Nombre:</b> '.$nombre.'</td></tr>
<tr><td><b>Apellido:</b> '.$apellido.'</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>Suscripciones seleccionadas:</td></tr>
<tr><td>';
foreach($suscr_selected as $susc){
  $obj = json_decode($susc);
  $suscnombreMostrar = $obj->{'suscnombreMostrar'}; 
  $body.='<li>'.$suscnombreMostrar.'</li>';
}
$body.='</td></tr>
';
if($suscr_selected_descuento!=""){
  $body.='
  <tr><td>Descuentos aplicados sobre Suscripciones:</td></tr>
  <tr><td>';
  foreach($suscr_selected_descuento as $suscDESCMail){
    $objDESCMail = json_decode($suscDESCMail);
    $desctextoMail = $objDESCMail->{'desctexto'}; 
    $valorDESCMail = $objDESCMail->{'valor'};
    $body.='<li>'.$desctextoMail.' ('.$suscr_DESC_valor.'%)</li>';
  }
  $body.='</td></tr>';
}
$body.='
<tr><td>&nbsp;</td></tr>
<tr><td>MEDIO DE PAGO:</td></tr>
<tr><td><b>'.$MedioDePago.'</b></td></tr>
';
if($MedioDePago=="Tarjetas"){
  $body.='
  <tr><td>'.$tc_tipo.' '.$tc_marca.'</td></tr>
  '; 
}
$body.='
<tr><td>&nbsp;</td></tr>
<tr><td>C&oacute;mo conoci&oacute; el Club:</td></tr>
<tr><td>'.$comoconociste.'</td></tr>
<tr><td>'.$comoconocisteotro.'</td></tr>
<tr><td>&nbsp;</td></tr>';
if($codigodescuento!=""){
  $body.='
  <tr><td>C&oacute;digo de descuento aplicado:</td></tr>
  <tr><td>'.$codigodescuento.'</td></tr>
  <tr><td>&nbsp;</td></tr>
  ';
}
  $body.='
  <tr><td>&nbsp;</td></tr>
  <tr><td><b>MODO DE ENTREGA</b></td></tr>
  ';

if(isset($dataPuntos) && $dataPuntos!=""){
  foreach($dataPuntos as $row){
    $pdr_id_puntoderetiro=$row["id_puntoderetiro"];
    $pdr_MedioDeEnvio=$row["MedioDeEnvio"];
    $pdr_descripcion=$row["descripcion"];
    $pdr_direccion=$row["direccion"];
    $pdr_nro=$row["nro"];
    if($row["direccion"]!=""){$pdr_direccion=$row["direccion"].', ';}else{$pdr_direccion="";}
    if($row["localidad"]!=""){$pdr_localidadCompleta=$row["localidad"].', ';}else{$pdr_localidadCompleta="";}
    $pdr_partidoCompleto=$row["partido"];
  
    $pdr_direccionCompleta=$pdr_direccion.' '.$pdr_nro.' '.$pdr_localidadCompleta.' '.$pdr_partidoCompleto.'';
  
if($PuntoDeRetiroSeleccionado!=0){
  $body.= '<tr><td> Eligió RETIRAR su pedido en <b>'.$pdr_descripcion.'</b> - <i>'.$pdr_direccionCompleta.'</i>.</tr></td>';
}
}
}else{
if($PuntoDeRetiroSeleccionado==0){
    $body.= '<tr><td> Eligió recibir su pedido en la dirección que indicó.</tr></td>';
}
}

$body.='
<tr><td>&nbsp;</td></tr>
<tr style="text-align: center;"><td>--- <a href="https://www.escapeapluton.com.ar" target="_blank">www.escapeapluton.com.ar</a> ---</td></tr>
</tbody>
</table>
<table>
<tr><td></td></tr>
</table>
</body>
</html>
';

$mail->Body = $body;

	$mail->ClearAllRecipients(); 
  $mail->AddAddress($emaildestino);
    //$mail->Send();
 if(!$mail->Send()) { 
  $EsError='ERROR';
	$mensaje= '<div class="alert alert-danger">Hubo un error al enviar el email de confirmación, pero tu suscripción fue cargada correctamente.</div>' . $mail->ErrorInfo;
}else{
  $EsError='OK';

  $mensaje.="
<div class='title'>
<h2 class=' mb20'>&iexcl;Suscripción completa!</h2>
<div>A partir del próximo mes recibirás los libros, seg&uacute;n tus suscripciones.</div>
<div>Además, ya podés ingresar a tu </div>
<div class='accesoalpanel' style='cursor:pointer;'><strong><i class='fa fa-sign-in'></i>&nbsp;Panel de Suscripción</strong></div>
</div>
<script>
fbq('track', 'CompleteRegistration', {content_name: '$nombre$apellido', value: '$IdInsertado'});
gtag('event', 'CompleteRegistration', {'event_category' : 'Registration', 'event_label': '$nombre$apellido', value: '$IdInsertado'});
</script>
";


$asunto = 'Te suscribiste a Escape a Plutón';

$mail->From = 'contacto@escapeapluton.com.ar';
$mail->FromName = 'Escape a Plutón'; 
$mail->AddReplyTo('escapeapluton@gmail.com', 'Escape a Plutón');


$mail->CharSet = 'UTF-8';
$mail->Encoding = 'base64';
$mail->IsHTML(true);
$mail->Subject = ''.$asunto.'';
$body2 = '
<html>
<head>
<title>Suscripci&oacute;n a Escape a Plut&oacute;n</title>
</head>
<body>
<table style="width:800px; font-size:16px !important;" align="center">
</tbody>
<tr>
<td></td>
</tr>
<tr><td><img src="https://www.escapeapluton.com.ar/images/email/email_encabezado.png" alt="Escape a Plut&oacute;n - Club de libros"></td></tr>
<tr>&nbsp;</tr>
<tr style="text-align: center;"><td><h2>&iexcl;Hola '.$nombre.'! ¿c&oacute;mo est&aacute;s?</h2></td></tr>
<tr style="text-align: center;"><td><h2>Te damos la bienvenida a Escape a Plut&oacute;n 😊</h2></td></tr>
<tr><td><b>&nbsp;</b></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>'; 
if($divisa=="ARS" || $divisa==""){
  $body2.= 'Te suscribiste a:  <br><br>'; 
}else{
  $body2.= 'Has elegido:  <br><br>'; 
}
$body2.= '</td></tr>
<tr><td>';
foreach($suscr_selected as $susc){
  $obj = json_decode($susc);
  $suscnombreMostrar = $obj->{'suscnombreMostrar'}; 
  $body2.='<li><b>'.$suscnombreMostrar.'</b></li>';
}
$body2.='</td></tr>
';
if($suscr_selected_descuento!=""){
  $body2.='
  <tr><td>&nbsp;</td></tr>
  <tr><td>Descuentos aplicados sobre tus Suscripciones:</td></tr>
  <tr><td>';
  foreach($suscr_selected_descuento as $suscDESC){
    $objDESC = json_decode($suscDESC);
    $id_suscripcionDESC = $objDESC->{'suscr_id'}; 
    $desctexto = $objDESC->{'desctexto'}; 
    $body2.='<li><b>'.$desctexto.'</b> <i>('.$suscr_DESC_valor.'%)</i></li>';
  }
  $body2.='</td></tr>';
}
if($MedioDePago=='PAYPAL'){
  $body2.='<tr><td>&nbsp;</td></tr>
  <tr><td>Por haber elegido <b>PayPal como medio de pago</b>, te enviaremos un e-mail con la <b><u>solicitud de fondos</u></b> para avanzar con la activaci&oacute;n.</td></tr>';
}else{
   $body2.='<tr><td>&nbsp;</td></tr><tr><td>Te contamos c&oacute;mo seguimos:</td></tr>';
}
$body2.='
<tr><td>&nbsp;</td></tr>
';
if(isset($dataPuntos) && $dataPuntos!=""){
foreach($dataPuntos as $row){
  $pdr_id_puntoderetiro=$row["id_puntoderetiro"];
  $pdr_MedioDeEnvio=$row["MedioDeEnvio"];
  $pdr_descripcion=$row["descripcion"];
  $pdr_direccion=$row["direccion"];
  $pdr_nro=$row["nro"];
  if($row["direccion"]!=""){$pdr_direccion=$row["direccion"].', ';}else{$pdr_direccion="";}
  if($row["localidad"]!=""){$pdr_localidadCompleta=$row["localidad"].', ';}else{$pdr_localidadCompleta="";}
  $pdr_partidoCompleto=$row["partido"];

  $pdr_direccionCompleta=$pdr_direccion.' '.$pdr_nro.' '.$pdr_localidadCompleta.' '.$pdr_partidoCompleto.'';

if($PuntoDeRetiroSeleccionado!=0){
  $body2.='<tr><td><b>Lo importante, ¿cu&aacute;ndo llega el paquete?</b> 🤗 De acuerdo a tu selecci&oacute;n, podr&aacute;s retirar tu paquete en <b>'.$pdr_descripcion.'</b> - <i>'.$pdr_direccionCompleta.'</i>. Ser&aacute; siempre <b>entre el 15 y el 25 de cada mes</b>. </td></tr>';
}
}
}else{
  if($PuntoDeRetiroSeleccionado==0){
  $body2.='<tr><td><b>Lo importante, ¿cu&aacute;ndo llega el paquete?</b> 🤗 Muy pronto vas a empezar a recibir las selecciones del club en el domicilio que indicaste en el formulario. Ser&aacute; siempre <b>entre el 15 y el 25 de cada mes</b>. En esos d&iacute;as vas a recibir un mail de Enviamelo o Andreani con el aviso del env&iacute;o y el n&uacute;mero de seguimiento para que veas el recorrido. Estate atento porque suele ir a parar a spam.</td></tr>';
}
}

$body2.='
<tr><td>&nbsp;</td></tr>
<tr><td>Mientras tanto, ya '; if($divisa=="ARS" || $divisa==""){$body2.='pod&eacute;s ';}else{$body2.='puedes ';} $body2.='acceder con tu cuenta personal al <b>Panel de suscripci&oacute;n</b>. Al ingresar en <a href="https://www.escapeapluton.com.ar" target="_blank">la web del club</a> con tu usuario y contraseña elegidos, '; if($divisa=="ARS" || $divisa==""){$body2.='vas a poder ';}else{$body2.='podr&aacute;s ';} $body2.=' realizar  <b>cualquier cambio en tu pedido del mes y datos personales</b>: acceder a los descuentos exclusivos de la <b>Feria de Libros</b>, agregar o modificar ejemplares, o cambiar datos de contacto y formas de pago.</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>'; if($divisa=="ARS" || $divisa==""){$body2.='Record&aacute; que lo que abon&aacute;s ';}else{$body2.='Recuerda que lo que abonas ';} $body2.=' mensualmente es el valor de las suscripciones a las que te sumaste y sus modificaciones.</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>Adem&aacute;s, con tu usuario ten&eacute;s <b>acceso a la Comunidad de lectores</b>, donde '; if($divisa=="ARS" || $divisa==""){$body2.='pod&eacute;s ';}else{$body2.='puedes ';} $body2.='intercambiar ideas con otros miembros del club 💌</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td><b>¿C&oacute;mo te '; if($divisa=="ARS" || $divisa==""){$body2.='enter&aacute;s ';}else{$body2.='enteras ';} $body2.='de qu&eacute; libros y regalos '; if($divisa=="ARS" || $divisa==""){$body2.='vas a recibir ';}else{$body2.='recibir&aacute;s ';} $body2.='?</b> ¡Los regalos sorpresa son sorpresa! 😉 Pero los &uacute;ltimos d&iacute;as de cada mes te enviaremos un <b>newsletter con el t&iacute;tulo elegido del mes entrante</b>. En el caso de que no lo recibas, por favor '; if($divisa=="ARS" || $divisa==""){$body2.='cheque&aacute; ';}else{$body2.='chequea ';} $body2.='en solapas de Notificaciones, Promociones y Spam. Si a&uacute;n no nos '; if($divisa=="ARS" || $divisa==""){$body2.='encontr&aacute;s ';}else{$body2.='encuentras ';} $body2.='en tu casilla, por favor '; if($divisa=="ARS" || $divisa==""){$body2.='escribinos ';}else{$body2.='escr&iacute;benos ';} $body2.=' 🚨. Tambi&eacute;n, '; if($divisa=="ARS" || $divisa==""){$body2.='record&aacute; ';}else{$body2.='recuerda ';} $body2.='que siempre '; if($divisa=="ARS" || $divisa==""){$body2.='pod&eacute;s ';}else{$body2.='puedes ';} $body2.='entrar a tu Panel de suscripci&oacute;n y conocer todas las novedades del mes. '; if($divisa=="ARS" || $divisa==""){$body2.='Ten&eacute;s ';}else{$body2.='Tienes ';} $body2.=' tiempo hasta el d&iacute;a 5 de cada mes a las 12 PM para hacer modificaciones en tu pedido. </td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>📬 <b>Sobre los env&iacute;os</b></td></tr>
<tr><td>&nbsp;</td></tr>
';

if($divisa=="ARS" || $divisa==""){
  $body2.='
  <tr><td>De acuerdo a tu ubicaci&oacute;n, <b>abon&aacute;s '.$divisa.' '.$valorCE.', en concepto de env&iacute;o.</b></td></tr>
  <tr><td>&nbsp;</td></tr>
  <tr><td><li><b>Si viv&iacute;s en CABA</b>, recib&iacute;s los paquetes a trav&eacute;s de nuestra mensajer&iacute;a. En caso de que no te encontremos, nos ponemos en contacto con vos para coordinar la entrega.</li>
<li><b>Si viv&iacute;s en GBA</b>, recib&iacute;s los paquetes con el servicio de log&iacute;stica de Env&iacute;amelo. En caso de que no te encuentren, te contactan por tel&eacute;fono para coordinar la entrega.</li>
<li><b>Si viv&iacute;s en otro punto del pa&iacute;s</b>, los env&iacute;os los recib&iacute;s a trav&eacute;s de Andreani. Si el correo pasa por tu casa y no te encuentra, deja un aviso de visita para retirar el paquete por la sucursal m&aacute;s cercana. Si ya es 25 y no recibiste la entrega, pod&eacute;s escribirnos a <a href="mailto:escapeapluton@gmail.com" target="_blank">escapeapluton@gmail.com</a> para chequear el recorrido.</li></td></tr>';
}else if($divisa=="USD"){
  $body2.='<tr><td>De acuerdo a tu ubicaci&oacute;n, abonas <b>'.$divisa.' '.$costo_envio_ubicacion.', en concepto de env&iacute;o</b>. En algunos destinos, podr&aacute;n cobrarse impuestos correspondientes al pa&iacuta;s al momento de la entrega del paquete (en moneda local o USD) calculados por la aduana sobre el valor declarado.</td></tr>
  <tr><td>&nbsp;</td></tr>
  <tr><td>Los env&iacute;os los recibes a trav&eacute;s de FEDEX. Si tienes dudas sobre precios y din&aacute;mica de seguimiento, por favor escr&iacute;benos.</td></tr>';
}

$body2.='<tr><td>&nbsp;</td></tr>
<tr><td>📌<b>IMPORTANTE:</b> Si te suscribiste al Club a partir del d&iacute;a 5 a las 12 PM del mes en curso, tu primera entrega ser&aacute; en el mes siguiente.</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>'; if($divisa=="ARS" || $divisa==""){$body2.='Pod&eacute;s ';}else{$body2.='Puedes ';} $body2.='conocer m&aacute;s sobre la din&aacute;mica del club en las <b><a href="https://www.escapeapluton.com.ar/comofunciona" target="_blank">Preguntas Frecuentes</a></b>.</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>Si '; if($divisa=="ARS" || $divisa==""){$body2.='ten&eacute;s, siempre cont&aacute; ';}else{$body2.='tienes dudas, siempre cuentas ';} $body2.='con nuestro equipo. Te leemos 😍</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>&iexcl;Un gran abrazo!</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>El equipo de <b>Escape a Plut&oacute;n</b></td></tr>
<tr><td>&nbsp;</td></tr>
<tr style="text-align: center;"><td> <a href="https://www.escapeapluton.com.ar" target="_blank">www.escapeapluton.com.ar</a> </td></tr>
</tbody>
</table>
</body>
</html>
<style>
body {
  font-family: sans-serif;
  font-size:18px !important;
}
</style>
';

$mail->Body = $body2;

	$mail->ClearAllRecipients(); 
  $mail->AddAddress($email);
  $mail->Send();
};


$EsError = 'OK';
$datos_a_enviar = array($EsError, $mensaje);
echo json_encode($datos_a_enviar);


	




}else{
      error('<div class="alert alert-danger AvisoError"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>No pudimos suscribirte. Te pedimos disculpas por este inconveniente. Verificá bien los datos ingresados y si no podés de ninguna manera, contactanos a escapeapluton@gmail.com.</div>');	
      exit();	 
   }

   
	
}



function error($mensaje){
$EsError="ERROR";
$datos_a_enviar = array($EsError, $mensaje);
echo json_encode($datos_a_enviar);
}

$db = NULL; 
$dbConn = NULL; 
$sql = NULL;

?>
