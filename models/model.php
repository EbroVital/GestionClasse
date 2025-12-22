<?php 

    session_start();

    require __DIR__ . '/../database/connexion.php';


    // ============= les fonctions de l'eleve ===============

    // recuperer la classe de l'etudiant
    function recupClasse($connexion) {
        
        $id = $_SESSION['id_eleve'];

        $sql = $connexion->prepare("SELECT c.id, c.nom FROM eleves e, classes c WHERE e.classe_id = c.id AND e.id = :id");
        $sql->execute(['id' => $id]);
        $classe = $sql->fetch(PDO::FETCH_ASSOC);

        return $classe;
    }

    // toutes les info a mettre sur le dashboard d'un eleve
    function dashboardEleve($connexion) {

        $dashboard = [];
        $id = $_SESSION['id_eleve'];

        // info personnelles

        $stmt  = $connexion->prepare("SELECT e.id, e.nom, e.prenom, e.sexe, e.email, e.telephone, e.date_inscription, c.id as classe_id, c.nom as classe_nom, c.niveau FROM eleves e JOIN classes c ON e.classe_id = c.id
        WHERE e.id = :eleve_id");

        $stmt->execute(['eleve_id' => $id]);
        $dashboard['infos_personnelles'] = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$dashboard['infos_personnelles']) {
            return null; 
        }

        // Statistiques Générales

        $stmt = $connexion->prepare("SELECT COUNT(DISTINCT n.matiere_id) as nb_matieres, COUNT(n.id) as nb_notes_total, ROUND(AVG(n.note), 2) as moyenne_generale_brute, MIN(n.note) as note_min, MAX(n.note) as note_max,
        COUNT(DISTINCT n.professeur_id) as nb_professeurs FROM notes n WHERE n.eleve_id = :eleve_id");
        
        $stmt->execute(['eleve_id' => $id]);
        $dashboard['statistiques'] = $stmt->fetch(PDO::FETCH_ASSOC);

        // Moyenne par trimestre

        $stmt = $connexion->prepare("SELECT n.trimestre, COUNT(n.id) as nb_notes,
            COUNT(DISTINCT n.matiere_id) as nb_matieres,
            ROUND(SUM(n.note * n.coefficient * m.coefficient) / SUM(n.coefficient * m.coefficient), 2) as moyenne_ponderee FROM notes n
            JOIN matieres m ON n.matiere_id = m.id
            WHERE n.eleve_id = :eleve_id
            GROUP BY n.trimestre
            ORDER BY n.trimestre ");
    
        $stmt->execute(['eleve_id' => $id]);
        $dashboard['moyennes_trimestre'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // NOTES RÉCENTES (5 dernières)

        $stmt = $connexion->prepare("SELECT n.note, n.type_evaluation, n.date_evaluation, n.trimestre, n.commentaire, m.nom as matiere, p.nom as prof_nom, p.prenom as prof_prenom FROM notes n
        JOIN matieres m ON n.matiere_id = m.id
        JOIN professeurs p ON n.professeur_id = p.id
        WHERE n.eleve_id = :eleve_id
        ORDER BY n.date_evaluation DESC
        LIMIT 5");
    
        $stmt->execute(['eleve_id' => $id]);
        $dashboard['notes_recentes'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // MOYENNES PAR MATIÈRE (Trimestre en cours)

        $trimestre_actuel = date('n') <= 4 ? 2 : (date('n') <= 8 ? 3 : 1);
    
        $stmt = $connexion->prepare("SELECT m.nom as matiere, m.coefficient as coef_matiere, COUNT(n.id) as nb_notes, ROUND(AVG(n.note), 2) as moyenne_simple, ROUND(SUM(n.note * n.coefficient) / SUM(n.coefficient), 2) as moyenne_ponderee, MIN(n.note) as note_min,MAX(n.note) as note_max
        FROM notes n
        JOIN matieres m ON n.matiere_id = m.id
        WHERE n.eleve_id = :eleve_id
        AND n.trimestre = :trimestre
        GROUP BY m.id, m.nom, m.coefficient
        ORDER BY m.nom");
    
        $stmt->execute([
            'eleve_id' => $id,
            'trimestre' => $trimestre_actuel
        ]);
        $dashboard['moyennes_matieres'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // CLASSEMENT DANS LA CLASSE

        $stmt = $connexion->prepare("SELECT e.id, e.nom, e.prenom, ROUND(AVG(n.note), 2) as moyenne,
            RANK() OVER (ORDER BY AVG(n.note) DESC) as classement
        FROM eleves e
        LEFT JOIN notes n ON e.id = n.eleve_id AND n.trimestre = :trimestre
        WHERE e.classe_id = (SELECT classe_id FROM eleves WHERE id = :eleve_id)
        AND e.actif = 1
        GROUP BY e.id, e.nom, e.prenom
        ORDER BY moyenne DESC ");
    
        $stmt->execute([
            'eleve_id' => $id,
            'trimestre' => $trimestre_actuel
        ]);
    
        $classement_classe = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $dashboard['classement'] = null;
        $dashboard['nb_eleves_classe'] = count($classement_classe);
    
        foreach ($classement_classe as $eleve) {
            if ($eleve['id'] == $id) {
                $dashboard['classement'] = $eleve;
                break;
            }
        }

        // PROGRESSION (Comparaison trimestres)

        $stmt = $connexion->prepare("SELECT trimestre, ROUND(AVG(note), 2) as moyenne FROM notes
        WHERE eleve_id = :eleve_id
        GROUP BY trimestre
        ORDER BY trimestre");
    
        $stmt->execute(['eleve_id' => $id]);
        $progression = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        $dashboard['progression'] = [];

        if (count($progression) >= 2) {
            $derniere = end($progression);
            $avant_derniere = prev($progression);
            
            $difference = $derniere['moyenne'] - $avant_derniere['moyenne'];
            $dashboard['progression'] = [
                'difference' => round($difference, 2),
                'pourcentage' => round(($difference / $avant_derniere['moyenne']) * 100, 2),
                'sens' => $difference > 0 ? 'hausse' : ($difference < 0 ? 'baisse' : 'stable')
            ];
        }

        // MATIÈRES À AMÉLIORER (Moyenne < 10)

        $stmt = $connexion->prepare("SELECT m.nom as matiere, ROUND(AVG(n.note), 2) as moyenne
        FROM notes n
        JOIN matieres m ON n.matiere_id = m.id
        WHERE n.eleve_id = :eleve_id
        AND n.trimestre = :trimestre
        GROUP BY m.id, m.nom
        HAVING moyenne < 10
        ORDER BY moyenne ASC
        LIMIT 3");
    
        $stmt->execute([
            'eleve_id' => $id,
            'trimestre' => $trimestre_actuel
        ]);
        $dashboard['matieres_a_ameliorer'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // POINTS FORTS (Meilleures moyennes)

        $stmt = $connexion->prepare("SELECT m.nom as matiere, ROUND(AVG(n.note), 2) as moyenne
        FROM notes n
        JOIN matieres m ON n.matiere_id = m.id
        WHERE n.eleve_id = :eleve_id
        AND n.trimestre = :trimestre
        GROUP BY m.id, m.nom
        ORDER BY moyenne DESC
        LIMIT 3");
    
        $stmt->execute([
            'eleve_id' => $id,
            'trimestre' => $trimestre_actuel
        ]);
        $dashboard['points_forts'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        return $dashboard;

    }


    // liste des eleves dans une classe
    function listEleve($connexion){

        $classe = recupClasse($connexion) ;
        $id = $classe['id'];    
        
        $req = $connexion->prepare("SELECT e.* FROM eleves e , classes c WHERE e.classe_id = c.id AND e.classe_id = :id");

        $req->execute(['id' => $id]);
        $eleves = $req->fetchAll(PDO::FETCH_OBJ);

        return $eleves;

    }

    // Calculer la moyenne d'un élève par trimestre 
    function moyenne($connexion, $trimestre) {

        $id = $_SESSION['id_eleve'];

        $requete = $connexion->prepare("SELECT 
                e.nom, 
                e.prenom,
                m.nom as matiere,
                m.coefficient as coef_matiere,
                ROUND(SUM(n.note * n.coefficient) / SUM(n.coefficient), 2) as moyenne_matiere,
                COUNT(n.id) as nb_notes
            FROM notes n
            JOIN eleves e ON n.eleve_id = e.id
            JOIN matieres m ON n.matiere_id = m.id
            WHERE e.id = :id AND n.trimestre = :trimestre
            GROUP BY e.id, e.nom, e.prenom, m.id, m.nom, m.coefficient
            ORDER BY m.nom");

        $requete->execute([
            "id" => $id,
            "trimestre" => $trimestre
        ]);
        $moyenne = $requete->fetchAll(PDO::FETCH_ASSOC);
        
        return $moyenne;

    }


    // afficher toutes les notes d'un eleve de chaque matiere par trimestre
    function noteMat($connexion, $trimestre) {

        $id = $_SESSION['id_eleve'];

        $req = $connexion->prepare("SELECT 
            e.nom, e.prenom, 
            m.nom as matiere,
            n.note as note,
            n.type_evaluation as evaluation,
            m.coefficient as coefficient
        FROM notes n
        JOIN eleves e ON n.eleve_id = e.id
        JOIN matieres m ON n.matiere_id = m.id
        WHERE e.id = :id AND n.trimestre = :trimestre
        GROUP BY e.id, m.id");

        $req->execute([
            'id' => $id,
            'trimestre' => $trimestre
        ]);

        $notes = $req->fetchAll(PDO::FETCH_OBJ);

        return $notes;

    }


    // bulletin du 1er trimestre
    function bulletinEleve($connexion, $trimestre) {
    
        $id = $_SESSION['id_eleve'];

        // 1. Récupérer les moyennes par matière
        $stmt = $connexion->prepare("SELECT 
            m.nom as matiere,
            m.coefficient as coef_matiere,
            ROUND(SUM(n.note * n.coefficient) / SUM(n.coefficient), 2) as moyenne,
            COUNT(n.id) as nb_notes,
            MIN(n.note) as note_min,
            MAX(n.note) as note_max
        FROM notes n
        JOIN matieres m ON n.matiere_id = m.id
        WHERE n.eleve_id = :eleve_id 
        AND n.trimestre = :trimestre
        GROUP BY m.id, m.nom, m.coefficient
        ORDER BY m.nom");
    
        $stmt->execute([
            'eleve_id' => $id,
            'trimestre' => $trimestre
        ]);
    
        $matieres = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // 2. Calculer la moyenne générale pondérée
        $somme_notes_ponderees = 0;
        $somme_coefficients = 0;
        
        foreach($matieres as $matiere) {
            $somme_notes_ponderees += $matiere['moyenne'] * $matiere['coef_matiere'];
            $somme_coefficients += $matiere['coef_matiere'];
        }
    
        $moyenne_generale = $somme_coefficients > 0 
            ? round($somme_notes_ponderees / $somme_coefficients, 2) 
            : 0;
        
        return [
            'matieres' => $matieres,
            'moyenne_generale' => $moyenne_generale,
        ];
    }






    //  ==============  les fonctions du professeur  ===================

    // recuperer toutes les classes enseignées par un professeur
    function ClassProf($connexion) {

        $id = $_SESSION['idProf'];

        $stmt = $connexion->prepare("SELECT 
            GROUP_CONCAT(DISTINCT c.nom SEPARATOR ', ') as liste_classes,
            COUNT(DISTINCT c.id) as nombre_classes
            FROM enseignements en 
            JOIN classes c ON en.classe_id = c.id 
            WHERE en.professeur_id = $id");

        $stmt->execute();
        $requete = $stmt->fetch(PDO::FETCH_ASSOC);

        return $requete;

    }

    // recuperer toutes les classes
    function allClasse($connexion){
        $sql = $connexion->prepare("SELECT id, nom FROM classes");
        $sql->execute();
        $classes = $sql->fetchAll(PDO::FETCH_OBJ);

        return $classes;
    }
    
    // recuperer toutes les matières
    function allMatiere($connexion){
        $stmt = $connexion->prepare("SELECT * FROM matieres");
        $stmt->execute();
        $matieres = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $matieres;
    }

    // recuperer la specialité d'un professeur
    function recupSpecialiteProf($connexion){

        $idprof =  $_SESSION['idProf'];

        $stmt = $connexion->prepare("SELECT specialite FROM professeurs WHERE id = $idprof");
        $stmt->execute();
        $specialite = $stmt->fetch();

        return $specialite;
    }

    // inserer un enseignement 
    function insertEnseignement($connexion) {

        if(isset($_POST['valider'])){

            if(empty($_POST['classe']) && empty($_POST['matiere'])){
                $message = "Ces champs sont réquis !";
            } else {

                $classe = htmlspecialchars($_POST['classe']);
                $professeur = htmlspecialchars($_POST['idProf']);
                $matiere = htmlspecialchars($_POST['matiere']);
                $annee = htmlspecialchars($_POST['annee']);

                $requete = $connexion->prepare("INSERT INTO enseignements (professeur_id, matiere_id, classe_id, annee_scolaire) VALUES (:professeur_id, :matiere_id, :classe_id, :annee_scolaire)");
        
                $requete->bindValue(":professeur_id", $professeur, PDO::PARAM_INT);
                $requete->bindValue(":matiere_id", $matiere, PDO::PARAM_INT);
                $requete->bindValue(":classe_id", $classe, PDO::PARAM_INT);
                $requete->bindValue(":annee_scolaire", $annee);
        
                $requete->execute();

                if($requete){
                    $message = "Enregistrement effectué , Cliquez <a href = '../controllers/listEnseignementController.php' class='btn btn-primary'>ICI</a> pour voir !";
                } else {
                    $message = "Une erreur est survenue, réessayer ! ";
                }
        
            }

            return $message; 

        }
       

    }

    // liste des enseignements d'un professeur
    function listEnseignement($connexion) {

        $id = $_SESSION['idProf'];
        $requete = $connexion->prepare("SELECT p.id, m.nom as matiere, m.id as matiere_id, c.nom as classe, c.id as classe_id
        FROM enseignements en
        JOIN professeurs p ON en.professeur_id = p.id
        JOIN matieres m ON en.matiere_id = m.id
        JOIN classes c ON en.classe_id = c.id
        WHERE p.id = $id");

        $requete->execute();
        $stmt = $requete->fetchAll(PDO::FETCH_OBJ);
        return $stmt;
    }

    // supprimer un enseignement
    function delEnseignement($connexion, $id, $matiere, $classe) {

        if(!is_numeric($id) && !is_numeric($matiere) && !is_numeric($classe)) {

            header("Location: ../controllers/listEnseignementController.php");
            exit();

        } else {

            $req = $connexion->prepare("DELETE FROM enseignements 
            WHERE professeur_id = :id 
            AND matiere_id = :matiere 
            AND classe_id = :classe");

            $req->execute([
                'id' => $id,
                'matiere' => $matiere,
                'classe' => $classe
            ]);

            if ($req->rowCount() > 0) {

                $_SESSION['msg'] = "Suppression éffectuée !" ;

                header("Location: ../controllers/listEnseignementController.php");
                exit();
               
            } else {
                $_SESSION['msg'] = "Une erreur est survenue lors de la suppression";
            }
        }

    }

    // liste des eleves par classe
    function classEleve($connexion) {

        if(isset($_POST['valider'])) {

            $classe = intval($_POST['classe']);
            
            $stmt = $connexion->prepare("SELECT id,nom,prenom,sexe FROM eleves WHERE eleves.classe_id = $classe");
            $stmt->execute();
            $infos = $stmt->fetchAll(PDO::FETCH_OBJ);


            if($infos){
                $_SESSION['message'] = "Veuillez cliquez sur l'onglet <b> Liste des élèves </b> suivant pour voir la liste";
            } 

            return $infos;
        }
 
    }

    // recuperer la liste des classes d'un prof (mise à jour : adaptation)

    function classe($connexion) {

        $idProf = $_SESSION['idProf'];
        $requete = $connexion->prepare("SELECT c.id, c.nom
            FROM enseignements en 
            JOIN classes c ON en.classe_id = c.id 
            WHERE en.professeur_id = $idProf");

        $requete->execute();

        $classes = $requete->fetchAll(PDO::FETCH_OBJ);

        return $classes;

    }

    // ajout des notes 
    function ajoutNote($connexion) {

        if(isset($_POST['valider'])){

            $idprof = $_SESSION['idProf'];
            $eleveId = intval($_POST['eleve']);
            $matiereId = intval($_POST['matiere']);

            if(!empty($_POST['note']) && !empty($_POST['eval']) && !empty($_POST['trimestre']) && !empty($_POST['dateEval']) && !empty($_POST['commentaire'])) {

                $note = htmlspecialchars($_POST['note']);
                $eval = htmlspecialchars($_POST['eval']);
                $trimestre = htmlspecialchars($_POST['trimestre']);
                $date = htmlspecialchars($_POST['dateEval']);
                $commentaire = htmlspecialchars($_POST['commentaire']);
                $coefficient = htmlspecialchars($_POST['coefficient']);

                $requete = $connexion->prepare("INSERT INTO notes (eleve_id, matiere_id, professeur_id, note, type_evaluation, coefficient, trimestre, date_evaluation, commentaire) VALUES (:eleve_id, :matiere_id, :professeur_id, :note, :type_evaluation, :coefficient, :trimestre, :date_evaluation, :commentaire) ");

                $requete->bindValue(":eleve_id", $eleveId, PDO::PARAM_INT);
                $requete->bindValue(":matiere_id", $matiereId, PDO::PARAM_INT);
                $requete->bindValue(":professeur_id", $idprof, PDO::PARAM_INT);
                $requete->bindValue(":note", $note);
                $requete->bindValue(":type_evaluation", $eval);
                $requete->bindValue(":coefficient", $coefficient);
                $requete->bindValue(":trimestre", $trimestre);
                $requete->bindValue(":date_evaluation", $date);
                $requete->bindValue(":commentaire", $commentaire);

                $requete->execute();

                if($requete){
                    $message = "Enregistrement effectué , Cliquez <a href = '../controllers/listNoteController.php' class='btn btn-primary'>ICI</a> pour voir !";
                } else {
                    $message = "Une erreur est survenue, réessayer ! ";
                }

            } else {
                $message = "Veuillez remplir tous les champs !";
            }

            return $message;

        }

    }

    // recuperer les eleves d'une classe ainsi que leurs notes respectifs
    function noteEleve($connexion) {

        if(isset($_POST['valider'])) {

            $classe = intval($_POST['classe']);
            
            $stmt = $connexion->prepare("SELECT 
                e.nom,
                e.prenom, 
                n.note, 
                n.type_evaluation, 
                n.date_evaluation, 
                n.trimestre 
            FROM eleves e
            INNER JOIN notes n ON e.id = n.eleve_id
            WHERE e.classe_id = :classe
            ORDER BY e.nom, e.prenom, n.date_evaluation");


            $stmt->execute(['classe' => $classe]);
            $infos = $stmt->fetchAll(PDO::FETCH_OBJ);


            if($infos){

                $_SESSION['message'] = "Veuillez cliquez sur l'onglet <b> Liste des élèves </b> suivant pour voir la liste";
                $_SESSION['classe_selectionnee'] = $classe;
            } 

            return $infos;
        }

    }

    // compter toutes les notes faites par un prof
    function noteCount($connexion) {

        $id = $_SESSION['idProf'];

        $stmt = $connexion->prepare("SELECT COUNT(note) as notes FROM notes WHERE professeur_id = :id");
    
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ;

    }

    // compter tous les eleves par classe dont le prof enseigne
    function dashboardCompletProf($connexion) {

        $id = $_SESSION['idProf'];
        $dashboard = [];

        // INFORMATIONS PERSONNELLES

        $stmt = $connexion->prepare("SELECT 
            p.id,
            p.nom,
            p.prenom,
            p.email,
            p.telephone,
            p.specialite,
            p.date_embauche FROM professeurs p WHERE p.id = :prof_id GROUP BY p.id ");
    
        $stmt->execute(['prof_id' => $id]);
        $dashboard['infos_personnelles'] = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$dashboard['infos_personnelles']) {
            return null; // Professeur non trouvé
        }

        // STATISTIQUES GÉNÉRALES

        $stmt = $connexion->prepare("SELECT 
            COUNT(DISTINCT en.classe_id) as nb_classes,
            COUNT(DISTINCT en.matiere_id) as nb_matieres,
            COUNT(DISTINCT e.id) as nb_eleves_total,
            COUNT(n.id) as nb_notes_saisies,
            ROUND(AVG(n.note), 2) as moyenne_generale
        FROM enseignements en
        LEFT JOIN eleves e ON e.classe_id = en.classe_id AND e.actif = 1
        LEFT JOIN notes n ON n.professeur_id = en.professeur_id
        WHERE en.professeur_id = :prof_id");
    
        $stmt->execute(['prof_id' => $id]);
        $dashboard['statistiques'] = $stmt->fetch(PDO::FETCH_ASSOC);

        // CLASSES ENSEIGNÉES AVEC DÉTAILS

        $stmt = $connexion->prepare("SELECT 
            c.id,
            c.nom as classe_nom,
            c.niveau,
            COUNT(DISTINCT e.id) as nb_eleves,
            COUNT(DISTINCT en.matiere_id) as nb_matieres_enseignees,
            GROUP_CONCAT(DISTINCT m.nom SEPARATOR ', ') as matieres
        FROM enseignements en
        JOIN classes c ON en.classe_id = c.id
        JOIN matieres m ON en.matiere_id = m.id
        LEFT JOIN eleves e ON e.classe_id = c.id AND e.actif = 1
        WHERE en.professeur_id = :prof_id
        GROUP BY c.id, c.nom, c.niveau
        ORDER BY c.nom ");
    
        $stmt->execute(['prof_id' => $id]);
        $dashboard['classes'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // MATIÈRES ENSEIGNÉES AVEC STATISTIQUES

        $stmt = $connexion->prepare("SELECT 
            m.id,
            m.nom as matiere,
            m.coefficient,
            COUNT(DISTINCT en.classe_id) as nb_classes,
            COUNT(n.id) as nb_notes,
            ROUND(AVG(n.note), 2) as moyenne,
            COUNT(DISTINCT n.eleve_id) as nb_eleves_notes
        FROM enseignements en
        JOIN matieres m ON en.matiere_id = m.id
        LEFT JOIN notes n ON n.matiere_id = m.id AND n.professeur_id = :prof_id
        WHERE en.professeur_id = :prof_id
        GROUP BY m.id, m.nom, m.coefficient
        ORDER BY m.nom ");
    
        $stmt->execute(['prof_id' => $id]);
        $dashboard['matieres'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // NOTES RÉCENTES SAISIES

        $stmt = $connexion->prepare("SELECT 
            n.id,
            n.note,
            n.type_evaluation,
            n.date_evaluation,
            n.trimestre,
            e.nom as eleve_nom,
            e.prenom as eleve_prenom,
            c.nom as classe_nom,
            m.nom as matiere
        FROM notes n
        JOIN eleves e ON n.eleve_id = e.id
        JOIN classes c ON e.classe_id = c.id
        JOIN matieres m ON n.matiere_id = m.id
        WHERE n.professeur_id = :prof_id
        ORDER BY n.created_at DESC
        LIMIT 10");
    
        $stmt->execute(['prof_id' => $id]);
        $dashboard['notes_recentes'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // STATISTIQUES PAR TRIMESTRE

        $stmt = $connexion->prepare("SELECT 
            n.trimestre,
            COUNT(n.id) as nb_notes,
            COUNT(DISTINCT n.eleve_id) as nb_eleves,
            ROUND(AVG(n.note), 2) as moyenne,
            MIN(n.note) as note_min,
            MAX(n.note) as note_max
        FROM notes n
        WHERE n.professeur_id = :prof_id
        GROUP BY n.trimestre
        ORDER BY n.trimestre ");

        $stmt->execute(['prof_id' => $id]);
        $dashboard['stats_trimestre'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // ACTIVITÉ RÉCENTE (notes par jour)

        $stmt = $connexion->prepare("SELECT 
            DATE(n.created_at) as date,
            COUNT(n.id) as nb_notes
        FROM notes n
        WHERE n.professeur_id = :prof_id
        AND n.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        GROUP BY DATE(n.created_at)
        ORDER BY date DESC
        LIMIT 7 ");

        $stmt->execute(['prof_id' => $id]);
        $dashboard['activite_recente'] = $stmt->fetchAll(PDO::FETCH_ASSOC);


        // ÉLÈVES AVEC MEILLEURES MOYENNES

        $stmt = $connexion->prepare("SELECT 
            e.id,
            e.nom,
            e.prenom,
            c.nom as classe,
            ROUND(AVG(n.note), 2) as moyenne,
            COUNT(n.id) as nb_notes
        FROM notes n
        JOIN eleves e ON n.eleve_id = e.id
        JOIN classes c ON e.classe_id = c.id
        WHERE n.professeur_id = :prof_id
        GROUP BY e.id, e.nom, e.prenom, c.nom
        HAVING moyenne >= 16
        ORDER BY moyenne DESC
        LIMIT 5 ");
    
        $stmt->execute(['prof_id' => $id]);
        $dashboard['meilleurs_eleves'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // ÉLÈVES EN DIFFICULTÉ (moyenne < 10)

        $stmt = $connexion->prepare("SELECT 
            e.id,
            e.nom,
            e.prenom,
            c.nom as classe,
            ROUND(AVG(n.note), 2) as moyenne,
            COUNT(n.id) as nb_notes
        FROM notes n
        JOIN eleves e ON n.eleve_id = e.id
        JOIN classes c ON e.classe_id = c.id
        WHERE n.professeur_id = :prof_id
        GROUP BY e.id, e.nom, e.prenom, c.nom
        HAVING moyenne < 10
        ORDER BY moyenne ASC
        LIMIT 5 ");

        $stmt->execute(['prof_id' => $id]);
        $dashboard['eleves_difficulte'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // RÉPARTITION DES NOTES (par tranche)

        $stmt = $connexion->prepare("SELECT 
            CASE 
                WHEN n.note >= 16 THEN 'Très bien (16-20)'
                WHEN n.note >= 14 THEN 'Bien (14-16)'
                WHEN n.note >= 12 THEN 'Assez bien (12-14)'
                WHEN n.note >= 10 THEN 'Passable (10-12)'
                ELSE 'Insuffisant (0-10)'
            END as tranche,
            COUNT(*) as nb_notes,
            ROUND((COUNT(*) * 100.0 / (SELECT COUNT(*) FROM notes WHERE professeur_id = :prof_id)), 2) as pourcentage
        FROM notes n
        WHERE n.professeur_id = :prof_id
        GROUP BY tranche
        ORDER BY 
            CASE 
                WHEN n.note >= 16 THEN 1
                WHEN n.note >= 14 THEN 2
                WHEN n.note >= 12 THEN 3
                WHEN n.note >= 10 THEN 4
                ELSE 5
            END ");
    
        $stmt->execute(['prof_id' => $id]);
        $dashboard['repartition_notes'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // TAUX DE RÉUSSITE PAR CLASSE

        $stmt = $connexion->prepare("SELECT 
            c.nom as classe,
            COUNT(DISTINCT e.id) as nb_eleves,
            COUNT(CASE WHEN moyenne >= 10 THEN 1 END) as nb_reussite,
            ROUND((COUNT(CASE WHEN moyenne >= 10 THEN 1 END) * 100.0 / COUNT(DISTINCT e.id)), 2) as taux_reussite
        FROM (
            SELECT 
                n.eleve_id,
                e.classe_id,
                AVG(n.note) as moyenne
            FROM notes n
            JOIN eleves e ON n.eleve_id = e.id
            WHERE n.professeur_id = :prof_id
            GROUP BY n.eleve_id, e.classe_id
        ) as moyennes
        JOIN eleves e ON moyennes.eleve_id = e.id
        JOIN classes c ON e.classe_id = c.id
        GROUP BY c.id, c.nom
        ORDER BY c.nom");
    
        $stmt->execute(['prof_id' => $id]);
        $dashboard['taux_reussite'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // OBJECTIFS / ALERTES
        $stmt = $connexion->prepare("SELECT 
            e.id,
            e.nom,
            e.prenom,
            c.nom as classe,
            MAX(n.date_evaluation) as derniere_note
        FROM eleves e
        JOIN classes c ON e.classe_id = c.id
        JOIN enseignements en ON en.classe_id = c.id AND en.professeur_id = :prof_id
        LEFT JOIN notes n ON n.eleve_id = e.id AND n.professeur_id = :prof_id
        WHERE e.actif = 1
        GROUP BY e.id, e.nom, e.prenom, c.nom
        HAVING derniere_note IS NULL OR derniere_note < DATE_SUB(NOW(), INTERVAL 30 DAY)
        LIMIT 10");
    
        $stmt->execute(['prof_id' => $id]);
        $dashboard['alertes_eleves_sans_notes'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // COMPARAISON AVEC LA MOYENNE DE L'ÉTABLISSEMENT

        $stmt = $connexion->prepare("SELECT 
            (SELECT ROUND(AVG(note), 2) FROM notes WHERE professeur_id = :prof_id) as ma_moyenne,
            (SELECT ROUND(AVG(note), 2) FROM notes) as moyenne_etablissement");
    
        $stmt->execute(['prof_id' => $id]);
        $dashboard['comparaison'] = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $dashboard;
    
    

    }


?>



    





