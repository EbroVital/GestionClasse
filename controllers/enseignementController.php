<?php 

    require __DIR__ . "/../models/model.php" ;
    require __DIR__ . "/../database/connexion.php" ;
    

    if(!isset($_SESSION['nom']) && ($_SESSION['prenom'])) {
        header("Location:login.php");
    }

    // fonction pour recuperer toutes les classes
    $classes = allClasse($connexion);

    // fonction pour recuperer toutes les matieres
    $matieres = allMatiere($connexion);

    // fonction pour inserer un enregistrement
    $message = insertEnseignement($connexion);

    include __DIR__ . "/../views/ajoutEnseignement.php";
