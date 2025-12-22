<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <!-- <link href="img/logo/logo.png" rel="icon"> -->
  <title>Tableau de bord</title>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="../css/ruang-admin.min.css" rel="stylesheet">
  <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
  <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> -->
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
            <h1 class="h3 mb-0 text-gray-800">Mon espace</h1>
          </div>

          <div class="row mb-3">
            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Ma classe</div>
                      <div class="h5 font-weight-bold text-gray-800">
                        <?= htmlspecialchars($infos['classe_nom']) ?>
                      </div>
                      
                    </div>
                    
                  </div>
                </div>
              </div>
            </div>


            <!-- Earnings (Annual) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <?php if ($dashboard['classement']): ?>
                <div class="stat-card bg-warning text-white">
                  <div class="stat-number">
                                        <?= $dashboard['classement']['classement'] ?>e
                  </div>
                  <div class="stat-label text-white">
                                        sur <?= $dashboard['nb_eleves_classe'] ?> élèves
                  </div>
                </div>
              <?php endif; ?>
            </div>


            <!-- New User Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div>
                  <div class="stat-card bg-primary text-white">
                    <div class="stat-number">
                        <?= $stats['moyenne_generale_brute'] ?? 'N/A' ?>
                    </div>
                  <div class="stat-label text-white">
                      <i class="fas fa-chart-line"></i> Moyenne générale
                  </div>
                </div>
              </div>
            </div>
            <!-- Pending Requests Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="stat-card bg-success text-white">
                <div class="stat-number">
                    <?= $stats['nb_notes_total'] ?? 0 ?>
                </div>
                <div class="stat-label text-white">
                    <i class="fas fa-file-alt"></i> Notes enregistrées
                </div>
              </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
              <div class="stat-card bg-info text-white">
                  <div class="stat-number">
                      <?= $stats['nb_matieres'] ?? 0 ?>
                  </div>
                  <div class="stat-label text-white">
                      <i class="fas fa-book"></i> Matières suivies
                  </div>
              </div>
            </div>

            <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-calendar-alt"></i> Moyennes par trimestre
                </div>
                <div class="card-body">
                    <?php if (empty($dashboard['moyennes_trimestre'])): ?>
                        <p class="text-muted">Aucune note enregistrée</p>
                    <?php else: ?>
                        <?php foreach ($dashboard['moyennes_trimestre'] as $trim): ?>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h5 class="mb-0">Trimestre <?= $trim['trimestre'] ?></h5>
                                    <small class="text-muted">
                                        <?= $trim['nb_notes'] ?> notes • <?= $trim['nb_matieres'] ?> matières
                                    </small>
                                </div>
                                <div>
                                    <span class="badge badge-primary badge-custom">
                                        <?= $trim['moyenne_ponderee'] ?>/20
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
          </div>

              <div class="col-12">
                <div class="card">
                      <div class="card-header bg-dark text-white">
                          <i class="fas fa-tools"></i> Actions rapides
                      </div>
                      <div class="card-body text-center">
                          <a href="../views/generer_bulletin.php?eleve_id=<?= $eleve_id ?>&trimestre=1" 
                            class="btn btn-primary btn-lg mr-2" target="_blank">
                              <i class="fas fa-download"></i> Télécharger Bulletin T1
                          </a>
                          <a href="../views/generer_bulletin.php?eleve_id=<?= $eleve_id ?>&trimestre=2" 
                            class="btn btn-info btn-lg mr-2" target="_blank">
                              <i class="fas fa-download"></i> Télécharger Bulletin T2
                          </a>
                          <a href="../views/generer_bulletin.php?eleve_id=<?= $eleve_id ?>&trimestre=3" 
                            class="btn btn-warning btn-lg" target="_blank">
                              <i class="fas fa-download"></i> Télécharger Bulletin T3
                          </a>
                      </div>
                </div>
              </div>

              <div class="col-6 mt-3">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <i class="fas fa-clock"></i> Notes récentes
                    </div>
                    <div class="card-body">
                        <?php if (empty($dashboard['notes_recentes'])): ?>
                            <p class="text-muted">Aucune note récente</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <tbody>
                                        <?php foreach ($dashboard['notes_recentes'] as $note): ?>
                                            <tr>
                                                <td>
                                                    <strong><?= htmlspecialchars($note['matiere']) ?></strong><br>
                                                    <small class="text-muted">
                                                        <?= date('d/m/Y', strtotime($note['date_evaluation'])) ?>
                                                    </small>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-<?= $note['note'] >= 10 ? 'success' : 'danger' ?>">
                                                        <?= $note['note'] ?>/20
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
              </div>
              
              <div class="col-6 mt-3">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <i class="fas fa-star"></i> Points forts
                    </div>
                    <div class="card-body">
                        <?php if (empty($dashboard['points_forts'])): ?>
                            <p class="text-muted">Pas encore de données</p>
                        <?php else: ?>
                            <?php foreach ($dashboard['points_forts'] as $matiere): ?>
                                <div class="matiere-item" style="border-left-color: #28a745;">
                                    <div class="d-flex justify-content-between">
                                        <strong><?= htmlspecialchars($matiere['matiere']) ?></strong>
                                        <span class="badge badge-success"><?= $matiere['moyenne'] ?>/20</span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
              </div>

                <div class="col-6 mt-3">
                  <div class="card">
                      <div class="card-header bg-warning text-white">
                          <i class="fas fa-exclamation-triangle"></i> À améliorer
                      </div>
                      <div class="card-body">
                          <?php if (empty($dashboard['matieres_a_ameliorer'])): ?>
                              <p class="text-success">
                                  <i class="fas fa-check-circle"></i> Toutes les matières sont au-dessus de 10/20 !
                              </p>
                          <?php else: ?>
                              <?php foreach ($dashboard['matieres_a_ameliorer'] as $matiere): ?>
                                  <div class="matiere-item" style="border-left-color: #dc3545;">
                                      <div class="d-flex justify-content-between">
                                          <strong><?= htmlspecialchars($matiere['matiere']) ?></strong>
                                          <span class="badge badge-danger"><?= $matiere['moyenne'] ?>/20</span>
                                      </div>
                                  </div>
                              <?php endforeach; ?>
                          <?php endif; ?>
                      </div>
                  </div>
                </div>

          <!-- Progression -->
          <?php if (!empty($dashboard['progression'])): ?>
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-secondary text-white">
                            <i class="fas fa-chart-line"></i> Progression
                        </div>
                        <div class="card-body text-center">
                            <h3 class="<?= $dashboard['progression']['sens'] == 'hausse' ? 'progression-up' : 'progression-down' ?>">
                                <?php if ($dashboard['progression']['sens'] == 'hausse'): ?>
                                    <i class="fas fa-arrow-up"></i> +<?= $dashboard['progression']['difference'] ?> points
                                <?php elseif ($dashboard['progression']['sens'] == 'baisse'): ?>
                                    <i class="fas fa-arrow-down"></i> <?= $dashboard['progression']['difference'] ?> points
                                <?php else: ?>
                                    <i class="fas fa-minus"></i> Stable
                                <?php endif; ?>
                            </h3>
                            <p class="text-muted">
                                <?= abs($dashboard['progression']['pourcentage']) ?>% par rapport au trimestre précédent
                            </p>
                        </div>
                    </div>
                </div>
            </div>
          <?php endif; ?>


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
      <!-- Footer -->
      <!-- <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
              <span>copyright &copy; <script> document.write(new Date().getFullYear()); </script> - developed by
                <b><a href="https://github.com/EbroVital" target="_blank">Ebrottié Vital</a></b>
              </span>
          </div>
        </div>
      </footer> -->

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
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script> -->

</body>

</html>

<style>
        .stat-card {
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
        }
        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
        }
        .badge-custom {
            padding: 8px 15px;
            font-size: 0.9rem;
        }
        .progression-up {
            color: #28a745;
        }
        .progression-down {
            color: #dc3545;
        }
        .matiere-item {
            padding: 10px;
            border-left: 4px solid #007bff;
            margin-bottom: 10px;
            background: #f8f9fa;
        }
</style>

