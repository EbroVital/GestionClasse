<?php 

    session_start();

    require "database/connexion.php";

     //  recuperer toutes les classes
     $requete = $connexion->prepare("SELECT id, nom FROM classes");
     $requete->execute();
     $classes = $requete->fetchAll(PDO::FETCH_OBJ);


    // verification des champs

    if(isset($_POST["inscription"])){

        if(empty($_POST["nom"]) && empty($_POST["prenom"]) && empty($_POST["email"]) && empty($_POST["adresse"]) && empty($_POST["mdp"]) && empty($_POST["mdp2"]) ){

            $message = "Tous les champs sont réquis !";
        } 

        if( $_POST['mdp'] != $_POST['mdp2']){
            $message = "Les mots de passe sont différents !";
        } else {


            $nom = htmlspecialchars(strip_tags($_POST['nom']));
            $prenom = htmlspecialchars(strip_tags($_POST['prenom']));
            $email = htmlspecialchars(strip_tags($_POST['email']));
            $adresse = htmlspecialchars(strip_tags($_POST['adresse']));
            $telephone = htmlspecialchars(strip_tags($_POST['telephone']));
            $sexe = htmlspecialchars(strip_tags($_POST['sexe']));
            $mdp = htmlspecialchars(strip_tags($_POST['mdp']));
            $classe = intval($_POST['classe']);
            

            $password = password_hash($mdp , PASSWORD_BCRYPT);

            $stmt = $connexion->prepare("INSERT INTO eleves (nom, prenom, sexe,adresse, email, mdp, telephone, classe_id) VALUES (:nom, :prenom, :sexe, :adresse, :email, :mdp, :telephone, :classe)");

            $stmt->bindValue(":nom", $nom);
            $stmt->bindValue(":prenom", $prenom);
            $stmt->bindValue(":sexe", $sexe);
            $stmt->bindValue(":adresse", $adresse);
            $stmt->bindValue(":email", $email);
            $stmt->bindValue(":mdp", $password);
            $stmt->bindValue(":telephone", $telephone);
            $stmt->bindValue(":classe", $classe, PDO::PARAM_INT);

            $stmt->execute();

            if($stmt){
              $message = 'Inscription éffectuée, Cliquez <a href="index.php" class= "btn btn-primary"> ICI</a> pour vous connecter';
            } else {
              $message = "Une erreur est survenue, Réessayer !";
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
  <title>Inscription</title>
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-login">
  <!-- Register Content -->
  <div class="container-login">
    <div class="row justify-content-center">
      <div class="col-xl-10 col-lg-12 col-md-9">
        <div class="card shadow-sm my-5">
          <div class="card-body p-0">
            <div class="row">
              <div class="col-lg-12">
                <div class="login-form">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Inscription</h1>
                  </div>

                   <!-- Afficher les messages d'erreur  -->

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
                              <label>Nom :</label>
                              <input type="text" class="form-control" id="exampleInputFirstName" placeholder="Entrez votre nom" name="nom">
                          </div>
                        </div>

                        <div class="col">
                          <div class="form-group">
                              <label>Prénom :</label>
                              <input type="text" class="form-control" id="exampleInputLastName" placeholder="Entrez votre prénom" name="prenom">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col">
                          <div class="form-group">
                              <label>Sexe :</label>
                              <select class="form-control mb-3" name="sexe">
                                  <option>-- Sexe --</option>
                                  <option value="Homme">Masculin</option>
                                  <option value="Féminin">Feminin</option>
                              </select>
                          </div>
                        </div>

                        <div class="col">
                          <div class="form-group">
                              <label>Email :</label>
                              <input type="email" class="form-control" id="exampleInputEmail" aria-describedby="emailHelp"
                                  placeholder="Entrez votre Email" name="email">
                          </div>
                        </div>
                      </div>  
                      <div class="form-group">
                        <div class="row">

                          <div class="col">
                              <label for="">Classe :</label>
                              <select name="classe" id="" class="form-control mb-3">
                                <option value="">-- Sélectionner la classe --</option>
                                <?php foreach ($classes as $classe) : ?>
                                  <option value="<?= $classe->id ;?>">
                                    <?= $classe->nom ;?>
                                  </option>
                                <?php endforeach ;?>
                              </select>
                          </div>

                          <div class="col">
                            <div class="form-group">
                              <label>Adresse : </label>
                              <input type="text" class="form-control" id="exampleInputPassword" placeholder="Entrez votre adresse" name="adresse">
                            </div> 
                          </div>

                        </div>
                        
                      </div>  

                      <div class="form-group">
                        <label>Téléphone :</label>
                        <input type="number" class="form-control" id="exampleInputPassword" placeholder="Entrez votre téléphone" name="telephone">
                      </div> 

                      <div class="row">
                        <div class="col">
                          <div class="form-group">
                              <label>Mot de passe :</label>
                              <input type="password" class="form-control" id="exampleInputPassword" placeholder="Entrez votre Mot de passe" name="mdp">
                          </div> 
                        </div>

                        <div class="col">
                          <div class="form-group">
                              <label>Confirmez le mot de passe :</label>
                              <input type="password" class="form-control" id="exampleInputPasswordRepeat"
                                  placeholder="Repetez le mot de passe" name="mdp2">
                          </div>
                        </div>
                      </div>  
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary btn-block" value="S'inscrire" name="inscription">
                        </div>
                    </form>
                    
                    <hr>
                    <div class="text-center">
                        <a class="font-weight-bold small" href="index.php">Se connecter !</a>
                    </div>
                    <div class="text-center">
                        <a class="font-weight-bold small" href="recovery.php">Mot de passe oublié ?</a>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Register Content -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/ruang-admin.min.js"></script>
</body>

</html>