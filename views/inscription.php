<?php 

    // session_start();
    require __DIR__ . "/../database/connexion.php";

    // verification des champs

    if(isset($_POST['inscription'])){

        if( $_POST['mdp'] != $_POST['mdp2']){
          $message = "Les mots de passe sont différents !";
        }

        if(empty($_POST["nom"]) && empty($_POST["prenom"]) && empty($_POST["email"]) && empty($_POST["mdp"]) && empty($_POST["mdp2"]) && empty($_POST['telephone']) && empty($_POST['specialite'])){

            $message = "Tous les champs sont réquis !";
        } 
          else {

            $nom = htmlspecialchars(strip_tags($_POST['nom']));
            $prenom = htmlspecialchars(strip_tags($_POST['prenom']));
            $email = htmlspecialchars(strip_tags($_POST['email']));
            $mdp = htmlspecialchars(strip_tags($_POST['mdp']));
            $tel = htmlspecialchars(strip_tags($_POST['telephone']));
            $specialite = htmlspecialchars(strip_tags($_POST['specialite']));

            $stmt = $connexion->prepare("SELECT * FROM professeurs WHERE email = :email");
            $stmt->bindValue(":email", $email);
            $stmt->execute();
            $result = $stmt->fetch();

            if($result){
                $message = "Vous ne pouvez pas utilisé cette adresse Email, Veuillez choisir une autre adresse !";
            } else {

                $password = password_hash($mdp , PASSWORD_BCRYPT);

                $requete = $connexion->prepare("INSERT INTO professeurs (nom,prenom,email,mdp,telephone,specialite) VALUES (:nom,:prenom,:email,:mdp,:telephone,:specialite)");

                $requete->bindValue(":nom", $nom);
                $requete->bindValue(":prenom", $prenom);
                $requete->bindValue(":email", $email);
                $requete->bindValue(":mdp", $password);
                $requete->bindValue(":telephone", $tel);
                $requete->bindValue(":specialite", $specialite);

                $requete->execute();

                if($requete){

                    $message = 'Inscription éffectué, Cliquez <a href="login.php" class= "btn btn-primary"> ICI</a> pour vous Connectez !!';
                    
                } else {
                    $message = "Une erreur est survenue lors de l'enregistrement, Veuillez réessayer ! ";
                }

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
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="../css/ruang-admin.min.css" rel="stylesheet">

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
                    <h1 class="h4 text-gray-900 mb-4">S'inscrire en tant que Professeur</h1>
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
                            <label>Confirmez le mot de passe :</label>
                            <input type="password" class="form-control" id="exampleInputPasswordRepeat"
                                placeholder="Repetez le mot de passe" name="mdp2">
                          </div>
                          
                        </div>

                        <div class="col">
                          <div class="form-group">
                            <label>Mot de passe :</label>
                            <input type="password" class="form-control" id="exampleInputPassword" placeholder="Entrez votre Mot de passe" name="mdp">
                          </div>
                        </div>

                      </div>  
                      
                        <div class="form-group">
                            <label>Email :</label>
                            <input type="email" class="form-control" id="exampleInputEmail" aria-describedby="emailHelp"
                                placeholder="Entrez votre Email" name="email">
                        </div>

                        <div class="row">
                          <div class="col">
                              <div class="form-group">
                                <Label>Téléphone :</Label>
                                <input type="tel" class="form-control" 
                                placeholder="Entrez votre numero de telephone" name="telephone">
                              </div>
                          </div>
                          <div class="col">
                            <div class="form-group">
                              <label for="">Spécialité :</label>
                              <input type="text" class="form-control"
                                placeholder="Entrez votre spécialité" name="specialite">
                            </div>
                          </div>
                        </div>

                        <div class="form-group">
                            <input type="submit" class="btn btn-primary btn-block" value="S'inscrire" name="inscription">
                        </div>
                    </form>
                    
                    <hr>
                    <div class="text-center">
                        <a class="font-weight-bold small" href="login.php">Se connecter !</a>
                    </div>
                    <div class="text-center">
                        <a class="font-weight-bold small" href="mdp.php">Mot de passe oublié ?</a>
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