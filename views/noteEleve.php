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
            <h1 class="h3 mb-0 text-gray-800">Liste des notes par classe</h1>
          </div>

                <?php 

                    if(isset($_POST['valider'])){

                        echo '<div class="alert alert-info text-center alert-dismissible" role="alert">';
                        echo '<h6><i class="fas fa-info"></i><b> Information</b></h6>';
                        echo $_SESSION['message'];
                        echo "</div>";

                    }

                ?>

                <div class="tab-title">
                <p class="tab-links active-link" onclick="opentab('choix')">Choix de la classe</p>
                <p class="tab-links" onclick="opentab('liste')">Liste des élèves et leurs notes</p>
            </div>  

            <div class="tab-contents active-tab" id="choix">
                <form  method="post">
                    <div class="form-group">
                        <label for="">Sélectionner la classe :</label>
                        <select name="classe"  class="form-control">
                          <option value="">-- Sélectionner une classe --</option>
                            <?php foreach($classes as $classe) : ?>
                                <option value="<?= $classe->id; ?>">
                                   <?= $classe->nom  ; ?>
                                </option>
                            <?php endforeach ;?>    
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Choisir" name="valider" class="btn btn-primary btn-block">
                    </div>
                </form>
            </div>

            <div class="tab-contents" id="liste">
            
                <div class="col-lg-12">
                    <div class="card mb-2">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    </div>
                    <div class="table-responsive p-3">
                      <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Nom</th>
                                    <th>Prénoms</th>
                                    <th>Evaluation</th>
                                    <th>Date</th>
                                    <th>Note</th>
                                    <th>Trimestre</th>
                                </tr>
                            </thead>
                              <tbody>
                                <?php if(!$infos || count($infos) == 0) :?>
                                  <tr>
                                      <td colspan="6" class="text-center">Aucun élève trouvé !</td>
                                  </tr>
                                <?php else :?>
                                    <?php foreach($infos as $eleve) :?>
                                        <tr>
                                            <td class="text-center"><?= htmlspecialchars($eleve->nom); ?></td>
                                            <td class="text-center"><?= htmlspecialchars($eleve->prenom); ?></td>
                                            <td class="text-center"><?= htmlspecialchars($eleve->type_evaluation ?? '-'); ?></td>
                                            <td class="text-center"><?= htmlspecialchars($eleve->date_evaluation ?? '-'); ?></td>
                                            <td class="text-center"><?= htmlspecialchars($eleve->note ?? '-'); ?></td>
                                            <td class="text-center"><?= htmlspecialchars($eleve->trimestre ?? '-'); ?></td>
                                        </tr>
                                    <?php endforeach ;?> 
                                <?php endif ;?>

                              </tbody>
                      </table>
                    </div>     
                </div>               
           
            </div>
          
          
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
                  <a href="../views/deconexion.php?user=prof" class="btn btn-primary">Se déconnecter</a>
                </div>
              </div>
            </div>
          </div>

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
</body>

</html>

<style>

    .tab-title{
        display: flex;
        margin: 20px 0 40px;
    }
    form, #liste{
        margin: 40px 20px;
    }
    .tab-links{
        margin-right: 50px;
        font-size: 18px;
        font-weight: 500;
        cursor: pointer;
        position: relative;
    }
    .tab-links::after{
        content: '';
        width: 0;
        height: 4px;
        position: absolute;
        background:#4C60DA;
        left: 0;
        bottom: -8px;
        transition: 0.5s;
    }
    .tab-links.active-link::after{
        width: 100%;
    }

    .tab-contents{
        display: none;
    }
    .tab-contents.active-tab{
        display: block;
    }

</style>

<script>

    let tablinks = document.getElementsByClassName('tab-links');
    let tabcontents = document.getElementsByClassName('tab-contents');

    function opentab(tabname){
        for(tablink of tablinks){
            tablink.classList.remove('active-link');
        }
        for(tabcontent of tabcontents){
            tabcontent.classList.remove('active-tab');
        }
        event.currentTarget.classList.add('active-link');
        document.getElementById(tabname).classList.add('active-tab');
    }

</script>