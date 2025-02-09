<?php
if(!isset($_SESSION)){session_start();} 

if(isset($_SESSION['idestudiante']) && $_SESSION['idestudiante']!="" &&  $_SESSION['tipousuario']=="ESTUDIANTE"){

  $id_estudiante_activo=$_SESSION['idestudiante'];

?>
<!DOCTYPE html>
<html lang="es" class="no-js">

<head>
<?php include "../includes/header.php";?>
</head>

<body class="menu-estudiantes">
  <!-- ================ Start Header Area ================= -->
  <header class="default-header">
  <?php include "../includes/menu_estudiantes.php";?>
  </header>
  <!-- ================ End Header Area ================= -->
	<!-- ================ start banner Area ================= -->
	<section class="banner-area">
		<div class="container">
			<div class="row justify-content-center align-items-center">
				<div class="col-lg-12 banner-right">
					<h1 class="text-red">
							Mis datos
					</h1>
					<p class="mx-auto mt-20 mb-10">
          Aquí puedes actualizar tu información personal para mantener tu perfil al día.
          </p>
				</div>
			</div>
		</div>
	</section>
	<!-- ================ End banner Area ================= -->

  <?php 

  $sqlestudiante="SELECT E.id_estudiante, E.NombreUsuario, E.Nombre, E.Apellido, E.Email, E.Telefono, E.TelefonoAlternativo, E.Genero, E.FechaNacimiento, E.TipoDocumento, E.DNI, E.Domicilio, E.Localidad, E.Pais, E.Divisa, E.Provincia, E.CodPostal, E.Observaciones, E.instagram, E.tiktok, E.facebook, E.twitter, E.linkedin, E.Avatar, E.imagen_principal, E.color, E.FechaAlta, E.UltimoAcceso, E.ActivoNoActivo, A.avatar_urlimg
  FROM estudiantes E 
  INNER JOIN avatars A ON A.id_avatar = E.Avatar
  WHERE E.id_estudiante=:id_estudiante_activo";
  $resultestudiante = $dbConn->prepare($sqlestudiante);
  $resultestudiante->execute(array(':id_estudiante_activo' => $id_estudiante_activo));
  $rowestudiante = $resultestudiante->fetch(PDO::FETCH_ASSOC);
  $countestudiante = $resultestudiante->rowCount();
  if($countestudiante>0){
    $id_estudiante = isset($rowestudiante['id_estudiante']) ? $rowestudiante['id_estudiante'] : null;
    $Nombre = isset($rowestudiante['Nombre']) ? $rowestudiante['Nombre'] : null;
    $Apellido = isset($rowestudiante['Apellido']) ? $rowestudiante['Apellido'] : null;
    $Email = isset($rowestudiante['Email']) ? $rowestudiante['Email'] : null;
    $Telefono = isset($rowestudiante['Telefono']) ? $rowestudiante['Telefono'] : null;
    $TelefonoAlternativo = isset($rowestudiante['TelefonoAlternativo']) ? $rowestudiante['TelefonoAlternativo'] : null;
    $Genero = isset($rowestudiante['Genero']) ? $rowestudiante['Genero'] : null;
    $FechaNacimiento = isset($rowestudiante['FechaNacimiento']) ? $rowestudiante['FechaNacimiento'] : null;
    $TipoDocumento = isset($rowestudiante['TipoDocumento']) ? $rowestudiante['TipoDocumento'] : null;
    $DNI = isset($rowestudiante['DNI']) ? $rowestudiante['DNI'] : null;
    $Domicilio = isset($rowestudiante['Domicilio']) ? $rowestudiante['Domicilio'] : null;
    $Localidad = isset($rowestudiante['Localidad']) ? $rowestudiante['Localidad'] : null;
    $Pais = isset($rowestudiante['Pais']) ? $rowestudiante['Pais'] : null;
    $Divisa = isset($rowestudiante['Divisa']) ? $rowestudiante['Divisa'] : null;
    $Provincia = isset($rowestudiante['Provincia']) ? $rowestudiante['Provincia'] : null;
    $CodPostal = isset($rowestudiante['CodPostal']) ? $rowestudiante['CodPostal'] : null;
    $Observaciones = isset($rowestudiante['Observaciones']) ? $rowestudiante['Observaciones'] : null;
    $instagram = isset($rowestudiante['instagram']) ? $rowestudiante['instagram'] : null;
    $tiktok = isset($rowestudiante['tiktok']) ? $rowestudiante['tiktok'] : null;
    $facebook = isset($rowestudiante['facebook']) ? $rowestudiante['facebook'] : null;
    $twitter = isset($rowestudiante['twitter']) ? $rowestudiante['twitter'] : null;
    $linkedin = isset($rowestudiante['linkedin']) ? $rowestudiante['linkedin'] : null;
    $Avatar = isset($rowestudiante['Avatar']) ? $rowestudiante['Avatar'] : null;
    $imagen_principal = isset($rowestudiante['imagen_principal']) ? $rowestudiante['imagen_principal'] : null;
    $color = isset($rowestudiante['color']) ? $rowestudiante['color'] : null;
    $FechaAlta = isset($rowestudiante['FechaAlta']) ? $rowestudiante['FechaAlta'] : null;
    $UltimoAcceso = isset($rowestudiante['UltimoAcceso']) ? $rowestudiante['UltimoAcceso'] : null;
    $ActivoNoActivo = isset($rowestudiante['ActivoNoActivo']) ? $rowestudiante['ActivoNoActivo'] : null;
    $avatar_urlimg = isset($rowestudiante['avatar_urlimg']) ? $rowestudiante['avatar_urlimg'] : null;
  }
  
  
  ?>
  	<!-- Start post-content Area -->
	<section class="post-content-area single-post-area">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 posts-list">
				<form>
        <div class="form-group mb-10">

        <h3><?php echo $Email; ?></h3>
        <div class="help-block">Tu e-mail no se puede cambiar.</div>
                    </div>
                    <div class="form-group mb-10">
                        <label for="Nombre">Nombre</label>
                        <input type="text" class="form-control" id="Nombre" name="Nombre" value="<?php echo $Nombre; ?>" required>
                    </div>
                    <div class="form-group mb-10">
                        <label for="Apellido">Apellido</label>
                        <input type="text" class="form-control" id="Apellido" name="Apellido" value="<?php echo $Apellido; ?>" required>
                    </div>

                    <div class="form-group mb-10">
                        <label for="Telefono">Teléfono</label>
                        <input type="text" class="form-control" id="Telefono" name="Telefono" value="<?php echo $Telefono; ?>" required>
                    </div>
                    <div class="form-group mb-10">
                        <label for="Domicilio">Domicilio</label>
                        <input type="text" class="form-control" id="Domicilio" name="Domicilio" value="<?php echo $Domicilio; ?>" required>
                    </div>
                    <div class="form-group mb-10">
                        <label for="Localidad">Localidad</label>
                        <input type="text" class="form-control" id="Localidad" name="Localidad" value="<?php echo $Localidad; ?>" required>
                    </div>
                    <div class="form-group mb-10">
                        <label for="Provincia">Provincia</label>
                        <input type="text" class="form-control" id="Provincia" name="Provincia" value="<?php echo $Provincia; ?>" required>
                    </div>
                    <div class="form-group mb-10">
                        <label for="Pais">País</label>
                        <input type="text" class="form-control" id="Pais" name="Pais" value="<?php echo $Pais; ?>" required>
                    </div>
                    <div class="form-group mb-10">
                        <label for="CodPostal">Código Postal</label>
                        <input type="text" class="form-control" id="CodPostal" name="CodPostal" value="<?php echo $CodPostal; ?>" required>
                    </div>
                    <button type="submit" class="btn btn-success">Actualizar Datos</button>
						</form>
					
				
				</div>
				<div class="col-lg-4 sidebar-widgets">
					<div class="widget-wrap">
						<div class="single-sidebar-widget user-info-widget">
							<img src="<?php echo $avatar_urlimg; ?>" alt="">
							<a href="#"><h4><?php echo $Nombre.' '.$Apellido; ?></h4></a>
							<p>Estudiante</p>
							<ul class="social-links">
								<li><a href="#"><i class="fa fa-instagram"></i></a></li>
								<li><a href="#"><i class="fa fa-facebook"></i></a></li>
								<li><a href="#"><i class="fa fa-twitter"></i></a></li>
							</ul>
						</div>

            <?php
$sqlCursos = "SELECT C.id_curso, C.nombrecurso, C.descripcion, C.imagen_principal, I.id_estudiante, I.ActivoNoActivo
              FROM cursos C 
              INNER JOIN cursos_estudiantes I ON C.id_curso = I.id_curso 
              WHERE I.id_estudiante = :id_estudiante_activo AND I.ActivoNoActivo = 'ACTIVO'";

$stmt = $dbConn->prepare($sqlCursos);
$stmt->execute(array(':id_estudiante_activo' => $id_estudiante_activo));
$cursos = $stmt->fetchAll();
?>
            <div class=" ">
              <h4 class="">Cursos realizados</h4>
              <div class="">
              <?php
              foreach ($cursos as $curso) {
                $id_curso = $curso["id_curso"];
                $nombrecurso = $curso["nombrecurso"];
                $descripcion = $curso["descripcion"];
                $imagen_principal = $curso["imagen_principal"];
                ?>
                <div class=" d-flex flex-row align-items-center">
                  <div class="thumb" style="width:40%;margin-right:10px;">
                    <img class="img-fluid" src="<?php echo $imagen_principal; ?>" alt="">
                  </div>
                  <div class="details">
                    <a href="micurso?curso=<?php echo $id_curso; ?>"><h6><?php echo $nombrecurso; ?></h6></a>
                  </div>
                </div>
              <?php
              }
              ?>
              </div>
            </div>
              
              </div>
            </div>
          
            
            
            
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- End post-content Area -->
  <?php
  include "../includes/footer.php";
  $db = NULL; 
  $dbConn = NULL; 
  $sql = NULL;
  ?>


              
</body>

</html>
<?php
}else{
	$actual_page = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";	header("Location:inicio?al=$actual_page");
	}	
	?>