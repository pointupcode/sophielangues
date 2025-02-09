<!DOCTYPE html>
<html lang="es" class="no-js">

<head>
<?php include "includes/header.php";?>
</head>

<body>
  <!-- ================ Start Header Area ================= -->
  <header class="default-header">
  <?php include "includes/menu.php";?>
  </header>
  <!-- ================ End Header Area ================= -->

  <!-- ================ start banner Area ================= -->
  <section class="home-banner-area">
    <div class="container">
      <div class="row justify-content-center fullscreen align-items-center">
        <div class="col-lg-5 col-md-8 home-banner-left">
          <h1 class="text-white">
            Conectando culturas <br />
            a través del idioma.
          </h1>
          <p class="mx-auto text-white  mt-20 mb-40">
          Domina idiomas de manera práctica y dinámica. Desde los conceptos básicos hasta niveles avanzados, te guiamos para alcanzar fluidez y explorar nuevas culturas, todo a tu propio ritmo.</p>
        </div>
        <div class="offset-lg-2 col-lg-5 col-md-12 home-banner-right">
          <img class="img-fluid" src="img/header-img.png" alt="" />
        </div>
      </div>
    </div>
  </section>
  <!-- ================ End banner Area ================= -->

  <!-- ================ Start Feature Area ================= -->
  <section class="feature-area">
    <div class="container-fluid">
      <div class="feature-inner row">
        <div class="col-lg-2 col-md-6">
          <div class="feature-item d-flex">
            <i class="ti-book"></i>
            <div class="ml-20">
              <h4>Lecciones Interactivas</h4>
              <p>Aprende idiomas con videos y ejercicios prácticos.</p>
            </div>
          </div>
        </div>
        <div class="col-lg-2 col-md-6">
          <div class="feature-item d-flex">
            <i class="ti-cup"></i>
            <div class="ml-20">
              <h4>Progreso Continuo</h4>
              <p>
              Sigue tu avance y recibe certificados por completar cursos.
              </p>
            </div>
          </div>
        </div>
        <div class="col-lg-2 col-md-6">
          <div class="feature-item d-flex border-right-0">
            <i class="ti-desktop"></i>
            <div class="ml-20">
              <h4>Flexibilidad Total</h4>
              <p>
              Aprende a tu ritmo, desde cualquier lugar y en cualquier momento, sin horarios fijos.
              </p>
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
          <h2>
            Cursos <br />
            ¡Empieza ya mismo!
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



  <!-- ================ Start Registration Area ================= -->
  <section class="registration-area">
    <div class="container">
      <div class="row align-items-end">
        <div class="col-lg-5">
          <div class="section-title text-left text-black">
            <h2 class="text-black">
            ¡Inscríbete <br>
            ahora!
            </h2>
            <p>
            Empieza hoy tu camino hacia el dominio de un nuevo idioma. ¡Inscríbete y transforma tu futuro!

            </p>
          </div>
        </div>
        <div class="offset-lg-3 col-lg-4 col-md-6">
          <div class="course-form-section">
            <h3 class="text-black">Iniciá inmediatamente</h3>
            <p class="">¡Es hora de aprender!</p>
            <form class="course-form-area contact-page-form course-form text-right" id="myForm" action="mail.html" method="post">
              <div class="form-group col-md-12">
                <input type="text" class="form-control" id="name" name="name" placeholder="Tu nombre" onfocus="this.placeholder = ''"
                 onblur="this.placeholder = 'Tu nombre'">
              </div>
              <div class="form-group col-md-12">
                <input type="email" class="form-control" id="email" name="email" placeholder="Tu e-mail" onfocus="this.placeholder = ''"
                 onblur=" this.placeholder = 'Tu e-mail'">
              </div>
              <div class="form-group col-md-12  mb-20">
                <input type="password" class="form-control mb-20" id="contrasenia" name="password" placeholder="Tu contraseña" onfocus="this.placeholder = ''"
                 onblur=" this.placeholder = 'Tu contraseña'">
                 <div class="help-block conoculta mostrarcontrasenia mb-10" title="Mostrar / Ocultar contraseña">Mostrar contraseña</div>

              </div>
              <input type="hidden" class="form-control mb-10" id="divisa" name="divisa" value="<?php echo $DivisaGeneral;?>" readonly>
              <input type="hidden" class="form-control mb-10" id="pais" name="pais" value="<?php echo $id_paisGeneral;?>" readonly>
           
              <div class="help-block">Al registrarte, aceptas nuestras <a target="_blank" href="terminos" rel="noopener noreferrer">Condiciones de uso</a> y nuestra <a target="_blank" href="privacidad" rel="noopener noreferrer">Política de privacidad</a>.</div>
              
              <div class="col-lg-12 text-center">
                <button class="btn text-uppercase">¡Empezar!</button>
              </div>
              
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- ================ End Registration Area ================= -->

  
  <!-- ================ Start Feature Area ================= -->
  <section class="other-feature-area">
    <div class="container">
      <div class="feature-inner row">
        <div class="col-lg-12">
          <div class="section-title text-left">
            <h2>
              Todo lo que necesitas <br />
              para aprender
            </h2>
            <p>
            Descubre las herramientas que te llevarán a dominar el idioma de manera efectiva y práctica.
            </p>
          </div>
        </div>

        <div class="col-lg-4 col-md-6">
          <div class="other-feature-item">
            <i class="ti-key"></i>
            <h4>Seguimiento del Progreso</h4>
            <div>
              <p>
              Monitorea tu avance y celebra cada logro con herramientas diseñadas para medir tu aprendizaje.

              </p>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 mt--160">
          <div class="other-feature-item">
            <i class="ti-files"></i>
            <h4>Clases en Vivo
            </h4>
            <div>
              <p>
              Interactúa en tiempo real con docentes expertos para resolver dudas y mejorar tu fluidez.
              </p>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 mt--260">
          <div class="other-feature-item">
            <i class="ti-medall-alt"></i>
            <h4>Pruebas y Ejercicios Interactivos</h4>
            <div>
              <p>
              Refuerza lo aprendido con actividades dinámicas que evalúan y potencian tus habilidades.
              </p>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="other-feature-item">
            <i class="ti-briefcase"></i>
            <h4>Lecciones Personalizadas</h4>
            <div>
              <p>
              Aprende a tu ritmo con contenido adaptado a tus objetivos y nivel actual.
              </p>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 mt--160">
          <div class="other-feature-item">
            <i class="ti-crown"></i>
            <h4>Material Complementario</h4>
            <div>
              <p>
              Accede a recursos descargables para repasar y practicar donde y cuando quieras.
              </p>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 mt--260">
          <div class="other-feature-item">
            <i class="ti-headphone-alt"></i>
            <h4>Práctica de Conversaciones</h4>
            <div>
              <p>
              Desarrolla confianza y fluidez conversando en situaciones reales con expertos.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- ================ End Feature Area ================= -->


  <!-- ================ Start Video Area ================= -->
  <section class="video-area section-gap-bottom">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-5">
          <div class="section-title text-white">
            <h2 class="text-white">
              Conocenos en acción
            </h2>
            <p>
            Descubre cómo enseñamos idiomas de forma práctica y efectiva en cada lección.
            </p>
          </div>
        </div>
        <div class="offset-lg-1 col-md-6 video-left">
          <div class="owl-carousel video-carousel">
            <div class="single-video">
              <div class="video-part">
       
                <video 
    id="VideoEjemplo1"
    class="video-js"
    controls
    preload="auto"
    width="533"
    height="300"
    data-setup="{}"
  >
    <source src="material/VideoEjemplo_1.mp4" type="video/mp4" />
    <p class="vjs-no-js">
      To view this video please enable JavaScript, and consider upgrading to a
      web browser that
      <a href="https://videojs.com/html5-video-support/" target="_blank"
        >supports HTML5 video</a
      >
    </p>
  </video>
              </div>
                <h4 class="text-white mb-20 mt-30">Sophie</h4>
              <p class="text-white mb-20">
              Experta en francés, apasionada por enseñar y ayudar a alcanzar fluidez rápidamente.
              </p>
            </div>

            <div class="single-video">
              <div class="video-part">
 

                <video  
    id="VideoEjemplo2"
    class="video-js"
    controls
    preload="auto"
    width="533"
    height="300"
    data-setup="{}"
  >
    <source src="material/VideoEjemplo2_1.mp4" type="video/mp4" />
    <p class="vjs-no-js">
      To view this video please enable JavaScript, and consider upgrading to a
      web browser that
      <a href="https://videojs.com/html5-video-support/" target="_blank"
        >supports HTML5 video</a
      >
    </p>
  </video>
              </div>
              <h4 class="text-white mb-20 mt-30">Nicky</h4>
              <p class="text-white mb-20">
              Joven y fresca, enseña francés con energía y métodos dinámicos para todos.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- ================ End Video Area ================= -->

  
  <!-- ================ Start Testimonials Area ================= -->
  <section class="testimonials-area section-gap">
    <div class="container">
      <div class="testi-slider owl-carousel" data-slider-id="1">
        <div class="row align-items-center">
          <div class="col-lg-5">
            <div class="item">
              <div class="testi-item">
                <img src="img/quote.png" alt="" />
                <div class="mt-40 text">
                  <p>
                  Las clases en vivo son increíbles. Mejoré mi pronunciación y ya puedo hablar con confianza en el trabajo.
                  </p>
                </div>
                <h4>Lucas</h4>
                <p>35 años</p>
              </div>
            </div>
          </div>
        </div>

        <div class="row align-items-center">
          <div class="col-lg-5">
            <div class="item">
              <div class="testi-item">
                <img src="img/quote.png" alt="" />
                <div class="mt-40 text">
                  <p>
                  Gracias a las lecciones personalizadas, por fin entiendo las bases del francés. ¡Es un antes y después!.
                  </p>
                </div>
                <h4>Mariana</h4>
                <p>28 años</p>
              </div>
            </div>
          </div>
        </div>

        <div class="row align-items-center">
          <div class="col-lg-5">
            <div class="item">
              <div class="testi-item">
                <img src="img/quote.png" alt="" />
                <div class="mt-40 text">
                  <p>
                  Me encanta la flexibilidad de los cursos. Puedo aprender a mi ritmo y repasar con los PDFs en cualquier momento.
                  </p>
                </div>
                <h4>Sofía</h4>
                <p>22 años</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- ================ End Testimonials Area ================= -->

  <div class="SiTodaviaNoTenesCuenta mb-30 text-center">
 <h3 class="clearfix mb-20">¿Todavía no tienes cuenta?</h3>
 <p class="clearfix mb-10">Hazlo ahora y accede a todos los cursos disponibles para aprender idiomas con calidad.</p>
    <button class="btn btn-info registro" data-bs-target="#ModalRegistro" data-bs-toggle="modal"><h3>REGÍSTRATE AHORA</h3></button>
 </div>
 <?php
 $db = NULL; 
 $dbConn = NULL; 
 $sql = NULL;

 include "includes/footer.php";
 ?>

</body>

</html>