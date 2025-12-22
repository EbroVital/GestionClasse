<?php

    require __DIR__ . '/../database/connexion.php';
    require __DIR__ . '/../models/model.php';

    if(!isset($_SESSION['nom']) && ($_SESSION['prenom'])) {
        header("Location:login.php");
    }

    $stmt = listEnseignement($connexion);

    // var_dump($stmt);

    include __DIR__ . '/../views/listeEnseignement.php';