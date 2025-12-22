<?php

    require __DIR__ . "/../database/connexion.php";
    require __DIR__ . "/../models/model.php";

    // recuperer la classe de l'eleve
    $classe = recupClasse($connexion) ;
   
    // recuperer la liste des eleves d'une classe
    $eleves = listEleve($connexion);
    // var_dump($eleves);
    
    include __DIR__ . "/../views/listClasse.php";




