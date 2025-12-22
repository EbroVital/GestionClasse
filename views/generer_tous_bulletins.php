<?php
// generer_tous_bulletins.php - Génère un ZIP avec tous les bulletins d'une classe

    // Charger l'autoloader de Composer
    require_once __DIR__ . '/../vendor/autoload.php';
    require_once __DIR__ . '/../database/connexion.php';

    $classe_id = isset($_GET['classe_id']) ? intval($_GET['classe_id']) : 0;
    $trimestre = isset($_GET['trimestre']) ? intval($_GET['trimestre']) : 1;

    if ($classe_id <= 0) {
        die("Classe non spécifiée");
    }

    // Récupérer les élèves de la classe
    $stmt = $connexion->prepare("SELECT e.id, e.nom, e.prenom, c.nom as classe_nom
        FROM eleves e
        JOIN classes c ON e.classe_id = c.id
        WHERE e.classe_id = :classe_id
        AND e.actif = 1
        ORDER BY e.nom, e.prenom
    ");
    $stmt->execute(['classe_id' => $classe_id]);
    $eleves = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($eleves)) {
        die("Aucun élève dans cette classe");
    }

    $classe_nom = $eleves[0]['classe_nom'];

    // Créer un dossier temporaire
    $temp_dir = sys_get_temp_dir() . '/bulletins_' . uniqid();
    mkdir($temp_dir);

    // Générer un PDF pour chaque élève
    foreach ($eleves as $eleve) {
        
    // Récupérer les notes de l'élève
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
        ORDER BY m.nom
    ");
    
    $stmt->execute([
        'eleve_id' => $eleve['id'],
        'trimestre' => $trimestre
    ]);
    
    $matieres = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Si l'élève n'a pas de notes, on skip
    if (empty($matieres)) {
        continue;
    }
    
    // Calculer la moyenne générale
    $somme_notes_ponderees = 0;
    $somme_coefficients = 0;
    
    foreach($matieres as $matiere) {
        $somme_notes_ponderees += $matiere['moyenne'] * $matiere['coef_matiere'];
        $somme_coefficients += $matiere['coef_matiere'];
    }
    
    $moyenne_generale = $somme_coefficients > 0 
        ? round($somme_notes_ponderees / $somme_coefficients, 2) 
        : 0;
    
    // Créer le PDF
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->SetMargins(15, 15, 15);
    $pdf->SetAutoPageBreak(TRUE, 15);
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 10);
    
    // Contenu HTML (simplifié)
    $html = '
    <h1 style="text-align: center; color: #2c3e50;">BULLETIN SCOLAIRE</h1>
    <h2 style="text-align: center; color: #34495e;">Trimestre ' . $trimestre . '</h2>
    
    <div style="background-color: #ecf0f1; padding: 10px; margin: 15px 0;">
        <p><strong>Nom :</strong> ' . strtoupper($eleve['nom']) . '</p>
        <p><strong>Prénom :</strong> ' . ucfirst($eleve['prenom']) . '</p>
        <p><strong>Classe :</strong> ' . $classe_nom . '</p>
    </div>
    
    <table border="1" cellpadding="5" style="border-collapse: collapse; width: 100%;">
        <thead>
            <tr style="background-color: #3498db; color: white;">
                <th>MATIÈRE</th>
                <th>COEF.</th>
                <th>MOYENNE</th>
            </tr>
        </thead>
        <tbody>';
    
    foreach($matieres as $matiere) {
        $html .= '
            <tr>
                <td>' . $matiere['matiere'] . '</td>
                <td style="text-align: center;">' . $matiere['coef_matiere'] . '</td>
                <td style="text-align: center;">' . $matiere['moyenne'] . '/20</td>
            </tr>';
    }
    
    $html .= '
        </tbody>
        <tfoot>
            <tr style="background-color: #2ecc71; color: white; font-weight: bold;">
                <td colspan="2">MOYENNE GÉNÉRALE</td>
                <td style="text-align: center;">' . $moyenne_generale . '/20</td>
            </tr>
        </tfoot>
    </table>';
    
    $pdf->writeHTML($html, true, false, true, false, '');
    
    // Sauvegarder le PDF
    $filename = $temp_dir . '/Bulletin_' . $eleve['nom'] . '_' . $eleve['prenom'] . '.pdf';
    $pdf->Output($filename, 'F');
}

    // Créer le fichier ZIP
    $zip_filename = 'Bulletins_' . str_replace(' ', '_', $classe_nom) . '_T' . $trimestre . '.zip';
    $zip = new ZipArchive();
    $zip_path = $temp_dir . '/' . $zip_filename;

    if ($zip->open($zip_path, ZipArchive::CREATE) !== TRUE) {
        die("Impossible de créer le fichier ZIP");
    }

    // Ajouter tous les PDF au ZIP
    $files = glob($temp_dir . '/*.pdf');
    foreach ($files as $file) {
        $zip->addFile($file, basename($file));
    }

    $zip->close();

    // Envoyer le ZIP au navigateur
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . $zip_filename . '"');
    header('Content-Length: ' . filesize($zip_path));
    readfile($zip_path);

    // Nettoyer les fichiers temporaires
    foreach ($files as $file) {
        unlink($file);
    }
    unlink($zip_path);
    rmdir($temp_dir);

    exit;
?>