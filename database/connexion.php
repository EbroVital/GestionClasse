<?php

    $db = "mysql:host=localhost;dbname=dbclasse";
    $user = "root";
    $password = "";

    try {

        $connexion = new PDO($db, $user, $password);

        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    } catch (\Throwable $th) {
        throw $th->getMessage();
    }






?>