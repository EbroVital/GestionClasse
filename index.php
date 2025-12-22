<?php
 
  session_start();  
  require "database/connexion.php";

  if($_SERVER['REQUEST_METHOD'] == "POST"){

    if(empty($_POST['email']) && empty($_POST['mdp'])){
      $message = "Tous les champs sont réquis !";
    } else {

      $email = htmlspecialchars(strip_tags($_POST['email']));
      $mdp = htmlspecialchars(strip_tags($_POST['mdp']));

      $stmt = $connexion->prepare("SELECT * FROM eleves WHERE email = :email");
      $stmt->execute(array('email'=>$email));
      $eleve = $stmt->fetch();

      if(!$eleve){
        $message = "Cette adresse Email n'est pas reconnu !";
      } else {
        
          $verifMdp = password_verify($mdp, $eleve['mdp']);

          if($verifMdp){

            $_SESSION['nom'] = $eleve['nom'];
            $_SESSION['prenom'] = $eleve['prenom'];
            $_SESSION['email'] = $eleve['email'];
            $_SESSION['sexe'] = $eleve['sexe'];
            $_SESSION['id_eleve'] = $eleve['id'];

            header("Location: controllers/dashboardEleveController.php");

          } else {
              $message = "Le mot de passe n'est pas correct ! ";
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
  <title>Login</title>
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-login">
  <!-- Login Content -->
  <div class="container-login">
    <div class="row justify-content-center">
      <div class="col-xl-6 col-lg-12 col-md-9">
        <div class="card shadow-sm my-5">
          <div class="card-body p-0">
            <div class="row">
              <div class="col-lg-12">
                <div class="login-form">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Se Connecter en tant qu'élève</h1>
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
                  <form class="user" method="post">
                    <div class="form-group">
                      <input type="email" class="form-control" id="exampleInputEmail" aria-describedby="emailHelp"
                        placeholder="Entrez votre Email " name="email">
                    </div>
                    <div class="form-group">
                      <input type="password" class="form-control" id="exampleInputPassword" placeholder="Mot de passe" name="mdp">
                    </div>
                    
                    <div class="form-group">
                      <input type="submit" class="btn btn-primary btn-block" value="Se connecter" name="connexion">
                    </div>
                    <hr>
                    
                  <div class="text-center">
                    <a class="font-weight-bold small" href="register.php">Créer un compte !</a>
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
  <!-- Login Content -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/ruang-admin.min.js"></script>
</body>

</html>