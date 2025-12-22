<?php 

    require __DIR__ . "/../database/connexion.php";
    require __DIR__ . "/../models/model.php";

    if(!isset($_SESSION['nom']) && ($_SESSION['prenom'])) {
        header("Location:login.php");
    }

    // recuperer les classes enseignées par le prof
    $classes = classe($connexion);

    // recuperer la liste des eleves par classe ainsi que leurs notes
    $infos = noteEleve($connexion);
    // var_dump($infos);


    include __DIR__ . "/../views/noteEleve.php";

