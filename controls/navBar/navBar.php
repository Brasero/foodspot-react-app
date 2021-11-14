<nav class="navbar navbar-light bg-transparent fixed-top mb-3">
  <div class="container-fluid">
    <a class="navbar-brand ms-1 d-flex" href="#"> 
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
        <span class="navbar-toggler-icon"></span>
        </button>
    </a>
    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <ul class="navbar-nav justify-content-end flex-grow-1 pe-1">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="index.php">DashBoard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index.php?page=1">Catégories</a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="index.php?page=2">Produits</a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href='index.php?page=3'>Ingrédients</a>
          </li>
          <li class="nav-item">
            <a class="text-danger nav-link" href="logOut.php">Déconnexion</a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</nav>