<?php

  require "database/connexion.php";

  if($_SERVER['REQUEST_METHOD'] == "POST"){

    if(empty($_POST['email']) && empty($_POST['mdp']) && empty($_POST['mdp2'])){
        $message = "Tous les champs sont réquis !";
    }
    if($_POST['mdp'] != $_POST['mdp2']){
      $message = "Les mots de passe ne sont pas identiques !";
    } else {

      $email = htmlspecialchars(strip_tags($_POST['email']));
      $mdp = htmlspecialchars(strip_tags($_POST['mdp']));

      $stmt = $connexion->prepare("SELECT * FROM eleves WHERE email = :email");
      $stmt->bindValue(":email", $email);
      $stmt->execute();
      $req = $stmt->fetch();

      if(!$req){
        $message = "Désolé cette adresse n'est pas reconnu !";
      } else {

        $password = password_hash($mdp, PASSWORD_BCRYPT);

        $sql = $connexion->prepare("UPDATE eleves SET mdp = :mdp WHERE email = :email");

        $sql->bindValue(":mdp", $password);
        $sql->bindValue(":email", $email);

        $sql->execute();

        if($sql){
          $message = 'Modification éffectuée, Cliquez <a href="index.php" class= "btn btn-primary"> ICI</a> pour vous Connectez avec votre nouveau mot de passe ';
        } else {
          $message = "Une erreur est survenue, Réessayer !";
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
  <title>Récupération</title>
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
                    <h1 class="h4 text-gray-900 mb-4">Vous avez oublié votre mot de passe</h1>
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
                      <input type="password" class="form-control" id="exampleInputPassword" placeholder="Confirmez le mot de passe" name="mdp2">
                    </div>
                    
                    <div class="form-group">
                      <input type="submit" class="btn btn-primary btn-block" value="Modifier le mot de passe" name="connexion">
                    </div>
                    <hr>
                    
                  <div class="text-center">
                    <a class="font-weight-bold small" href="register.php">Créer un compte !</a>
                  </div>
                  <div class="text-center">
                    <a class="font-weight-bold small" href="register.php">S'inscrire !</a>
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