<?php
/**
 * seed.php - Script pour remplir la base de données avec des données de test
 * 
 * ATTENTION : Ce script va SUPPRIMER toutes les données existantes !
 * 
 * Usage : php seed.php
 * Ou : http://localhost/Gestion_Classe/seed.php
 */

$transaction_active = false;

require_once __DIR__ . "/database/connexion.php";

echo "========================================\n";
echo "  SEED - Génération de données de test\n";
echo "========================================\n\n";

// Demander confirmation
if (php_sapi_name() === 'cli') {
    echo "⚠️  ATTENTION : Ce script va SUPPRIMER toutes les données existantes !\n";
    echo "Voulez-vous continuer ? (oui/non) : ";
    $handle = fopen("php://stdin", "r");
    $line = fgets($handle);
    if (trim($line) !== 'oui') {
        echo "Opération annulée.\n";
        exit;
    }
}

try {
    $connexion->beginTransaction();
    
    // ==========================================
    // 1. NETTOYER LES TABLES
    // ==========================================
    echo "🗑️  Nettoyage des tables...\n";
    
    $connexion->exec("SET FOREIGN_KEY_CHECKS = 0");
    $connexion->exec("TRUNCATE TABLE notes");
    $connexion->exec("TRUNCATE TABLE enseignements");
    $connexion->exec("TRUNCATE TABLE eleves");
    $connexion->exec("TRUNCATE TABLE classes");
    $connexion->exec("TRUNCATE TABLE professeurs");
    $connexion->exec("TRUNCATE TABLE matieres");
    $connexion->exec("SET FOREIGN_KEY_CHECKS = 1");
    
    echo "✅ Tables nettoyées\n\n";
    
    // ==========================================
    // 2. INSÉRER LES PROFESSEURS
    // ==========================================
    echo "👨‍🏫 Insertion des professeurs...\n";

    $mdp = password_hash("1234", PASSWORD_BCRYPT);

    $professeurs = [
        ['Dupont', 'Marie', 'marie.dupont@ecole.fr', $mdp , '0612345678', 'Mathématiques', '2018-09-01'],
        ['Martin', 'Jean', 'jean.martin@ecole.fr', $mdp , '0623456789', 'Français', '2015-09-01'],
        ['Bernard', 'Sophie', 'sophie.bernard@ecole.fr', $mdp , '0634567890', 'Anglais', '2019-09-01'],
        ['Dubois', 'Pierre', 'pierre.dubois@ecole.fr', $mdp , '0645678901', 'Histoire-Géographie', '2017-09-01'],
        ['Leroy', 'Christine', 'christine.leroy@ecole.fr', $mdp , '0656789012', 'Sciences Physiques', '2020-09-01'],
        ['Moreau', 'Laurent', 'laurent.moreau@ecole.fr', $mdp , '0667890123', 'SVT', '2016-09-01'],
        ['Simon', 'Isabelle', 'isabelle.simon@ecole.fr', $mdp , '0678901234', 'EPS', '2014-09-01'],
        ['Laurent', 'Marc', 'marc.laurent@ecole.fr', $mdp , '0689012345', 'Arts Plastiques', '2021-09-01']
    ];

    $stmt = $connexion->prepare("INSERT INTO professeurs (nom, prenom, email,mdp, telephone, specialite, date_embauche, actif) 
        VALUES (?, ?, ?, ?, ?, ?, ?, 1)
    ");
    
    foreach ($professeurs as $prof) {
        $stmt->execute($prof);
    }
    
    echo "✅ " . count($professeurs) . " professeurs insérés\n\n";
    
    // ==========================================
    // 3. INSÉRER LES MATIÈRES
    // ==========================================
    echo "📚 Insertion des matières...\n";
    
    $matieres = [
        ['Mathématiques', 4.0],
        ['Français', 4.0],
        ['Anglais',  3.0],
        ['Histoire-Géographie',  3.0],
        ['Sciences Physiques',  3.0],
        ['SVT',  3.0],
        ['EPS',  2.0],
        ['Arts Plastiques',  2.0],
        ['Technologie',  2.0],
        ['Musique',  2.0]
    ];
    
    $stmt = $connexion->prepare("INSERT INTO matieres (nom, coefficient) 
        VALUES (?, ?)
    ");
    
    foreach ($matieres as $matiere) {
        $stmt->execute($matiere);
    }
    
    echo "✅ " . count($matieres) . " matières insérées\n\n";
    
    // ==========================================
    // 4. INSÉRER LES CLASSES
    // ==========================================
    echo "🏫 Insertion des classes...\n";
    
    $classes = [
        ['6ème A', '6ème', '2024-2025'],
        ['6ème B', '6ème', '2024-2025'],
        ['5ème A', '5ème', '2024-2025'],
        ['5ème B', '5ème', '2024-2025'],
        ['4ème A', '4ème', '2024-2025'],
        ['4ème B', '4ème', '2024-2025'],
        ['3ème A', '3ème', '2024-2025'],
        ['3ème B', '3ème', '2024-2025']
    ];
    
    $stmt = $connexion->prepare("INSERT INTO classes (nom, niveau, annee_scolaire) 
        VALUES (?, ?, ?)
    ");
    
    foreach ($classes as $classe) {
        $stmt->execute($classe);
    }
    
    echo "✅ " . count($classes) . " classes insérées\n\n";
    
    // ==========================================
    // 5. INSÉRER LES ÉLÈVES
    // ==========================================
    echo "👨‍🎓 Insertion des élèves...\n";
    
    $noms = ['Durand', 'Lefebvre', 'Moreau', 'Simon', 'Laurent', 'Petit', 'Roux', 'David', 'Bertrand', 'Morel', 
             'Fournier', 'Girard', 'Bonnet', 'Dupuis', 'Lambert', 'Fontaine', 'Rousseau', 'Vincent', 'Muller', 'Lefevre',
             'Faure', 'Andre', 'Mercier', 'Blanc', 'Guerin', 'Boyer', 'Garnier', 'Chevalier', 'Francois', 'Legrand'];
    
    $prenoms_garcons = ['Lucas', 'Hugo', 'Louis', 'Gabriel', 'Arthur', 'Jules', 'Raphael', 'Adam', 'Noah', 'Nathan',
                        'Theo', 'Tom', 'Enzo', 'Maxime', 'Antoine', 'Paul', 'Victor', 'Alexandre', 'Mathis', 'Clement'];
    
    $prenoms_filles = ['Emma', 'Lea', 'Chloe', 'Manon', 'Sarah', 'Camille', 'Jade', 'Lola', 'Lisa', 'Zoe',
                       'Clara', 'Oceane', 'Marine', 'Laura', 'Julie', 'Alice', 'Charlotte', 'Lucie', 'Pauline', 'Amelie'];
    
    $stmt = $connexion->prepare("INSERT INTO eleves (nom, prenom, sexe, adresse, mdp, telephone, email, classe_id, date_inscription) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    $eleve_count = 0;
    
    // Pour chaque classe
    for ($classe_id = 1; $classe_id <= 8; $classe_id++) {
        $nb_eleves = rand(22, 28); // Entre 22 et 28 élèves par classe
        
        for ($i = 0; $i < $nb_eleves; $i++) {
            $sexe = rand(0, 1) ? 'Masculin' : 'Feminin';
            $prenom = $sexe === 'Masculin' ? $prenoms_garcons[array_rand($prenoms_garcons)] : $prenoms_filles[array_rand($prenoms_filles)];
            $nom = $noms[array_rand($noms)];
    
            $date = Date("Y-m-d H:i:s"); 
            $mdp = "1234";
            $mdp = password_hash($mdp, PASSWORD_BCRYPT);
            $telephone = '06' . rand(10000000, 99999999);
            $email = strtolower($prenom . '.' . $nom . '@eleve.fr');
            $adresse = "bp 225 abidjan";
            
            $stmt->execute([$nom, $prenom, $sexe, $adresse, $mdp, $telephone, $email, $classe_id, $date]);

            $eleve_count++;
        }
    }
    
    echo "✅ $eleve_count élèves insérés\n\n";
    
    // ==========================================
    // 6. INSÉRER LES ENSEIGNEMENTS
    // ==========================================
    echo "📖 Insertion des enseignements...\n";
    
    // Mathématiques (prof 1) pour toutes les classes
    for ($classe_id = 1; $classe_id <= 8; $classe_id++) {
        $connexion->exec("INSERT INTO enseignements (professeur_id, matiere_id, classe_id, annee_scolaire) VALUES (1, 1, $classe_id, '2024-2025')");
    }
    
    // Français (prof 2) pour classes 1-4
    for ($classe_id = 1; $classe_id <= 4; $classe_id++) {
        $connexion->exec("INSERT INTO enseignements (professeur_id, matiere_id, classe_id, annee_scolaire) VALUES (2, 2, $classe_id, '2024-2025')");
    }
    
    // Anglais (prof 3) pour toutes les classes
    for ($classe_id = 1; $classe_id <= 8; $classe_id++) {
        $connexion->exec("INSERT INTO enseignements (professeur_id, matiere_id, classe_id, annee_scolaire) VALUES (3, 3, $classe_id, '2024-2025')");
    }
    
    // Histoire-Géo (prof 4) pour classes 5-8
    for ($classe_id = 5; $classe_id <= 8; $classe_id++) {
        $connexion->exec("INSERT INTO enseignements (professeur_id, matiere_id, classe_id, annee_scolaire) VALUES (4, 4, $classe_id, '2024-2025')");
    }
    
    // Sciences Physiques (prof 5) pour classes 3-6
    for ($classe_id = 3; $classe_id <= 6; $classe_id++) {
        $connexion->exec("INSERT INTO enseignements (professeur_id, matiere_id, classe_id, annee_scolaire) VALUES (5, 5, $classe_id, '2024-2025')");
    }
    
    // SVT (prof 6) pour toutes les classes
    for ($classe_id = 1; $classe_id <= 8; $classe_id++) {
        $connexion->exec("INSERT INTO enseignements (professeur_id, matiere_id, classe_id, annee_scolaire) VALUES (6, 6, $classe_id, '2024-2025')");
    }
    
    // EPS (prof 7) pour toutes les classes
    for ($classe_id = 1; $classe_id <= 8; $classe_id++) {
        $connexion->exec("INSERT INTO enseignements (professeur_id, matiere_id, classe_id, annee_scolaire) VALUES (7, 7, $classe_id, '2024-2025')");
    }
    
    echo "✅ Enseignements insérés\n\n";
    
    // ==========================================
    // 7. INSÉRER LES NOTES
    // ==========================================
    echo "📝 Insertion des notes (peut prendre un moment)...\n";
    
    $types_evaluation = ['Devoir', 'Contrôle', 'Examen', 'Oral', 'TP'];
    $coefficients = [1.0, 1.5, 2.0, 2.5, 3.0];
    
    $stmt = $connexion->prepare("INSERT INTO notes (eleve_id, matiere_id, professeur_id, note, type_evaluation, coefficient, trimestre, date_evaluation) VALUES (?, ?, ?, ?, ?, ?, ?, ?) ");
    
    $note_count = 0;
    
    // Récupérer tous les élèves
    $eleves = $connexion->query("SELECT id, classe_id FROM eleves")->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($eleves as $eleve) {
        $classe_id = $eleve['classe_id'];
        $eleve_id = $eleve['id'];
        
        // Récupérer les enseignements de cette classe
        $enseignements = $connexion->query("SELECT professeur_id, matiere_id 
            FROM enseignements 
            WHERE classe_id = $classe_id
        ")->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($enseignements as $ens) {
            $prof_id = $ens['professeur_id'];
            $matiere_id = $ens['matiere_id'];
            
            // Générer des notes pour les 3  trimestres
            for ($trimestre = 1; $trimestre <= 3; $trimestre++) {
                $nb_notes = rand(3, 6); // Entre 3 et 6 notes par matière par trimestre
                
                for ($i = 0; $i < $nb_notes; $i++) {
                    // Générer une note avec distribution réaliste (moyenne autour de 12-13)
                    $note = round(rand(5, 20) + (rand(-2, 2) * 0.5), 2);
                    $note = max(0, min(20, $note)); // Entre 0 et 20
                    
                    $type = $types_evaluation[array_rand($types_evaluation)];
                    $coef = $coefficients[array_rand($coefficients)];
                    
                    // Date aléatoire dans le trimestre
                    $mois_debut = ($trimestre - 1) * 3 + 9; // Sept, Dec, Mars
                    $mois = $mois_debut + rand(0, 2);
                    if ($mois > 12) $mois -= 12;
                    $annee = $mois >= 9 ? 2024 : 2025;
                    $date = "$annee-" . str_pad($mois, 2, '0', STR_PAD_LEFT) . "-" . rand(1, 28);
                    
                    $stmt->execute([
                        $eleve_id, 
                        $matiere_id, 
                        $prof_id, 
                        $note, 
                        $type, 
                        $coef, 
                        $trimestre, 
                        $date
                    ]);
                    
                    $note_count++;
                    
                    // Afficher progression tous les 100 notes
                    if ($note_count % 100 === 0) {
                        echo "  ⏳ $note_count notes insérées...\n";
                    }
                }
            }
        }
    }
    
    echo "✅ $note_count notes insérées\n\n";
    
    // ==========================================
    // 8. STATISTIQUES FINALES
    // ==========================================
    echo "========================================\n";
    echo "  STATISTIQUES\n";
    echo "========================================\n";
    
    $stats = [
        'Professeurs' => $connexion->query("SELECT COUNT(*) FROM professeurs")->fetchColumn(),
        'Matières' => $connexion->query("SELECT COUNT(*) FROM matieres")->fetchColumn(),
        'Classes' => $connexion->query("SELECT COUNT(*) FROM classes")->fetchColumn(),
        'Élèves' => $connexion->query("SELECT COUNT(*) FROM eleves")->fetchColumn(),
        'Enseignements' => $connexion->query("SELECT COUNT(*) FROM enseignements")->fetchColumn(),
        'Notes' => $connexion->query("SELECT COUNT(*) FROM notes")->fetchColumn(),
    ];
    
    foreach ($stats as $label => $count) {
        echo "✅ $label : $count\n";
    }
    
    echo "\n========================================\n";
    echo "  EXEMPLES DE CONNEXION\n";
    echo "========================================\n";
    echo "📧 Prof : marie.dupont@ecole.fr\n";
    echo "📧 Élève : (consultez la table eleves)\n\n";
    
    $connexion->commit();
    
    
    echo "✅ ✅ ✅  SEED TERMINÉ AVEC SUCCÈS ! ✅ ✅ ✅\n\n";
    
} catch (Exception $e) {
    if ($transaction_active) {
        $connexion->rollBack();
    }
    echo "\n❌ ERREUR : " . $e->getMessage() . "\n";
    echo "Fichier : " . $e->getFile() . "\n";
    echo "Ligne : " . $e->getLine() . "\n";
    echo "\nTrace :\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
?>