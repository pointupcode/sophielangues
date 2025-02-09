
<?php
if(!isset($_SESSION)){session_start();} 
if(isset($_SESSION['idestudiante'])) { 

if(isset($_SESSION['avatarURL']) && $_SESSION['avatarURL']!=""){$AvatarURL=$_SESSION['avatarURL'];}else{$AvatarURL="images/avatars/avatar1.png";}
if(isset($_SESSION['nombre']) && $_SESSION['nombre']!=""){$nombre=$_SESSION['nombre'];}else{$nombre=NULL;}
if(isset($_SESSION['apellido']) && $_SESSION['apellido']!=""){$apellido=$_SESSION['apellido'];}else{$apellido=NULL;}
if(isset($_SESSION['tipousuario']) && $_SESSION['tipousuario']!=""){$tipousuario=$_SESSION['tipousuario'];}else{$tipousuario=NULL;}
if(isset($_SESSION['pais']) && $_SESSION['pais']!=""){$pais=$_SESSION['pais'];}else{$pais='13';}
if(isset($_SESSION['divisa']) && $_SESSION['divisa']!=""){$DivisaGeneral=$_SESSION['divisa'];}else{$DivisaGeneral='ARS';}


    echo '<div class=" title_login resultfinal">

    <div class="btn fa fa-close cerrarPanelLogin" aria-hidden="true"></div>
    <div class="clearfix"></div>

    <div class="avatar_container"><img src="'.$AvatarURL.'"></div>
    
    <div class="usuariologueado">';echo stripslashes('<b>'.$nombre.' '.$apellido.'</b>'); echo '</div>
    <div class="clearfix"></div>

    <div class="clearfix"></div>
    <div class="login_opciones help-block">
    <h2 class="mb-20">¡Ya estás aprendiendo!</h2>
    <a href="miscursos"><span class="fa fa-plus-square"></span>&nbsp;<span class="login_opcion">Mis cursos</span></a> 
    </div>


    </div>
    ';
  
}else{
    $actual_page = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    echo '
   
    <div class="row title_login text-center">
    <div class="col-md-12">
    <div class="resultfinal">
           <form class="course-form-area contact-page-form course-form text-right" id="myForm" action="mail.html" method="post">
             <h2 class="mb-10">Completa tus datos.</h2>
        <h4 class="mb-20">¡Y comienza a aprender ya mismo!</h4>
              <div class="form-group col-md-12">
                <input type="text" class="form-control mb-10" id="name" name="name" placeholder="Tu nombre">
              </div>
              <div class="form-group col-md-12">
                <input type="email" class="form-control mb-10" id="email" name="email" placeholder="Tu e-mail">
              </div>
              <div class="form-group col-md-12">
                <input type="password" class="form-control mb-10" id="contrasenia" name="password" placeholder="Tu contraseña">
                <div class="help-block conoculta mostrarcontrasenia mb-10" title="Mostrar / Ocultar contraseña">Mostrar contraseña</div>
              </div>
              <input type="hidden" class="form-control mb-10" id="divisaRegistro" name="divisaRegistro" readonly>
              <input type="hidden" class="form-control mb-10" id="paisRegistro" name="paisRegistro" readonly>

              <div class="col-lg-12 text-center">
                <button class="btn btn-info text-uppercase">¡Registrarme!</button>
              </div>
            </form>
        <div class="resultlogin mb-20"></div>
        <div class="help-block">Al registrarte, aceptas nuestras <a target="_blank" href="terminos" rel="noopener noreferrer">Condiciones de uso</a> <br>y nuestra <a target="_blank" href="privacidad" rel="noopener noreferrer">Política de privacidad</a>.</div>
   <div class="clearfix mb-30"></div>
 <div class="SiTodaviaNoTenesCuenta mb-30">
 <h3 class="clearfix mb-20">¿Ya tienes una cuenta?</h3>
    <button class="btn btn-success" data-bs-target="#ModalCentrado" data-bs-toggle="modal"><h3>INGRESA AHORA</h3></button>
 </div>

    </div>
    </div>

    ';
}
?>

<script>
$(document).ready(function(){


    var DatosActuales=$(document).find("#paisactual").val();
	var regex = new RegExp("'", "g");
	var string = DatosActuales.replace(regex, "\"");	
	var obj = jQuery.parseJSON( ''+string+'' );
	var id_pais = obj.id_pais;
	var divisa = obj.divisa;
    $(document).find("#paisRegistro").val(id_pais);
    $(document).find("#divisaRegistro").val(divisa);


});
</script>