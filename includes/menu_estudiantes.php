
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
            <li><a href="miscursos">Mis cursos</a></li>
            <li><a href="misdatos">Mis datos</a></li>
            <li><a class="user-name" ><?php echo $_SESSION["nombre"];?></a><a href="logout"><i class="fa fa-sign-out"></i></a></li>
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