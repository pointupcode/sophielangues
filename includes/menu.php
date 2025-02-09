
 <nav class="navbar navbar-expand-lg  navbar-light">
      <div class="container">
        <a class="navbar-brand" href="inicio">
          <img src="img/logo.png" class="pr-30" alt="Aprender en cualquier lugar del mundo." />
          <div class="logo">SOPHIE<br>LANGUES</div>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="lnr lnr-menu"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end align-items-center" id="navbarSupportedContent">
          <ul class="navbar-nav">
            <li><a href="inicio">Inicio</a></li>
            <li class="dropdown">
              <a class="dropdown-toggle" href="#" data-toggle="dropdown">
                Cursos
              </a>
              <div class="dropdown-menu">
                <?php
                $sqlcursos="SELECT C.id_curso, C.nombrecurso, C.descripcion, C.precio, C.CursoMoneda, C.imagen_principal, C.Destacado, C.ActivoNoActivo FROM cursos C WHERE C.ActivoNoActivo='ACTIVO' AND C.Destacado=1 ORDER BY C.Destacado ";
                $cursos = $dbConn->query($sqlcursos);
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
                  <a class="dropdown-item" href="cursos?curso=<?php echo $id_curso;?>"><?php echo $nombrecurso;?></a>
                  <?php
                }
                
                ?>
       
              </div>
            </li>
            <li><a href="contacts">Contacto</a></li>
            <li class="acceder-btn"  data-bs-toggle="modal" data-bs-target="#ModalCentrado"><a><i class="fa fa-user"></i>&nbsp; Iniciar sesión</a></li>&nbsp;&nbsp;
            <li data-bs-toggle="modal" data-bs-target="#ModalRegistro" data-bs-toggle="modal">
            <button class="btn btn-info registro" data-bs-target="#ModalRegistro" data-bs-toggle="modal">REGÍSTRATE</button></li>
            <li>
              <button class="search">
                <span class="lnr lnr-magnifier" id="search"></span>
              </button>
              
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="search-input" id="search-input-box">
      <div class="container">
        <form class="d-flex justify-content-between">
          <input type="text" class="form-control" id="search-input" placeholder="Search Here" />
          <button type="submit" class="btn"></button>
          <span class="lnr lnr-cross" id="close-search" title="Close Search"></span>
        </form>
      </div>
    </div>