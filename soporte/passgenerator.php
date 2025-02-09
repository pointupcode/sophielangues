<?php
$passw="matiaspacu11";
echo $passw;
$passssube = password_hash($passw, PASSWORD_BCRYPT);    
echo '<pre>';
echo $passssube;
echo '</pre>';

?>