<?php

    require __DIR__ . '/../models/model.php';

    
    
    $id = intval($_GET['Ens']);
    $matiere = intval($_GET['Mat']);
    $classe = intval($_GET['classe']);

    delEnseignement($connexion, $id, $matiere, $classe);