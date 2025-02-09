<?php


// Configuración del servidor SMTP

$mail = new PHPMailer;

//Enable SMTP debugging. 
$mail->SMTPDebug = 3;                               
//Set PHPMailer to use SMTP.
$mail->isSMTP();            
//Set SMTP host name                          
$mail->Host = 'mail.sophielangues.com.ar';
//Set this to true if SMTP host requires authentication to send email
$mail->SMTPAuth = TRUE;                      
//Provide username and password     
$mail->Username = 'contact@sophielangues.com.ar';                
$mail->Password = 'Soph2024Lo';                           
                          
//Set TCP port to connect to 
$mail->Port = '587';

// Configuración cabeceras del mensaje

$mail->From = 'contact@sophielangues.com.ar';
	
$mail->FromName = 'Sophie Langues'; 

?>