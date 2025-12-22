<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <!-- <link href="img/logo/logo.png" rel="icon"> -->
  <title>Mes enseignements</title>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="../css/ruang-admin.min.css" rel="stylesheet">
</head>

<body id="page-top">
  <div id="wrapper">
    <!-- Sidebar -->
      <?php include __DIR__ . "/../layouts/sidebar.php" ; ?>
    <!-- Sidebar -->
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <!-- TopBar -->
          <?php include __DIR__ . '/../layouts/header.php' ; ?>
        <!-- Topbar -->

        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Gérer mes enseignements</h1>
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
                            <label for="">Choisir la classe à intervenir :</label>
                            <select name="classe" id="" class="form-control">
                              <option>-- Sélectionner une classe --</option>
                              <?php foreach($classes as $classe) : ?>
                                <option value="<?= $classe->id ; ?>">
                                  <?= $classe->nom; ?>
                                </option>
                              <?php endforeach ; ?>  
                            </select>
                          </div>
                        </div>

                        <div class="col">
                          <div class="form-group">
                            <label for="">Choisir la matière à enseigner :</label>
                            <select name="matiere" id="" class="form-control">
                              <option value="">-- Sélectionner une matière --</option>
                              <?php foreach ($matieres as $matiere) : ?>
                                <option value="<?= $matiere->id ;?>">
                                  <?= $matiere->nom ; ?>
                                </option>
                              <?php endforeach ; ?>  
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="">Année Scolaire :</label>
                        <input type="text" name="annee" id="" class="form-control" value="2024-2025" readonly>
                      </div>
                        
                        <input type="hidden" name="idProf" value="<?php echo $_SESSION['idProf'] ;?>">
                        
                        <input type="submit" value="Enregistrer" name="valider" class="btn btn-primary btn-block mb-5">
                        
                    </form>
          
          <!--Row-->

      

          <!-- Modal Logout -->
          <?php include __DIR__ . "/../layouts/modal.php"  ; ?>

        </div>
        <!---Container Fluid-->
      </div>

      <!-- Footer -->
          <?php  include __DIR__ . "/../layouts/footer.php" ; ?>
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