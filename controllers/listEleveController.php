<?php

    require __DIR__ . "/../models/model.php" ;
    require __DIR__ . "/../database/connexion.php" ;


    if(!isset($_SESSION['nom']) && ($_SESSION['prenom'])) {
        header("Location:login.php");
    }

    // recuperer les eleves appartenant à une classe
    $infos = classEleve($connexion);

    // recuperer les classes enseignées par un prof
    $classes = classe($connexion);
    

    include __DIR__ . "/../views/listeEleve.php";
