<?php
    
    session_start();
    require_once __DIR__ . '/../database/connexion.php';

    // Récupérer les classes du prof
    $prof_id = $_SESSION['idProf'];

    $stmt = $connexion->prepare("SELECT DISTINCT c.id, c.nom
        FROM enseignements en
        JOIN classes c ON en.classe_id = c.id
        WHERE en.professeur_id = :prof_id
        ORDER BY c.nom
    ");
    $stmt->execute(['prof_id' => $prof_id]);
    $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer les élèves si une classe est sélectionnée
    $eleves = [];
    $classe_selectionnee = isset($_GET['classe_id']) ? intval($_GET['classe_id']) : 0;

    if ($classe_selectionnee > 0) {
        $stmt = $connexion->prepare("SELECT e.id, e.nom, e.prenom
            FROM eleves e
            WHERE e.classe_id = :classe_id
            AND e.actif = 1
            ORDER BY e.nom, e.prenom
        ");
        $stmt->execute(['classe_id' => $classe_selectionnee]);
        $eleves = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
  <title>Bulletins Scolaires</title>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link href="../css/ruang-admin.min.css" rel="stylesheet">
</head>



<body id="page-top">
  <div id="wrapper">
    <!-- Sidebar -->
      <?php include __DIR__ . '/../layouts/sidebar.php' ; ?>
    <!-- Sidebar -->
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <!-- TopBar -->
          <?php include __DIR__ . '/../layouts/header.php' ; ?>
        <!-- Topbar -->

        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h2><i class="fas fa-file-pdf"></i> Télécharger les Bulletins</h2>
            <hr>
            
          </div>

          <!-- Sélection de la classe -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="">
                        <div class="form-group">
                            <label for="classe_id">Sélectionnez une classe :</label>
                            <select name="classe_id" id="classe_id" class="form-control" onchange="this.form.submit()">
                                <option value="">-- Choisir une classe --</option>
                                <?php foreach($classes as $classe): ?>
                                    <option value="<?= $classe['id'] ?>" 
                                            <?= $classe_selectionnee == $classe['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($classe['nom']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
          
            <!-- Liste des élèves -->
            <?php if ($classe_selectionnee > 0): ?>
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-users"></i> 
                            Élèves (<?= count($eleves) ?>)
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        
                        <?php if (empty($eleves)): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong class="text-danger"> Aucun élève dans cette classe. </strong>
                            </div>
                        <?php else: ?>
                            
                            <div class="row">
                                <?php foreach($eleves as $eleve): ?>
                                    <div class="col-md-6">
                                        <div class="eleve-card">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h5 class="mb-1">
                                                        <i class="fas fa-user"></i>
                                                        <?= htmlspecialchars($eleve['prenom']) ?> 
                                                        <?= strtoupper($eleve['nom']) ?>
                                                    </h5>
                                                </div>
                                                <div>
                                                    <div class="btn-group">
                                                        <a href="generer_bulletin.php?eleve_id=<?= $eleve['id'] ?>&trimestre=1" 
                                                           class="btn btn-sm btn-success btn-bulletin" 
                                                           target="_blank"
                                                           title="Trimestre 1">
                                                            <i class="fas fa-download"></i> T1
                                                        </a>
                                                        <a href="generer_bulletin.php?eleve_id=<?= $eleve['id'] ?>&trimestre=2" 
                                                           class="btn btn-sm btn-info btn-bulletin" 
                                                           target="_blank"
                                                           title="Trimestre 2">
                                                            <i class="fas fa-download"></i> T2
                                                        </a>
                                                        <a href="generer_bulletin.php?eleve_id=<?= $eleve['id'] ?>&trimestre=3" 
                                                           class="btn btn-sm btn-warning btn-bulletin" 
                                                           target="_blank"
                                                           title="Trimestre 3">
                                                            <i class="fas fa-download"></i> T3
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <!-- Télécharger tous les bulletins -->
                            <div class="mt-4 text-center">
                                <h5 class="mb-3">
                                    <i class="fas fa-file-archive"></i> 
                                    Télécharger tous les bulletins (ZIP)
                                </h5>
                                <div class="btn-group btn-group-lg" role="group">
                                    <a href="generer_tous_bulletins.php?classe_id=<?= $classe_selectionnee ?>&trimestre=1" 
                                       class="btn btn-success" target="_blank">
                                        <i class="fas fa-download"></i> 
                                        Trimestre 1
                                    </a>
                                    <a href="generer_tous_bulletins.php?classe_id=<?= $classe_selectionnee ?>&trimestre=2" 
                                       class="btn btn-info" target="_blank">
                                        <i class="fas fa-download"></i> 
                                        Trimestre 2
                                    </a>
                                    <a href="generer_tous_bulletins.php?classe_id=<?= $classe_selectionnee ?>&trimestre=3" 
                                       class="btn btn-warning" target="_blank">
                                        <i class="fas fa-download"></i> 
                                        Trimestre 3
                                    </a>
                                </div>
                            </div>    
                            
                        <?php endif; ?>
                        
                    </div>
                </div>
            <?php endif; ?>

         

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
          <?php include __DIR__ . '/../layouts/footer.php' ;?>
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
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<style>
        .eleve-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            transition: all 0.3s;
        }
        .eleve-card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        .btn-bulletin {
            margin: 2px;
        }
    </style>