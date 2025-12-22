<?php


    require __DIR__ . "/../database/connexion.php";
    require __DIR__ . "/../models/model.php";

    if(!isset($_SESSION['nom']) && ($_SESSION['prenom'])) {
        header("Location:login.php");
        exit();
    }

    // liste des classes enseignées par le prof
    $requete = ClassProf($connexion);
    $classes_array = explode(', ', $requete['liste_classes']);

    // recuperer la specialité du prof
    $specialite = recupSpecialiteProf($connexion);

    // recuperer toutes les notes faites par un prof
    $result = noteCount($connexion);

    // Utilisation avec affichage HTML
    $dashboard = dashboardCompletProf($connexion);

    if (!$dashboard) {
        die("Professeur non trouvé");
    }

    $infos = $dashboard['infos_personnelles'];
    $stats = $dashboard['statistiques'];
    

    include __DIR__ . "/../views/dashboard.php";

