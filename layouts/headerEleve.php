<nav class="navbar navbar-expand navbar-light bg-navbar topbar mb-4 static-top">
          <button id="sidebarToggleTop" class="btn btn-link rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>
          <ul class="navbar-nav ml-auto">
            
            <div class="topbar-divider d-none d-sm-block"></div>
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <span >
                    <?php 
                      if(isset($_SESSION['id_eleve'] ) && $_SESSION['email']){
                        echo $_SESSION['nom']. " " .$_SESSION['prenom'];
                      }
                    ?>
                </span>
                &nbsp; &nbsp;
                <?php 

                    if($_SESSION['sexe'] == "Feminin") {
                      echo "<img class='img-profile rounded-circle' src='../img/girl.png' style='max-width: 60px'>";
                    } else {
                      echo "<img class='img-profile rounded-circle' src='../img/man.png' style='max-width: 60px'>";
                    }

                 ;?>
                
              </a>
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="javascript:void(0);" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Se déconnecter
                </a>
              </div>
            </li>
          </ul>
        </nav>