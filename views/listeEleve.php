
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
            <h1 class="h3 mb-0 text-gray-800">Liste des élèves</h1>
          </div>

            <?php 
                if(isset($_POST['valider']) && isset($_SESSION['message'])){
                    echo '<div class="alert alert-info text-center alert-dismissible" role="alert">';
                    echo '<h6><i class="fas fa-info"></i><b> Information</b></h6>';
                    echo $_SESSION['message'];
                    echo "</div>";
                }
            ?>


            <div class="tab-title">
                <p class="tab-links active-link" onclick="opentab('choix')">Choix de la classe</p>
                <p class="tab-links" onclick="opentab('liste')">Liste des élèves</p>
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
                                    <th>#</th>
                                    <th>Nom</th>
                                    <th>Prénoms</th>
                                    <th>Sexe</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!$infos) :?>
                                    <tr>
                                        <td colspan="4" class="text-center"> Aucun eleve trouvé ! </td> 
                                    </tr>
                                    <?php else :?>
                                      <?php foreach($infos as $info) :?>
                                        <tr>
                                            <td> <?= $info->id; ?> </td>
                                            <td> <?= $info->nom; ?> </td>
                                            <td> <?= $info->prenom; ?> </td>
                                            <td> <?= $info->sexe; ?> </td>
                                        </tr> 
                                      <?php endforeach ;?> 
                                <?php endif ;?>     
                                
                            </tbody>
                        </table>
                    </div>     
                </div>               
           
            </div>


          
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