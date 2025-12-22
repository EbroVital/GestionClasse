<?php
// generer_bulletin.php

require_once __DIR__ . '/../database/connexion.php';
require_once __DIR__ . '/../vendor/autoload.php'; // Ajuste le chemin

// Récupérer les paramètres
$eleve_id = isset($_GET['eleve_id']) ? intval($_GET['eleve_id']) : 0;
$trimestre = isset($_GET['trimestre']) ? intval($_GET['trimestre']) : 1;

if ($eleve_id <= 0) {
    die("Élève non spécifié");
}

// ========================================
// RÉCUPÉRER LES DONNÉES DE L'ÉLÈVE
// ========================================

// Infos élève
$stmt = $connexion->prepare("SELECT e.nom, e.prenom, c.nom as classe, c.niveau
    FROM eleves e
    JOIN classes c ON e.classe_id = c.id
    WHERE e.id = :eleve_id
");
$stmt->execute(['eleve_id' => $eleve_id]);
$eleve = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$eleve) {
    die("Élève non trouvé");
}

// Notes par matière
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
    'eleve_id' => $eleve_id,
    'trimestre' => $trimestre
]);

$matieres = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

// ========================================
// CRÉER LE PDF
// ========================================

$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// Informations du document
$pdf->SetCreator('Gestion Classe');
$pdf->SetAuthor('École');
$pdf->SetTitle('Bulletin Scolaire - ' . $eleve['prenom'] . ' ' . $eleve['nom']);
$pdf->SetSubject('Bulletin Trimestre ' . $trimestre);

// Supprimer header/footer par défaut
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Marges
$pdf->SetMargins(15, 15, 15);
$pdf->SetAutoPageBreak(TRUE, 15);

// Ajouter une page
$pdf->AddPage();

// Police
$pdf->SetFont('helvetica', '', 10);

// ========================================
// CONTENU DU BULLETIN
// ========================================

// En-tête
$html = '
<style>
    h1 { 
        text-align: center; 
        color: #2c3e50; 
        font-size: 20px;
        margin-bottom: 5px;
    }
    h2 { 
        text-align: center; 
        color: #34495e; 
        font-size: 16px;
        margin-top: 0;
    }
    .info-box {
        background-color: #ecf0f1;
        padding: 10px;
        margin: 15px 0;
        border-radius: 5px;
    }
    .info-box p {
        margin: 3px 0;
        padding : 5px;
        font-size: 11px;
    }
    table {
        border-collapse: collapse;
        width: 100%;
        margin-top: 15px;
    }
    th {
        background-color: #3498db;
        color: white;
        padding: 8px;
        text-align: center;
        font-weight: bold;
        font-size: 10px;
    }
    td {
        padding: 6px;
        border: 1px solid #bdc3c7;
        text-align: center;
        font-size: 10px;
    }
    .moyenne-generale {
        background-color: #2ecc71;
        color: white;
        font-weight: bold;
        font-size: 12px;
    }
    .matiere-col {
        text-align: left;
        font-weight: bold;
    }
</style>

<h1>BULLETIN SCOLAIRE</h1>
<h2>Trimestre ' . $trimestre . ' - Année Scolaire 2024-2025</h2>

<div class="info-box">
    <p><strong>Nom :</strong> ' . strtoupper($eleve['nom']) . '</p>
    <p><strong>Prénom :</strong> ' . ucfirst($eleve['prenom']) . '</p>
    <p><strong>Classe :</strong> ' . $eleve['classe'] . '</p>
</div>

<table>
    <thead>
        <tr>
            <th>MATIÈRE</th>
            <th>COEF.</th>
            <th>MOYENNE</th>
            <th>MIN</th>
            <th>MAX</th>
            <th>NB NOTES</th>
        </tr>
    </thead>
    <tbody>';

// Lignes des matières
foreach($matieres as $matiere) {
    $couleur_moyenne = '';
    if ($matiere['moyenne'] >= 16) {
        $couleur_moyenne = 'color: #27ae60; font-weight: bold;';
    } elseif ($matiere['moyenne'] >= 14) {
        $couleur_moyenne = 'color: #2980b9; font-weight: bold;';
    } elseif ($matiere['moyenne'] >= 10) {
        $couleur_moyenne = 'color: #f39c12;';
    } else {
        $couleur_moyenne = 'color: #e74c3c; font-weight: bold;';
    }
    
    $html .= '
        <tr>
            <td class="matiere-col">' . $matiere['matiere'] . '</td>
            <td>' . $matiere['coef_matiere'] . '</td>
            <td style="' . $couleur_moyenne . '">' . number_format($matiere['moyenne'], 2) . '/20</td>
            <td>' . $matiere['note_min'] . '</td>
            <td>' . $matiere['note_max'] . '</td>
            <td>' . $matiere['nb_notes'] . '</td>
        </tr>';
}

// Moyenne générale
$appreciation = '';
if ($moyenne_generale >= 16) {
    $appreciation = 'Très bien';
} elseif ($moyenne_generale >= 14) {
    $appreciation = 'Bien';
} elseif ($moyenne_generale >= 12) {
    $appreciation = 'Assez bien';
} elseif ($moyenne_generale >= 10) {
    $appreciation = 'Passable';
} else {
    $appreciation = 'Insuffisant';
}

$html .= '
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2" class="moyenne-generale">MOYENNE GÉNÉRALE</td>
            <td colspan="4" class="moyenne-generale">' . number_format($moyenne_generale, 2) . '/20 - ' . $appreciation . '</td>
        </tr>
    </tfoot>
</table>

<div style="margin-top: 30px; font-size: 9px; color: #7f8c8d;">
    <p><strong>Appréciation du trimestre :</strong></p>
    <p style="border: 1px solid #bdc3c7; padding: 10px; min-height: 50px;">
        Bulletin généré le ' . date('d/m/Y à H:i') . '
    </p>
</div>

<div style="margin-top: 30px; text-align: right;">
    <p>Le Directeur</p>
    <p style="margin-top: 40px;">_______________________</p>
</div>
';

// Écrire le HTML dans le PDF
$pdf->writeHTML($html, true, false, true, false, '');

// ========================================
// SORTIE DU PDF
// ========================================

// Nom du fichier
$filename = 'Bulletin_' . $eleve['nom'] . '_' . $eleve['prenom'] . '_T' . $trimestre . '.pdf';

// Forcer le téléchargement
$pdf->Output($filename, 'D');

// OU afficher dans le navigateur
// $pdf->Output($filename, 'I');

?>