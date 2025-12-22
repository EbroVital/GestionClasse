<?php

  session_start();
  require "connexion.php";

  if(!isset($_SESSION['nom']) && ($_SESSION['prenom'])) {
    header("Location:login.php");
  }


  if (isset($_POST['ajout'])) {

    if (!is_numeric($_POST['coefficient'])){
      $message = "Le coefficient doit etre un nombre" ;
    }

    if(empty($_POST['libelle']) && empty($_POST['coefficient'])){
      $message = "Les champs sont obligatoires";
    } 
    else {

       $idProf = intval($_POST['idProf']);
       $libelle = htmlspecialchars($_POST['libelle']);
       $coefficient = intval($_POST['coefficient']);

       $stmt = $connexion->prepare("INSERT INTO dbclasse.matiere (libelle,coefficient, professeur) VALUES (:libelle, :coefficient, :professeur)");

       $stmt->bindValue(":libelle", $libelle);
       $stmt->bindValue(":coefficient", $coefficient);
       $stmt->bindValue(":professeur", $idProf);

       $stmt->execute();

        if($stmt){
          $message = "Enregistrement effectué !";
        } else {
          $message = "Une erreur est survenue, réessayer ! ";
        }
      
      



    }
    


  }


  


?>




<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <!-- <link href="img/logo/logo.png" rel="icon"> -->
  <title>Ajout de matière</title>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="../css/ruang-admin.min.css" rel="stylesheet">
</head>

<body id="page-top">
  <div id="wrapper">
    <!-- Sidebar -->
    <ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.php">
        <div class="sidebar-brand-icon">
          <!-- <img src="../img/logo/logo2.png"> -->
        </div>
        <div class="sidebar-brand-text mx-3">....</div>
      </a>
      <hr class="sidebar-divider my-0">
      <li class="nav-item active">
        <a class="nav-link" href="dashboard.php">
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
          <span>Les classes</span>
        </a>
        <div id="collapseBootstrap" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <!-- <h6 class="collapse-header">Bootstrap UI</h6> -->
            <a class="collapse-item" href="ajoutClasse.php">Ajouter une classe</a>
            <a class="collapse-item" href="listeClasse.php">Liste des classes</a>
            
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
            <a class="collapse-item" href="listeEleve.php">Liste des élèves</a>
            <!-- <a class="collapse-item" href="form_advanceds.html">Form Advanceds</a> -->
            <!-- <a class="collapse-item" href="ajoutEleve.php">Ajouter les élèves <br>dans une classe</a> -->
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
            <a class="collapse-item" href="ajoutNote.php">Ajout des notes</a>
          </div>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#coll" aria-expanded="true"
          aria-controls="collapseTable">
          <!-- <i class="fas fa-fw fa-table"></i> -->
          <span>Les matières</span>
        </a>
        <div id="coll" class="collapse" aria-labelledby="headingTable" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <!-- <h6 class="collapse-header">Tables</h6> -->
            <a class="collapse-item" href="ajoutMatiere.php">Ajout de matière</a>
          </div>
        </div>
      </li>
    </ul>
    <!-- Sidebar -->
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <!-- TopBar -->
        <nav class="navbar navbar-expand navbar-light bg-navbar topbar mb-4 static-top">
          <button id="sidebarToggleTop" class="btn btn-link rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>
          <ul class="navbar-nav ml-auto">
            
            <div class="topbar-divider d-none d-sm-block"></div>
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <span>
                   <?php echo $_SESSION['nom']." ".$_SESSION['prenom']; ?>
                </span>
                <img class="img-profile rounded-circle" src="../img/man.png" style="max-width: 60px">
                
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
        <!-- Topbar -->

        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Ajout de matière</h1>
            
          </div>

                    <?php 

                      if(isset($message)){
                        echo '<div class="alert alert-info text-center" role="alert">';
                          echo '<h6><i class="fas fa-info"></i><b> Information</b></h6>';
                          echo $message;
                          echo "</div>";
                      }

                    ?>

          
          <form method="post">
            <div class="row">
              <div class="col">
                <div class="form-group">
                  <label>Libellé :</label>
                  <input type="text" class="form-control" id="exampleInputFirstName" placeholder="Entrez le nom de votre matière" name="libelle">
                </div>
              </div>

              <input type="hidden" name="idProf" value="<?php echo $_SESSION['idProf'] ;?>">

              <div class="col">
                <div class="form-group">
                  <label>Coefficient :</label>
                  <input type="text" class="form-control" id="exampleInputLastName" placeholder="Entrez le coefficient" name="coefficient">
                </div>
              </div>
            </div>
                  
              <div class="form-group">
                <input type="submit" class="btn btn-primary btn-block" value="Ajouter" name="ajout">
              </div>
          </form>

          <!--Row-->

         

          <!-- Modal Logout -->
          <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabelLogout"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabelLogout">Oups !</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <p>Voulez-vous quitter l'application ?</p>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Annuler</button>
                  <a href="deconexion.php" class="btn btn-primary">Se déconnecter</a>
                </div>
              </div>
            </div>
          </div>

        </div>
        <!---Container Fluid-->
      </div>
      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
              <span>copyright &copy; <script> document.write(new Date().getFullYear()); </script> - developed by
                <b><a href="https://github.com/EbroVital" target="_blank">Ebrottié Vital</a></b>
              </span>
          </div>
        </div>
      </footer>
      <!-- Footer -->
    </div>
  </div>

  <!-- Scroll to top -->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../js/ruang-admin.min.js"></script>
  <script src="../vendor/chart.js/Chart.min.js"></script>
  <script src="../js/demo/chart-area-demo.js"></script>  
</body>

</html>