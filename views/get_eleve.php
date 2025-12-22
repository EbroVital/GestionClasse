<?php

    ini_set('display_errors', 1);
    error_reporting(E_ALL);


    header('Content-Type: application/json');

    require __DIR__ . "/../database/connexion.php";

    // Récupérer l'ID de la classe
    $classe_id = isset($_GET['classe_id']) ? intval($_GET['classe_id']) : 0;
    
    if ($classe_id > 0) {
        // Requête pour récupérer les élèves de la classe
        $req = $connexion->prepare("SELECT id, nom, prenom FROM eleves WHERE classe_id = :classe_id AND actif = 1 ORDER BY nom, prenom");

        // $req->bindValue(":classe_id" , $classe_id, PDO::PARAM_INT);
        $req->execute(['classe_id' => $classe_id]);

        $eleves = $req->fetchAll(PDO::FETCH_ASSOC);
        
        // Renvoyer la réponse JSON
        echo json_encode([
            'success' => true,
            'eleves' => $eleves,
            'count' => count($eleves)
        ]);
        

    } else {
        echo json_encode([
            'success' => false,
            'message' => 'ID de classe invalide'
        ]);
    }
    

?>