<?php

    require __DIR__ . '/../models/model.php';
    require __DIR__ . '/../database/connexion.php';


    // Récupérer toutes les données à mettre sur le tableau de bord de l'eleve
    $dashboard = dashboardEleve($connexion);

    if (!$dashboard) {
        die("Élève non trouvé");
    }

    $eleve_id = $_SESSION['id_eleve'];
    $infos = $dashboard['infos_personnelles'];
    $stats = $dashboard['statistiques'];


    if(!isset($_SESSION['email']) && !$_SESSION['id_eleve']) {
        header("Location:../index.php");
    }
    
    include __DIR__ . '/../views/dashboardEleve.php';


    

