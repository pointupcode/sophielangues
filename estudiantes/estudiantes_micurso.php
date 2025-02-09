<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

if(!isset($_SESSION)){session_start();} 

if(isset($_SESSION['idestudiante']) && $_SESSION['idestudiante']!="" &&  $_SESSION['tipousuario']=="ESTUDIANTE"){

  $id_estudiante_activo=$_SESSION['idestudiante'];
  $cursoViene=$_GET['curso'];
  $moduloViene = isset($_GET['modulo']) ? $_GET['modulo'] : null;
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

<?php
$cursosymodulossql = "SELECT C.id_curso, C.nombrecurso, C.descripcion AS curso_descripcion, C.profesor, C.imagen_principal, C.color, C.ActivoNoActivo,
M.id_modulo, M.ModuloNumero, M.titulomodulo, M.descripcion AS modulo_descripcion, M.ActivoNoActivo
FROM cursos C 
INNER JOIN modulos M ON C.id_curso = M.id_curso 
WHERE M.ActivoNoActivo='ACTIVO' AND C.id_curso = :cursoViene";

if ($moduloViene !== null) {
    $cursosymodulossql .= " AND M.ModuloNumero = :moduloViene";
}

$cursosymodulossql .= " LIMIT 1";

$stmt = $dbConn->prepare($cursosymodulossql);
$stmt->bindParam(':cursoViene', $cursoViene, PDO::PARAM_INT);

if ($moduloViene !== null) {
    $stmt->bindParam(':moduloViene', $moduloViene, PDO::PARAM_INT);
}

$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$countcurso = $stmt->rowCount();
if($countcurso>0){

    $id_curso = isset($result['id_curso']) ? $result['id_curso'] : null;
    $nombrecurso = isset($result['nombrecurso']) ? $result['nombrecurso'] : null;
    $curso_descripcion = isset($result['curso_descripcion']) ? $result['curso_descripcion'] : null;
    $profesor = isset($result['profesor']) ? $result['profesor'] : null;
    $imagen_principal = isset($result['imagen_principal']) ? $result['imagen_principal'] : null;
    $color = isset($result['color']) ? $result['color'] : null;
    $id_modulo = isset($result['id_modulo']) ? $result['id_modulo'] : null;
    $ModuloNumero = isset($result['ModuloNumero']) ? $result['ModuloNumero'] : null;
    $titulomodulo = isset($result['titulomodulo']) ? $result['titulomodulo'] : null;
    $modulo_descripcion = isset($result['modulo_descripcion']) ? $result['modulo_descripcion'] : null;
}


$cursosymodulossql = "SELECT C.id_curso, C.nombrecurso, C.descripcion AS curso_descripcion, C.profesor, C.imagen_principal, C.color, C.ActivoNoActivo AS curso_activo,
M.id_modulo, M.ModuloNumero, M.titulomodulo, M.descripcion AS modulo_descripcion, M.ActivoNoActivo AS modulo_activo,
V.id_video, V.url_video, V.ActivoNoActivo AS video_activo,
MA.id_material, MA.links_tipo, MA.url_link, MA.descripcion AS material_descripcion, MA.ActivoNoActivo AS material_activo
FROM cursos C 
INNER JOIN modulos M ON C.id_curso = M.id_curso 
LEFT JOIN modulos_videos V ON M.id_modulo = V.id_modulo AND V.id_curso = C.id_curso
LEFT JOIN modulos_materiales MA ON M.id_modulo = MA.id_modulo AND MA.id_curso = C.id_curso
WHERE C.id_curso = :cursoViene  AND M.ActivoNoActivo = 'ACTIVO' AND (V.ActivoNoActivo = 'ACTIVO' OR V.ActivoNoActivo IS NULL) AND (MA.ActivoNoActivo = 'ACTIVO' OR MA.ActivoNoActivo IS NULL)
ORDER BY M.ModuloNumero";

$stmt = $dbConn->prepare($cursosymodulossql);
$stmt->execute(['cursoViene' => $cursoViene]);
$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Variables para organizar los datos
$modulo_actual = null;
$modulos = [];
$materiales = [];

foreach ($resultados as $fila) {
    // Definir el primer módulo como el actual
    if ($modulo_actual === null) {
        $modulo_actual = [
            'nombre' => $fila['titulomodulo'],
            'descripcion' => $fila['modulo_descripcion'],
            'video' => ($fila['video_activo'] != '') ? $fila['url_video'] : null
        ];
    }

    // Guardar todos los módulos (evita duplicados)
    if (!isset($modulos[$fila['id_modulo']])) {
        $modulos[$fila['id_modulo']] = [
            'id_modulo' => $fila['id_modulo'],
            'nombre' => $fila['titulomodulo'],
            'descripcion' => $fila['modulo_descripcion']
        ];
    }

    // Guardar los materiales si están activos
    if (!empty($fila['id_material']) && $fila['material_activo'] == 'ACTIVO') {
        $materiales[] = [
            'nombre' => $fila['material_descripcion'],
            'tipo' => $fila['links_tipo'],
            'url' => $fila['url_link']
        ];
    }
}
?>

	<!-- ================ start banner Area ================= -->
	<section class="banner-area">
		<div class="container">
			<div class="row justify-content-center align-items-center">
				<div class="col-lg-12 banner-right">
					<h1 class="text-red">
          <?php echo $nombrecurso; ?>
					</h1>
					<p class="mx-auto mt-20 mb-10">
          <?php echo $curso_descripcion; ?>
          </p>
				</div>
			</div>
		</div>
	</section>
	<!-- ================ End banner Area ================= -->

	<section class="post-content-area single-post-area">
		<div class="container">
			<div class="row">

    <div class="col-md-12 video-modulo">
      <h3><i class="fa fa-play-circle "></i> &nbsp;Módulo <?php echo $ModuloNumero; ?></h3>
      <h4><?php echo $titulomodulo; ?></h4>
      <p><?php echo $modulo_descripcion; ?></p>
            <div class="video-container">
                <video 
                id="VideoModulo"
                class="video-js"
                controls
                preload="auto"
                width="720"
                height="auto"
                data-setup="{}"
              >
                <source src="<?php echo $modulo_actual['video']; ?>" type="application/x-mpegURL" />
                <p class="vjs-no-js">
                  To view this video please enable JavaScript, and consider upgrading to a
                  web browser that
                  <a href="https://videojs.com/html5-video-support/" target="_blank"
                    >supports HTML5 video</a
                  >
                </p>
              </video>
            </div>
 </div>


<div class="col-md-6">           <!-- Todos los módulos -->
         <div class="todos-modulos">
        <h4>Todos los Módulos</h4>
        <ul>
            <?php foreach ($modulos as $modulo) : ?>
              <a href="micurso?curso=<?php echo $cursoViene?>&modulo=<?php echo $modulo['id_modulo']?>"><li><i class="fa fa-play-circle "></i> <?php echo $modulo['nombre']; ?> - <?php echo $modulo['descripcion']; ?></li></a>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<div class="col-md-6">             <!-- Todos los materiales -->
              <div class="todos-materiales">
        <h4>Materiales del Curso</h4>
        <ul>
            <?php foreach ($materiales as $material) : ?>
                <li>
                    <strong><i class="fa fa-paperclip "></i> <?php echo $material['nombre']; ?></strong> (<?php echo $material['tipo']; ?>) - 
                    <a href="<?php echo $material['url']; ?>" target="_blank">Ver Material</a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div></div>

<div class="col-md-12">
<section class="help-section">
          <h4>¿Necesitas ayuda?</h4>
          <p>Dejanos tu consulta y será respondida en el encuentro en vivo.</p>
            <form id="help-form">
              <div class="form-group mb-10">
              <textarea id="message" rows="4"  class="form-control" placeholder="Escribe tu consulta y te responderemos en el encuentro en vivo."  required></textarea>
              </div>
              <div class="form-group mb-10">
              <button type="submit"  class="btn btn-info">Enviar Consulta</button>
              </div>
            </form>
            <div id="response-message" style="display: none;">
               <p>¡Gracias! Su mensaje ha sido enviado.</p>
            </div>
       </section>
</div>
 
</div>
</div>
</section>



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