<?php

    require __DIR__ . "/../models/model.php";
    require __DIR__ . "/../database/connexion.php";

    // recuperer les classes enseignées par un professeur
    $classes = classe($connexion);

    // recuperer toutes les matières
    $matieres = allMatiere($connexion);

    // inserer les notes dans la base de données
    $message = ajoutNote($connexion);
    

    include __DIR__ . "/../views/ajoutNote.php";


