<?php

    require __DIR__ . "/../database/connexion.php";
    require __DIR__ . "/../models/model.php";

    // recuperer les notes du 1er trimestre
    $notes = noteMat($connexion, 1);
    
    // recuperer les notes du 2ième trimestre
    $noteMat = noteMat($connexion, 2);

    // recuperer les notes du 3ième trimestre
    $notes3 = noteMat($connexion, 3);


    include __DIR__ . "/../views/note.php";
