<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$post=json_encode($_POST);

/* ENVIA DATA DE USUARIO NUEVO A SOPORTE */
$emailsoporte='pointup@gmail.com';

require 'js/phpmail/vendor/autoload.php';

$mail = new PHPMailer;

$mail->isSMTP();                                 
$mail->Host = 'mail.sophielangues.com.ar';
$mail->SMTPAuth = TRUE;                            
$mail->Username = 'contact@sophielangues.com.ar';                
$mail->Password = 'Soph2024Lo';                           
$mail->Port = '587';

$mail->From = 'contact@sophielangues.com.ar';
	
$mail->FromName = 'Sophie Langues'; 

$mail->CharSet = 'UTF-8';
$mail->Encoding = 'base64';
$mail->IsHTML(true);
$mail->Subject = ''.$asunto.'';



$asunto = "ESTUDIANTE CARGADO EN SISTEMA";

$mail->CharSet = 'UTF-8';
$mail->Encoding = 'base64';
$mail->IsHTML(true);
$mail->Subject = ''.$asunto.'';
$body='<h2>Estudiante cargado en Plataforma</h2>';
$body.='Data: '.$post.'<br><br>';
$body.='Fecha y hora: '.$FechaHoraStamp.'<br>';



$mail->Body = $body;

	$mail->ClearAllRecipients(); 
    $mail->AddAddress($emailsoporte);
	$mail->Send();


?>