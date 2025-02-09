
<?php
if(!isset($_SESSION)){session_start();} 
if(isset($_SESSION['idestudiante'])) { 

if(isset($_SESSION['avatarURL']) && $_SESSION['avatarURL']!=""){$AvatarURL=$_SESSION['avatarURL'];}else{$AvatarURL="images/avatars/avatar1.png";}
if(isset($_SESSION['nombre']) && $_SESSION['nombre']!=""){$nombre=$_SESSION['nombre'];}else{$nombre=NULL;}
if(isset($_SESSION['apellido']) && $_SESSION['apellido']!=""){$apellido=$_SESSION['apellido'];}else{$apellido=NULL;}
if(isset($_SESSION['tipousuario']) && $_SESSION['tipousuario']!=""){$tipousuario=$_SESSION['tipousuario'];}else{$tipousuario=NULL;}
if(isset($_SESSION['pais']) && $_SESSION['pais']!=""){$pais=$_SESSION['pais'];}else{$pais='13';}
if(isset($_SESSION['divisa']) && $_SESSION['divisa']!=""){$DivisaGeneral=$_SESSION['divisa'];}else{$DivisaGeneral='ARS';}


    echo '<div class=" title_login resultfinal text-center">
    <div class="avatar_container"><img src="'.$AvatarURL.'"></div>
    
    <div class="usuariologueado">';echo stripslashes('<b>'.$nombre.' '.$apellido.'</b>'); echo '</div>
    <div class="clearfix"></div>

    <div class="clearfix"></div>
    <div class="login_opciones help-block">
    <a href="miscursos"><span class="fa fa-plus-square"></span>&nbsp;<span class="login_opcion">Mis cursos</a> &nbsp;
    <a href="misdatos"><span class="fa fa-user"></span>&nbsp;<span class="login_opcion">Mis Datos</span></a> &nbsp;
    <a><span class="fa fa-sign-out"></span>&nbsp;<span class="login_opcion logout">Cerrar sesión</span></a>
    </div>


    </div>
    ';
  
}else{
    $actual_page = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    echo '
   
    <div class="row title_login text-center">
    <div class="col-md-12">
    <div class="resultfinal">
        <form class="datoslogin mb-20" id="datoslogin" onsubmit="return false">
        <input type="hidden" id="au" name="au" class="au" value="'.$actual_page.'"> 
        <h2 class="mb-10">Accede con tus datos.</h2>
        <h4 class="mb-20">¡Tus cursos te están esperando!</h4>
        <input type="text" id="email_login" name="email_login" class="form-control email_login mb-10" placeholder="Email" maxlength="150"> 
        <input type="password" id="contrasenia" name="contrasenia" class="form-control contrasenia mb-10" placeholder="Contraseña" maxlength="20"> 
        <div class="help-block conoculta mostrarcontrasenia mb-10" title="Mostrar / Ocultar contraseña">Mostrar contraseña</div>
     
        <div class="buttons">
        <button type="submit" id="suscr_ingresar" class="btn btn-warning ingresar">ENTRAR</button>
        </div>  
        </form>
        <div class="resultlogin"></div>
        <div class="help-block"><a href="olvidemicontrasenia"><span class="olvidemicontrasenia">Olvidé mi contraseña</span></a></div>
        </div>
   <div class="clearfix mb-30"></div>
 <div class="SiTodaviaNoTenesCuenta mb-30">
 <h3 class="clearfix mb-20">¿Todavía no tienes cuenta?</h3>
 <p class="clearfix mb-10">Hazlo ahora y accede a todos los cursos disponibles para aprender idiomas con calidad.</p>
    <button class="btn btn-info registro" data-bs-target="#ModalRegistro" data-bs-toggle="modal"><h3>REGÍSTRATE AHORA</h3></button>
 </div>

    </div>
    </div>

    ';
}
?>

