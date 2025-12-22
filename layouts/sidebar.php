<ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../controllers/dashboardController.php">
        <div class="sidebar-brand-icon">
          <!-- <img src="../img/logo/logo2.png"> -->
        </div>
        <div class="sidebar-brand-text mx-3">....</div>
      </a>
      <hr class="sidebar-divider my-0">
      <li class="nav-item active">
        <a class="nav-link" href="../controllers/dashboardController.php">
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
          <span>Mes enseignements</span>
        </a>
        <div id="collapseBootstrap" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <!-- <h6 class="collapse-header">Bootstrap UI</h6> -->
            <a class="collapse-item" href="../controllers/enseignementController.php">Gérer mes enseignements</a>
            <a class="collapse-item" href="../controllers/listEnseignementController.php">Liste des enseignements</a>
            
          </div>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseForm" aria-expanded="true"
          aria-controls="collapseForm">
          <!-- <i class="fab fa-fw fa-wpforms"></i> -->
          <span>Les élèves</span>
        </a>
        <div id="collapseForm" class="collapse" aria-labelledby="headingForm" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <!-- <h6 class="collapse-header">Forms</h6> -->
            <a class="collapse-item" href="../controllers/listEleveController.php">Liste des élèves</a>

          </div>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTable" aria-expanded="true"
          aria-controls="collapseTable">
          <!-- <i class="fas fa-fw fa-table"></i> -->
          <span>Les notes</span>
        </a>
        <div id="collapseTable" class="collapse" aria-labelledby="headingTable" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <!-- <h6 class="collapse-header">Tables</h6> -->
            <a class="collapse-item" href="../controllers/ajoutNoteController.php">Ajout des notes</a>
            <a class="collapse-item" href="../controllers/listNoteController.php">Liste des notes</a>
          </div>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#Table" aria-expanded="true"
          aria-controls="Table">
          <!-- <i class="fas fa-fw fa-table"></i> -->
          <span>Les bulletins</span>
        </a>
        <div id="Table" class="collapse" aria-labelledby="headingTable" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <!-- <h6 class="collapse-header">Tables</h6> -->
            <a class="collapse-item" href="../views/bulletins.php">Bulletins des élèves</a>
            
          </div>
        </div>
      </li>
      
    </ul>