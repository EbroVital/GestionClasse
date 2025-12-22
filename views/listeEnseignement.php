<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <!-- <link href="img/logo/logo.png" rel="icon"> -->
  <title>Liste</title>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="../css/ruang-admin.min.css" rel="stylesheet">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body id="page-top">
  <div id="wrapper">
    <!-- Sidebar -->
      <?php include __DIR__ . "/../layouts/sidebar.php"   ; ?>
    <!-- Sidebar -->
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <!-- TopBar -->
          <?php include __DIR__ . "/../layouts/header.php" ; ?>
        <!-- Topbar -->

        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Mes enseignements</h1>
          </div>

                  <?php 

                      if(isset($_SESSION['msg'])){

                        echo '<div class="alert alert-info text-center" role="alert">';
                        echo '<h6><i class="fas fa-info"></i><b> Information</b></h6>';
                        echo $_SESSION['msg'];
                        echo "</div>";

                      }

                  ?>


          <div class="row">
            <div class="col-lg-12 mb-4">
              <!-- Simple Tables -->
              <div class="card">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Vous intervenez : </h6>
                </div>
                <div class="table-responsive">
                  <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                      <tr>
                        <th class="text-center">Classe</th>
                        <th class="text-center">Matière</th>
                        <th class="text-center"> Actions </th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach($stmt as $element) : ?>
                      
                        <tr>
                            <td class="text-center"> <?= $element->classe ;?> </td>
                           
                            <td class="text-center"> <?= $element->matiere ;?> </td>
                        

                            <td class="text-center">
                                <button 
                                  class="btn btn-danger btn-delete" 
                                  data-ens="<?= $element->id ?>" 
                                  data-mat="<?= $element->matiere_id ?>" 
                                  data-classe="<?= $element->classe_id ?>"
                                  data-matiere-nom="<?= htmlspecialchars($element->matiere) ?>"
                                  data-classe-nom="<?= htmlspecialchars($element->classe) ?>">
                                  <i class="fas fa-trash"></i> Supprimer
                                </button>
                            </td>

                        </tr>
                      
                      <?php endforeach; ?>

                    </tbody>
                  </table>
                </div>
                <div class="card-footer"></div>
              </div>
            </div>
          </div>
          
          <!--Row-->

         

          <!-- Modal Logout -->
          <?php include __DIR__ . "/../layouts/modal.php"  ; ?>

        </div>
        <!---Container Fluid-->
      </div>
      <!-- Footer -->
          <?php include __DIR__ . "/../layouts/footer.php" ; ?>
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

  <script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Sélectionner tous les boutons de suppression
    const deleteButtons = document.querySelectorAll('.btn-delete');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Récupérer les données
            const ensId = this.dataset.ens;
            const matId = this.dataset.mat;
            const classeId = this.dataset.classe;
            const matiereNom = this.dataset.matiereNom;
            const classeNom = this.dataset.classeNom;
            
            // Afficher la confirmation
            Swal.fire({
                title: 'Êtes-vous sûr ?',
                html: `Vous allez supprimer l'enseignement :<br><br>
                       <strong>${matiereNom}</strong> en <strong>${classeNom}</strong>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Afficher un loader
                    Swal.fire({
                        title: 'Suppression en cours...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Rediriger vers la page de suppression
                    window.location.href = `../views/delEnseignement.php?Ens=${ensId}&Mat=${matId}&classe=${classeId}`;
                }
            });
        });
    });
});
</script>

</body>


</html>


