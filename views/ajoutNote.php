
<!DOCTYPE html>
<html lang="fr">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- <link href="img/logo/logo.png" rel="icon"> -->
    <title>Ajout des notes</title>
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="../css/ruang-admin.min.css" rel="stylesheet">
  </head>

  

  <body id="page-top">
    <div id="wrapper">
      <!-- Sidebar -->
      <?php include __DIR__ . "/../layouts/sidebar.php"  ;?>
      <!-- Sidebar -->
      <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
          <!-- TopBar -->
          <?php include __DIR__ . "/../layouts/header.php" ;?>
          <!-- Topbar -->

          <!-- Container Fluid-->
          <div class="container-fluid" id="container-wrapper">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
              <h1 class="h3 mb-0 text-gray-800">Ajout de note</h1>
              
            </div>

            <!-- <form method="post" id="formNotes">
            
              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label for="">Classe :</label>
                      <select name="classe" id="classe" class="form-control mb-3">
                        <option>-Choisissez une classe-</option>
                          <?php foreach ($classes as $classe ) : ?>
                            <option value="<?= $classe->id ;?>">
                                <?= $classe->nom ; ?>
                            </option>
                          <?php endforeach ;?>  
                      </select>
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label for="">Elève :</label>
                      <select name="eleve" id="" class="form-control mb-3">
                        <option>-Choisissez un élève-</option>
                            
                      </select>
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label for="">Matière :</label>
                      <select name="matiere" id="" class="form-control mb-3">
                        <option>-Choisissez la matière-</option>
                        <?php foreach ( $matieres as $matiere ) : ?>
                          <option value="<?= $matiere->id ; ?>">
                            <?= $matiere->nom ;?>
                          </option>
                        <?php endforeach ;?>  
                      </select>
                  </div>
                </div>
              </div>
              
              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label>Note : </label>
                    <input type="text" class="form-control" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Entrez la note" name="note">
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label for="">Type d'évaluation :</label>
                      <select name="eval" id="" class="form-control mb-3">
                        <option>-Choisissez le type-</option>
                          <option value="Devoir">Devoir</option>
                          <option value="Contrôle">Contrôle</option>
                          <option value="Examen">Examen</option>
                          <option value="Oral">Oral</option>
                          <option value="TP">TP</option>
                      </select>
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label>Coefficient : </label>
                    <input type="text" class="form-control" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Entrez le coefficient" name="coefficient">
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label>Trimestre : </label>
                    <input type="number" class="form-control" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Entrez le trimestre" name="trimestre">
                  </div>
                </div>

                <div class="col">
                  <div class="form-group">
                    <label>Date d'évaluation : </label>
                    <input type="date" class="form-control" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Entrez la date" name="date">
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                  <label>Commentaire : </label>
                  <textarea name="commentaire" id="" class="form-control"></textarea>
              </div>

              <input type="submit" value="Enregistrer" name="valider" class="btn btn-primary btn-block mb-5">
            </form> -->

            
                    <?php 

                      if(isset($message)){

                          echo '<div class="alert alert-info text-center" role="alert">';
                          echo '<h6><i class="fas fa-info"></i><b> Information</b></h6>';
                          echo $message;
                          echo "</div>";
                      }

                    ?>

            <form method="post" id="formNotes">
          
                <div class="row">
                  <div class="col">
                      <div class="form-group">
                          <label for="classe">Classe :</label>
                          <select name="classe" id="classe" class="form-control mb-3" required>
                              <option value="">-Choisissez une classe-</option>
                              <?php foreach ($classes as $classe) : ?>
                                  <option value="<?= $classe->id; ?>">
                                      <?= $classe->nom; ?>
                                  </option>
                              <?php endforeach; ?>  
                          </select>
                      </div>
                  </div>
                  
                  <div class="col">
                      <div class="form-group">
                          <label for="eleve">Élève :</label>
                          <select name="eleve" id="eleve" class="form-control mb-3" required disabled>
                              <option value="">-Choisissez d'abord une classe-</option>
                          </select>
                          <small class="form-text text-muted" id="loading-eleves" style="display:none;">
                              Chargement des élèves...
                          </small>
                      </div>
                  </div>
                  
                  <div class="col">
                      <div class="form-group">
                          <label for="matiere">Matière :</label>
                          <select name="matiere" id="matiere" class="form-control mb-3" required>
                              <option value="">-Choisissez la matière-</option>
                              <?php foreach ($matieres as $matiere) : ?>
                                  <option value="<?= $matiere->id; ?>" data-coefficient="<?= $matiere->coefficient; ?>">
                                      <?= $matiere->nom; ?>
                                  </option>
                              <?php endforeach; ?>  
                          </select>
                      </div>
                  </div>
                </div>
              
                  <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>Note : </label>
                            <input type="number" step="0.25" min="0" max="20" class="form-control" placeholder="Entrez la note" name="note" required>
                        </div>
                    </div>
                    
                    <div class="col">
                        <div class="form-group">
                            <label for="eval">Type d'évaluation :</label>
                            <select name="eval" id="eval" class="form-control mb-3" required>
                                <option value="">-Choisissez le type-</option>
                                <option value="Devoir">Devoir</option>
                                <option value="Contrôle">Contrôle</option>
                                <option value="Examen">Examen</option>
                                <option value="Oral">Oral</option>
                                <option value="TP">TP</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col">
                        <div class="form-group">
                            <label>Coefficient : </label>
                            <input type="number" step="0.1" min="0" class="form-control" id="coefficient" placeholder="Coefficient automatique" name="coefficient" readonly>
                            <small class="form-text text-muted">Se remplit automatiquement selon la matière</small>
                        </div>
                    </div>
                  </div>

                  <div class="row">
                      <div class="col">
                        <div class="form-group">
                          <label>Trimestre : </label>
                            <select name="trimestre" class="form-control" required>
                              <option value="">-Choisissez-</option>
                              <option value="1">Trimestre 1</option>
                              <option value="2">Trimestre 2</option>
                              <option value="3">Trimestre 3</option>
                            </select>
                        </div>
                      </div>

                      <div class="col">
                          <div class="form-group">
                              <label>Date d'évaluation : </label>
                              <input type="date" class="form-control" placeholder="Entrez la date" name="dateEval" required>
                          </div>
                      </div>
                  </div>
              
              <div class="form-group">
                  <label>Commentaire : </label>
                  <textarea name="commentaire" class="form-control" rows="3"></textarea>
              </div>

              <input type="submit" value="Enregistrer" name="valider" class="btn btn-primary btn-block mb-5">

            </form>
            
            <!--Row-->

            <!-- Modal Logout -->
            <?php include __DIR__ . "/../layouts/modal.php"  ; ?>

          </div>
          <!---Container Fluid-->
        </div>
        <!-- Footer -->
        <?php include __DIR__ . "/../layouts/footer.php" ;?>
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

  <script>
      $(document).ready( function() {

          $('#classe').on('change', function() {
              var classeId = $(this).val();
              var eleveSelect = $('#eleve');
              var loadingText = $('#loading-eleves');
              
              if (classeId) {
                  // Afficher le chargement
                  loadingText.show();
                  eleveSelect.prop('disabled', true);
                  eleveSelect.html('<option value=""> Chargement..... </option>');
                  
                  // Requête AJAX pour récupérer les élèves
                  $.ajax({
                      type: "GET",
                      url: "../views/get_eleve.php",
                      data: { classe_id: classeId },
                      dataType: "json",
                      success: function(response) {
                          loadingText.hide();
                          eleveSelect.html('<option value="">-Choisissez un élève-</option>');
                          
                          if (response.success && response.eleves.length > 0) {
                              $.each(response.eleves, function(index, eleve) {
                                  eleveSelect.append(
                                    '<option value="' + eleve.id + '">' + 
                                      eleve.nom + ' ' + eleve.prenom + 
                                  '</option>'
                                  );
                              });
                              eleveSelect.prop('disabled', false);
                          } else {
                              eleveSelect.html('<option value="">Aucun élève dans cette classe</option>');
                          }
                      },
                      error: function(xhr, status, error) {
                          loadingText.hide();
                          console.error("Erreur AJAX:", error);
                          eleveSelect.html('<option value="">Erreur de chargement</option>');
                          alert("Erreur lors du chargement des élèves");
                      }
                  });
              } else {
                  eleveSelect.html('<option value="">-Choisissez d\'abord une classe-</option>');
                  eleveSelect.prop('disabled', true);
              }
          });
          
          // ========================================
          // 2. REMPLIR LE COEFFICIENT SELON LA MATIÈRE
          // ========================================

          $('#matiere').on('change', function() {
              var selectedOption = $(this).find('option:selected');
              var coefficient = selectedOption.data('coefficient');
              
              if (coefficient) {
                  $('#coefficient').val(coefficient);
              } else {
                  $('#coefficient').val('');
              }
          });
          
          // ========================================
          // 3. VALIDATION DU FORMULAIRE (optionnel)
          // ========================================

          $('#formNotes').on('submit', function(e) {
              var classe = $('#classe').val();
              var eleve = $('#eleve').val();
              var matiere = $('#matiere').val();
              
              if (!classe || !eleve || !matiere) {
                  e.preventDefault();
                  alert('Veuillez remplir tous les champs obligatoires');
                  return false;
              }
          });
          
      });
  </script>
