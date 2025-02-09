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
            <h1 class="text-black">
              <?php echo ' <div class="avatar_container"><img src="'.$_SESSION["avatarURL"].'"></div>&iexcl;Hola, '. $_SESSION["nombre"].'!';
              ?>
            </h1>
            <p class="mx-auto text-black  mt-20 mb-40">
              Este es el espacio para construir tu aprendizaje.
            </p>
           
          </div>
        </div>
      </div>
    </section>
  <!-- ================ End banner Area ================= -->

  <!-- ================ Start Feature Area ================= -->
  <section class="feature-area estas-aprendiendo">
    <div class="container-fluid">
      <div class="feature-inner row">
        <div class="col-md-6">
          <div class="feature-item d-flex">
            <i class="ti-book"></i>
            <div class="ml-20 col-md-10">
              <h4>Estás aprendiendo</h4>

              <?php 
                $cursossql="SELECT 
    e.id_estudiante,
    c.id_curso,
    c.nombrecurso,
    c.descripcion,
    c.imagen_principal,
    COUNT(DISTINCT m.id_modulo) AS total_modulos,
    COUNT(DISTINCT cem.id_modulo) AS modulos_completados,
    ROUND((COUNT(DISTINCT cem.id_modulo) / COUNT(DISTINCT m.id_modulo)) * 100, 2) AS progreso,
    
    -- Último módulo completado
    COALESCE(ultimo_modulo.ModuloNumero, 0) AS ultimo_modulo_completado,
    COALESCE(ultimo_modulo.titulomodulo, 'N/A') AS titulo_ultimo_modulo,
    COALESCE(ultimo_modulo.descripcion, 'N/A') AS descripcion_ultimo_modulo,

    -- Módulo siguiente
    COALESCE(siguiente_modulo.ModuloNumero, 0) AS siguiente_modulo,
    COALESCE(siguiente_modulo.titulomodulo, 'N/A') AS titulo_siguiente_modulo,
    COALESCE(siguiente_modulo.descripcion, 'N/A') AS descripcion_siguiente_modulo

FROM cursos_estudiantes e
JOIN cursos c ON e.id_curso = c.id_curso
LEFT JOIN modulos m ON c.id_curso = m.id_curso
LEFT JOIN cursos_estudiantes_modulos cem 
    ON e.id_estudiante = cem.id_estudiante 
    AND e.id_curso = cem.id_curso

-- Último módulo completado (Subquery)
LEFT JOIN (
    SELECT m2.id_curso, m2.ModuloNumero, m2.titulomodulo, m2.descripcion, cem2.id_estudiante
    FROM cursos_estudiantes_modulos cem2
    JOIN modulos m2 ON cem2.id_modulo = m2.id_modulo
    WHERE cem2.id_estudiante = :id_estudiante
    ORDER BY m2.ModuloNumero DESC
    LIMIT 1
) AS ultimo_modulo ON ultimo_modulo.id_curso = c.id_curso

-- Módulo siguiente (Subquery)
LEFT JOIN (
    SELECT m3.id_curso, m3.ModuloNumero, m3.titulomodulo, m3.descripcion
    FROM modulos m3
    WHERE NOT EXISTS (
        SELECT 1 FROM cursos_estudiantes_modulos cem3 
        WHERE cem3.id_modulo = m3.id_modulo AND cem3.id_estudiante = :id_estudiante
    )
    ORDER BY m3.ModuloNumero ASC
    LIMIT 1
) AS siguiente_modulo ON siguiente_modulo.id_curso = c.id_curso

WHERE e.id_estudiante = :id_estudiante
GROUP BY e.id_estudiante, c.id_curso;

