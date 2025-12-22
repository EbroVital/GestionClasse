<ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../controllers/dashboardEleveController.php">
        <div class="sidebar-brand-icon">
          <!-- <img src="../img/logo/logo2.png"> -->
        </div>
        <div class="sidebar-brand-text mx-3">....</div>
      </a>
      <hr class="sidebar-divider my-0">
      <li class="nav-item active">
        <a class="nav-link" href="../controllers/dashboardEleveController.php">
          <!-- <i class="fas fa-fw fa-tachometer-alt"></i> -->
          <span>Tableau de bord</span></a>
      </li>
      <hr class="sidebar-divider">
      <!-- <div class="sidebar-heading">
        Les Classes
      </div> -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBootstrap"
          aria-expanded="true" aria-controls="collapseBootstrap">
          <!-- <i class="far fa-fw fa-window-maximize"></i> -->
          <span>Ma classe</span>
        </a>
        <div id="collapseBootstrap" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <!-- <h6 class="collapse-header">Bootstrap UI</h6> -->
            <a class="collapse-item" href="../controllers/listeClasseController.php">Liste de la classe</a>
            
          </div>
        </div>
      </li>
      
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseForm" aria-expanded="true"
          aria-controls="collapseForm">
          <span>Mes moyennes</span>
        </a>
        <div id="collapseForm" class="collapse" aria-labelledby="headingForm" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="../controllers/moyenneController.php">Voir mes moyennes</a>
          </div>
        </div>
      </li>

       <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse" aria-expanded="true"
          aria-controls="collapse">
          <span>Mes notes</span>
        </a>
        <div id="collapse" class="collapse" aria-labelledby="headingForm" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="../controllers/noteEleveController.php">Voir mes notes</a>
          </div>
        </div>
      </li>

      <!-- <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTable" aria-expanded="true"
          aria-controls="collapseTable">
        
          <span>Mon bulletin</span>
        </a>
        <div id="collapseTable" class="collapse" aria-labelledby="headingTable" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            
            <a class="collapse-item" href="../controllers/bulletinController.php">Voir mon bulletin</a>

          </div>
        </div>
      </li> -->
      
    </ul>