<?php

    require __DIR__ . "/../database/connexion.php";
    require __DIR__ . "/../models/model.php";

    // recuperer la moyenne du 1er trimestre
    $moyennes = moyenne($connexion, 1);

    // recuperer la moyenne du 2ième trimestre
    $moys = moyenne($connexion, 2);

    // recuperer la moyenne du 3ème trimestre
    $moyenns = moyenne($connexion, 3);

    



    include __DIR__ . "/../views/moyenne.php";