";
$cursos = $dbConn->prepare($cursossql);
$cursos->bindParam(':id_estudiante', $id_estudiante_activo);
              $cursos->execute();
              $cursos = $cursos->fetchAll();
              foreach ($cursos as $curso) {
                $id_curso=$curso["id_curso"];
                $id_estudiante=$curso["id_estudiante"];
                $Progreso=$curso["progreso"];
                $titulo_ultimo_modulo=$curso["titulo_ultimo_modulo"];
                $descripcion_ultimo_modulo=$curso["descripcion_ultimo_modulo"];
                $ultimo_modulo_completado=$curso["ultimo_modulo_completado"];
                $titulo_siguiente_modulo=$curso["titulo_siguiente_modulo"];
                $descripcion_siguiente_modulo=$curso["descripcion_siguiente_modulo"];
                $siguiente_modulo=$curso["siguiente_modulo"];
                $nombrecurso=$curso["nombrecurso"];
                $imagen_principal=$curso["imagen_principal"];
                ?>
                <div class="single-popular-course">
                <div class="thumb" style="background: url(<?php echo $imagen_principal;?>);"></div>
                <div class="details">
                <div class="d-flex justify-content-between mb-20">
                <h3><?php echo $nombrecurso;?></h3>
                </div>
                <div class="progress mb-20" role="progressbar" aria-label="Progreso del curso" aria-valuenow="<?php echo $Progreso;?>" aria-valuemin="0" aria-valuemax="100">
                  <div class="progress-bar bg-info progress-bar-striped progress-bar-animated" style="width: <?php echo $Progreso;?>%"><?php echo $Progreso;?>%</div>
                </div>
                <div class="modulo-actual" onclick="window.location='micurso?curso=<?php echo $id_curso;?>&modulo=<?php echo $ultimo_modulo_completado;?>'"><i class="fa fa-compass"></i>
                  <h5 class="mb-20">Estás en el módulo <?php echo $ultimo_modulo_completado;?></h5>
                  <h4 class="mb-10"><?php echo $titulo_ultimo_modulo;?></h4>
                  <div class="mb-20"><?php echo $descripcion_ultimo_modulo;?></div>
                </div>
                <div class="modulo-proximo"   onclick="window.location='micurso?curso=<?php echo $id_curso;?>&modulo=<?php echo $siguiente_modulo;?>'"><i class="fa fa-forward"></i>
                  <h5 class="mb-20">Próximo módulo <?php echo $siguiente_modulo;?></h5>
                  <h4 class="mb-10"><?php echo $titulo_siguiente_modulo;?></h4>
                  <div class="mb-20"><?php echo $descripcion_siguiente_modulo;?></div>
                </div>
              <div class="text-center"><a href="micurso?curso=<?php echo $id_curso;?>" class="btn btn-success">CONTINUAR</a></div>                
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
  </section>
  <!-- ================ End Feature Area ================= -->

  <!-- ================ Start Popular Course Area ================= -->
  <section class="popular-course-area section-gap">
    <div class="container-fluid">
      <div class="row justify-content-center section-title">
        <div class="col-lg-12">
          <h2>Cursos <br />
          que te pueden interesar.
          </h2>
          <p>
          Explora nuestras opciones y elige el curso perfecto para alcanzar tus metas en el idioma que elijas.
          </p>
        </div>
      </div>
      <div class="owl-carousel popuar-course-carusel">
      <?php
      $cursossql="SELECT C.id_curso, C.nombrecurso, C.descripcion, C.precio, C.CursoMoneda, C.imagen_principal, C.Destacado, C.ActivoNoActivo FROM cursos C WHERE C.ActivoNoActivo='ACTIVO' AND C.Destacado=1 ORDER BY C.Destacado ASC LIMIT 6";
      $cursos = $dbConn->query($cursossql);
      $cursos->execute();
      $cursos = $cursos->fetchAll();

      foreach ($cursos as $curso) {
        $id_curso=$curso["id_curso"];
        $nombrecurso=$curso["nombrecurso"];
        $descripcion=$curso["descripcion"];
        $precio=$curso["precio"];
        $CursoMoneda=$curso["CursoMoneda"];
        $imagen_principal=$curso["imagen_principal"];
        $ActivoNoActivo=$curso["ActivoNoActivo"];
        ?>
        <div class="single-popular-course">
          <div class="thumb" style="background: url(<?php echo $imagen_principal;?>);"></div>
            
          <div class="details">
            <div class="d-flex justify-content-between mb-20">
              <p class="name"><?php echo $nombrecurso;?></p>
              <p class="value"><?php echo $CursoMoneda." ".$precio;?></p>
            </div>
            <a href="#">
              <h4><?php echo $descripcion;?></h4>
            </a>
          </div>
        </div>
        <?php
      }
      ?>
        
      </div>
    </div>
  </section>
  <!-- ================ End Popular Course Area ================= -->

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