
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <!-- <link href="img/logo/logo.png" rel="icon"> -->
  <title>Tableau de bord Professeur</title>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="../css/ruang-admin.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script> -->
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
            <h1 class="h3 mb-0 text-gray-800">Tableau de bord</h1>
            
          </div>

          <div class="row mb-3">
            <!-- Earnings (Monthly) Card Example -->
            <!-- <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Mes classes (<?= $requete['nombre_classes'] ; ?>) </div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                           <?php 

                                echo "<ul>";
                                  foreach ($classes_array as $classe) {
                                      echo "<li>$classe</li>";
                                  }
                                echo "</ul>";

                           ?>
                      </div>
                      <div class="mt-2 mb-0 text-muted text-xs">
                       
                      </div>
                    </div>
                    
                  </div>
                </div>
              </div>
            </div> -->
            <!-- Earnings (Annual) Card Example -->
            <div class="col-xl-3 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Spécialité</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $specialite['specialite'] ; ?>
                      </div>
                     
                    </div>
                    
                  </div>
                </div>
              </div>
            </div>
            <!-- New User Card Example -->
            <div class="col-lg-3 col-md-6">
              <div class="stat-card text-white" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                  <i class="fas fa-file-alt stat-icon"></i>
                  <p class="stat-label">Notes saisies</p>
                  <h2 class="stat-number"><?= number_format($stats['nb_notes_saisies']) ?></h2>
              </div>
            </div>
            <!-- Pending Requests Card Example -->
            <!-- <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1"> </div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                      
                      </div>
                      
                    </div>
                    <div class="col-auto">
                      
                    </div>
                  </div>
                </div>
              </div>
            </div> -->

            <div class="col-lg-3 col-md-6">
              <div class="stat-card text-white" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                  <i class="fas fa-users stat-icon"></i>
                  <p class="stat-label">Élèves au total</p>
                  <h2 class="stat-number"><?= $stats['nb_eleves_total'] ?></h2>
              </div>
            </div>

            <div class="col-lg-3">
              <div class="stat-card text-white" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                  <i class="fas fa-chart-line stat-icon"></i>
                  <h2 class="stat-number"><?= $stats['moyenne_generale'] ?? 'N/A' ?><small>/20</small></h2>
                  <p class="stat-label">Moyenne générale</p>
              </div>
            </div>
            
          </div>

            <div class="row mb-4">
              
              <div class="col-lg-4 mt-3">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-trophy"></i> Taux de réussite</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($dashboard['taux_reussite'])): ?>
                            <?php 
                            $total_reussite = 0;
                            $total_eleves = 0;
                            foreach($dashboard['taux_reussite'] as $classe) {
                                $total_reussite += $classe['nb_reussite'];
                                $total_eleves += $classe['nb_eleves'];
                            }
                            $taux_global = $total_eleves > 0 ? round(($total_reussite / $total_eleves) * 100, 1) : 0;
                            ?>
                            
                            <div class="text-center mb-3">
                                <h1 class="display-3 text-success"><?= $taux_global ?>%</h1>
                                <p class="text-muted">d'élèves avec moyenne ≥ 10</p>
                            </div>
                            
                            <?php foreach($dashboard['taux_reussite'] as $classe): ?>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span><?= htmlspecialchars($classe['classe']) ?></span>
                                        <span class="text-<?= $classe['taux_reussite'] >= 80 ? 'success' : ($classe['taux_reussite'] >= 60 ? 'warning' : 'danger') ?>">
                                            <?= $classe['taux_reussite'] ?>%
                                        </span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-<?= $classe['taux_reussite'] >= 80 ? 'success' : ($classe['taux_reussite'] >= 60 ? 'warning' : 'danger') ?>" 
                                            style="width: <?= $classe['taux_reussite'] ?>%"></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted text-center">Pas encore de données</p>
                        <?php endif; ?>
                    </div>
                </div>
              </div>

            </div>

              <!-- Mes classes -->
            <div class="row mb-4">
              <div class="col-12">
                <div class="card shadow-sm">
                  <div class="card-header card-header-custom">
                      <h5 class="mb-0"><i class="fas fa-school"></i> Mes classes</h5>
                  </div>
                  <div class="card-body">
                      <div class="row">
                          <?php foreach($dashboard['classes'] as $index => $classe): ?>
                              <?php 
                              $colors = ['primary', 'info', 'success', 'warning', 'danger', 'secondary'];
                              $color = $colors[$index % count($colors)];
                              ?>
                              <div class="col-md-4 mb-3">
                                  <div class="card border-<?= $color ?>">
                                      <div class="card-body">
                                          <h5 class="card-title text-<?= $color ?>">
                                              <i class="fas fa-users"></i> <?= htmlspecialchars($classe['classe_nom']) ?>
                                          </h5>
                                          <p class="mb-2">
                                              <strong><?= $classe['nb_eleves'] ?> élèves</strong> • 
                                              <span class="text-muted"><?= htmlspecialchars($classe['matieres']) ?></span>
                                          </p>
                                          <div class="d-flex justify-content-between">
                                              <span class="badge badge-<?= $color ?> badge-lg">
                                                  <?= $classe['nb_matieres_enseignees'] ?> matière(s)
                                              </span>
                                              
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          <?php endforeach; ?>
                      </div>
                  </div>
                </div>
              </div>
            </div>

              <!-- Notes récentes et Alertes -->
            <div class="row mb-4">
              <div class="col-lg-6">
                <div class="card shadow-sm">
                  <div class="card-header bg-primary text-white">
                      <h5 class="mb-0"><i class="fas fa-clock"></i> Notes récentes</h5>
                  </div>
                  <div class="card-body p-0">
                      <div class="table-responsive">
                          <table class="table table-hover mb-0">
                              <thead class="thead-light">
                                  <tr>
                                      <th>Élève</th>
                                      <th>Classe</th>
                                      <th>Matière</th>
                                      <th>Note</th>
                                      <th>Date</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <?php if (empty($dashboard['notes_recentes'])): ?>
                                      <tr><td colspan="5" class="text-center text-muted">Aucune note récente</td></tr>
                                  <?php else: ?>
                                      <?php foreach($dashboard['notes_recentes'] as $note): ?>
                                          <tr>
                                              <td><?= htmlspecialchars($note['eleve_prenom'] . ' ' . $note['eleve_nom']) ?></td>
                                              <td><?= htmlspecialchars($note['classe_nom']) ?></td>
                                              <td><?= htmlspecialchars($note['matiere']) ?></td>
                                              <td>
                                                  <span class="badge badge-<?= $note['note'] >= 16 ? 'success' : ($note['note'] >= 10 ? 'warning' : 'danger') ?>">
                                                      <?= $note['note'] ?>/20
                                                  </span>
                                              </td>
                                              <td><?= date('d/m/Y', strtotime($note['date_evaluation'])) ?></td>
                                          </tr>
                                      <?php endforeach; ?>
                                  <?php endif; ?>
                              </tbody>
                          </table>
                      </div>
                  </div>
                </div>
              </div>
               <div class="col-lg-6 mt-3">
                  <div class="card shadow-sm">
                      <div class="card-header bg-warning text-white">
                          <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Alertes</h5>
                      </div>
                      <div class="card-body">
                          <?php if (!empty($dashboard['alertes_eleves_sans_notes'])): ?>
                              <div class="alert alert-info">
                                  <strong class="text-danger"><i class="fas fa-user-clock"></i> <?= count($dashboard['alertes_eleves_sans_notes']) ?> élèves sans notes depuis 30 jours</strong> 
                                  
                              </div>
                          <?php endif; ?>
                          
                          <?php if (!empty($dashboard['eleves_difficulte'])): ?>
                              <div class="alert alert-info">
                                  <strong class="text-danger"><i class="fas fa-chart-line"></i> <?= count($dashboard['eleves_difficulte']) ?> élèves  en difficulté (moyenne &lt; 10) </strong> 
                                 
                              </div>
                          <?php endif; ?>
                          
                          <?php if (!empty($dashboard['meilleurs_eleves'])): ?>
                              <div class="alert alert-success">
                                  <strong class="text-secondary"><i class="fas fa-star"></i> <?= count($dashboard['meilleurs_eleves']) ?> élèves
                                  avec excellents résultats (≥ 16)</strong> 
                              </div>
                          <?php endif; ?>
                      </div>
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
  <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script> -->

</body>

</html>

<style>
        body { background: #f4f6f9; }
        .stat-card {
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.2);
        }
        .stat-number { font-size: 3rem; font-weight: bold; margin: 0; }
        .stat-label { font-size: 0.95rem; opacity: 0.9; margin-top: 5px; }
        .stat-icon {
            font-size: 3rem;
            opacity: 0.3;
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
        }
        .card-header-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .chart-container { position: relative; height: 300px; }
</style>

