<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <!-- <link href="img/logo/logo.png" rel="icon"> -->
  <title>Moyenne</title>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="../css/ruang-admin.min.css" rel="stylesheet">
</head>

<body id="page-top">
  <div id="wrapper">
    <!-- Sidebar -->
      <?php include __DIR__ . '/../layouts/sidebarEleve.php' ; ?>
    <!-- Sidebar -->
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <!-- TopBar -->
          <?php include __DIR__ . '/../layouts/headerEleve.php' ; ?>
        <!-- Topbar -->

        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Mes moyennes </h1>
          </div>

          <div class="row mb-3">
           
            <div class="tab-title">
                <p class="tab-links active-link" onclick="opentab('trimestre_1')">1er Trimestre</p>
                <p class="tab-links" onclick="opentab('trimestre_2')">2ième Trimestre</p>
                <p class="tab-links" onclick="opentab('trimestre_3')">3ième Trimestre</p>
            </div> 
            
              <div class="col-lg-12 mb-4">
                <!-- Simple Tables -->
                <div class="tab-contents active-tab card" id="trimestre_1">
                  <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <!-- <h6 class="m-0 font-weight-bold text-primary">1er Trimestre </h6> -->
                  </div>
                  <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                      <thead class="thead-light">
                        <tr>
                          <th class="text-center">Matière</th>
                          <th class="text-center">coefficient</th>
                          <th class="text-center"> Moyenne </th>
                          <th class="text-center"> Nombre de notes </th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(empty($moyennes)) : ?> 
                          <tr>
                            <td colspan="4" class="text-center">
                              Moyenne pas disponible !
                            </td>
                          </tr>
                        <?php else : ?>  
                          <?php foreach($moyennes as $moyenne) : ?>
                            <tr>
                              <td class="text-center"> <?php echo $moyenne['matiere']  ;?> </td>
                              <td class="text-center"> <?php echo $moyenne['coef_matiere']  ;?> </td>
                              <td class="text-center"> 
                                <?php
                                  if( $moyenne['moyenne_matiere'] > 10) {
                                    echo "<span class='badge badge-success'>"."". 
                                     $moyenne['moyenne_matiere'] ;  
                                    echo "</span>";
                                  } else {
                                    echo "<span class='badge badge-danger'>"."".
                                    $moyenne['moyenne_matiere'] ;
                                    echo "</span>";
                                  }
                                  
                                ;?> 
                              </td>
                              <td class="text-center"> <?php echo $moyenne['nb_notes']  ;?> </td>
                            </tr>
                          <?php endforeach ; ?>  
                        <?php endif ;?>  
                      </tbody>
                    </table>
                  </div>
                  
                </div>

                <div class="tab-contents card" id="trimestre_2">
                  <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <!-- <h6 class="m-0 font-weight-bold text-primary">1er Trimestre </h6> -->
                  </div>
                  <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                      <thead class="thead-light">
                        <tr>
                          <th class="text-center">Matière</th>
                          <th class="text-center">coefficient</th>
                          <th class="text-center"> Moyenne </th>
                          <th class="text-center"> Nombre de notes </th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(empty($moys)) : ?>
                            <tr>
                              <td colspan="4" class="text-center"> Moyenne pas disponible ! </td>
                            </tr>
                        <?php else : ?>    
                          <?php foreach($moys as $moy) : ?>
                              <tr>
                                <td class="text-center"> <?php echo $moy['matiere']  ;?> </td>
                                <td class="text-center"> <?php echo $moy['coef_matiere']  ;?> </td>
                                <td class="text-center"> 
                                  <?php
                                    if( $moy['moyenne_matiere'] > 10) {
                                      echo "<span class='badge badge-success'>"."". 
                                      $moy['moyenne_matiere'] ;  
                                      echo "</span>";
                                    } else {
                                      echo "<span class='badge badge-danger'>"."".
                                      $moy['moyenne_matiere'] ;
                                      echo "</span>";
                                    }
                                    
                                  ;?> 
                                </td>
                                <td class="text-center"> <?php echo $moy['nb_notes']  ;?> </td>
                              </tr>
                          <?php endforeach ; ?> 
                        <?php endif ; ?>  
                      </tbody>
                    </table>
                  </div>
                  
                </div>

                <div class="tab-contents card" id="trimestre_3">
                  <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    
                  </div>
                  <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                      <thead class="thead-light">
                        <tr>
                          <th class="text-center">Matière</th>
                          <th class="text-center">coefficient</th>
                          <th class="text-center"> Moyenne </th>
                          <th class="text-center"> Nombre de notes </th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(empty($moyenns)) : ?>
                            <tr>
                              <td colspan="4" class="text-center">Moyenne pas disponible ! </td>
                            </tr>
                        <?php else : ?>    
                          <?php foreach($moyenns as $moy) : ?>
                              <tr>
                                <td class="text-center"> <?php echo $moy['matiere']  ;?> </td>
                                <td class="text-center"> <?php echo $moy['coef_matiere']  ;?> </td>
                                <td class="text-center"> 
                                  <?php
                                    if( $moy['moyenne_matiere'] > 10) {
                                      echo "<span class='badge badge-success'>"."". 
                                      $moy['moyenne_matiere'] ;  
                                      echo "</span>";
                                    } else {
                                      echo "<span class='badge badge-danger'>"."".
                                      $moy['moyenne_matiere'] ;
                                      echo "</span>";
                                    }
                                    
                                  ;?> 
                                </td>
                                <td class="text-center"> <?php echo $moy['nb_notes']  ;?> </td>
                              </tr>
                          <?php endforeach ; ?> 
                        <?php endif ; ?>  
                      </tbody>
                    </table>
                  </div>
                  
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
                  <a href="../views/deconexion.php?user=eleve" class="btn btn-primary">Se déconnecter</a>
                </div>
              </div>
            </div>
          </div>

        </div>
        <!---Container Fluid-->
      </div>
      

        <?php include __DIR__ . '/../layouts/footer.php' ; ?>

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
        margin: 20px 0 40px ;
        padding-left: 15px;
    }
    form, #liste{
        margin: 40px 20px;
    }
    .tab-links{
        margin-right: 40px;
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