-- Base de données pour la gestion d'une classe
-- Création de la base
-- CREATE DATABASE IF NOT EXISTS gestion_classe CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE dbclasse;

-- ============================================
-- TABLE: classes
-- ============================================
CREATE TABLE classes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL,
    niveau VARCHAR(20) NOT NULL,
    annee_scolaire VARCHAR(20) NOT NULL,
    effectif_max INT DEFAULT 30,
    professeur_principal_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================
-- TABLE: professeurs
-- ============================================
CREATE TABLE professeurs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    mdp TEXT,
    telephone VARCHAR(20),
    specialite VARCHAR(100),
    date_embauche DATE DEFAULT (CURRENT_DATE),
    actif BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================
-- TABLE: eleves
-- ============================================
CREATE TABLE eleves (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    sexe ENUM('M', 'F') NOT NULL,
    adresse TEXT,
    mdp TEXT,
    telephone VARCHAR(20),
    email VARCHAR(150),
    classe_id INT NOT NULL,
    date_inscription DATE DEFAULT (CURRENT_DATE),
    actif BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (classe_id) REFERENCES classes(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ============================================
-- TABLE: matieres
-- ============================================
CREATE TABLE matieres (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    coefficient DECIMAL(3,1) DEFAULT 1.0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================
-- TABLE: enseignements (Table de liaison)
-- Relie professeurs, matieres et classes
-- ============================================
CREATE TABLE enseignements (
    id INT PRIMARY KEY AUTO_INCREMENT,
    professeur_id INT NOT NULL,
    matiere_id INT NOT NULL,
    classe_id INT NOT NULL,
    annee_scolaire VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (professeur_id) REFERENCES professeurs(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (matiere_id) REFERENCES matieres(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (classe_id) REFERENCES classes(id) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE KEY unique_enseignement (professeur_id, matiere_id, classe_id, annee_scolaire)
) ENGINE=InnoDB;

-- ============================================
-- TABLE: notes
-- ============================================
CREATE TABLE notes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    eleve_id INT NOT NULL,
    matiere_id INT NOT NULL,
    professeur_id INT NOT NULL,
    note DECIMAL(5,2) NOT NULL CHECK (note >= 0 AND note <= 20),
    type_evaluation ENUM('Devoir', 'Contrôle', 'Examen', 'Oral', 'TP') DEFAULT 'Devoir',
    coefficient DECIMAL(3,1) DEFAULT 1.0,
    trimestre TINYINT CHECK (trimestre BETWEEN 1 AND 3),
    date_evaluation DATE NOT NULL,
    commentaire TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (eleve_id) REFERENCES eleves(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (matiere_id) REFERENCES matieres(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (professeur_id) REFERENCES professeurs(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ============================================
-- Ajout de la clé étrangère pour professeur principal
-- ============================================
ALTER TABLE classes 
ADD FOREIGN KEY (professeur_principal_id) REFERENCES professeurs(id) ON DELETE SET NULL ON UPDATE CASCADE;

-- ============================================
-- INDEX pour optimiser les performances
-- ============================================
CREATE INDEX idx_eleves_classe ON eleves(classe_id);
CREATE INDEX idx_eleves_nom ON eleves(nom, prenom);
CREATE INDEX idx_notes_eleve ON notes(eleve_id);
CREATE INDEX idx_notes_matiere ON notes(matiere_id);
CREATE INDEX idx_notes_trimestre ON notes(trimestre);
CREATE INDEX idx_enseignements_prof ON enseignements(professeur_id);
CREATE INDEX idx_enseignements_classe ON enseignements(classe_id);

-- ============================================
-- DONNÉES DE TEST
-- ============================================

-- Insertion de professeurs
-- INSERT INTO professeurs (nom, prenom, email, telephone, specialite, date_embauche) VALUES
-- ('Dupont', 'Marie', 'marie.dupont@ecole.fr', '0612345678', 'Mathématiques', '2020-09-01'),
-- ('Martin', 'Jean', 'jean.martin@ecole.fr', '0623456789', 'Français', '2019-09-01'),
-- ('Bernard', 'Sophie', 'sophie.bernard@ecole.fr', '0634567890', 'Anglais', '2021-09-01'),
-- ('Dubois', 'Pierre', 'pierre.dubois@ecole.fr', '0645678901', 'Histoire-Géographie', '2018-09-01');

-- Insertion de classes
-- INSERT INTO classes (nom, niveau, annee_scolaire, professeur_principal_id) VALUES
-- ('6ème A', '6ème', '2024-2025', 1),
-- ('6ème B', '6ème', '2024-2025', 2),
-- ('5ème A', '5ème', '2024-2025', 3);

-- Insertion de matières
-- INSERT INTO matieres (nom, code, coefficient) VALUES
-- ('Mathématiques', 'MATH', 3.0),
-- ('Français', 'FR', 3.0),
-- ('Anglais', 'ANG', 2.0),
-- ('Histoire-Géographie', 'HG', 2.0),
-- ('Sciences Physiques', 'PHY', 2.0),
-- ('SVT', 'SVT', 2.0),
-- ('EPS', 'EPS', 1.0);

-- Insertion d'élèves
-- INSERT INTO eleves (nom, prenom, date_naissance, sexe, classe_id, telephone, email) VALUES
-- ('Durand', 'Lucas', '2012-03-15', 'M', 1, '0656789012', 'lucas.durand@eleve.fr'),
-- ('Lefebvre', 'Emma', '2012-07-22', 'F', 1, '0667890123', 'emma.lefebvre@eleve.fr'),
-- ('Moreau', 'Hugo', '2012-11-08', 'M', 1, '0678901234', 'hugo.moreau@eleve.fr'),
-- ('Simon', 'Léa', '2012-05-30', 'F', 2, '0689012345', 'lea.simon@eleve.fr'),
-- ('Laurent', 'Tom', '2012-09-17', 'M', 2, '0690123456', 'tom.laurent@eleve.fr'),
-- ('Petit', 'Chloé', '2011-02-14', 'F', 3, '0601234567', 'chloe.petit@eleve.fr');

-- Insertion d'enseignements (qui enseigne quoi à quelle classe)
-- INSERT INTO enseignements (professeur_id, matiere_id, classe_id, annee_scolaire) VALUES
-- Mme Dupont (prof 1) enseigne les maths
-- (1, 1, 1, '2024-2025'),
-- (1, 1, 2, '2024-2025'),
-- (1, 1, 3, '2024-2025'),
-- M. Martin (prof 2) enseigne le français
-- (2, 2, 1, '2024-2025'),
-- (2, 2, 2, '2024-2025'),
-- Mme Bernard (prof 3) enseigne l'anglais
-- (3, 3, 1, '2024-2025'),
-- (3, 3, 2, '2024-2025'),
-- (3, 3, 3, '2024-2025'),
-- M. Dubois (prof 4) enseigne l'histoire-géo
-- (4, 4, 1, '2024-2025'),
-- (4, 4, 3, '2024-2025');

-- Insertion de quelques notes
-- INSERT INTO notes (eleve_id, matiere_id, professeur_id, note, type_evaluation, coefficient, trimestre, date_evaluation) VALUES
-- Notes de Lucas (eleve 1) en Maths
-- (1, 1, 1, 15.5, 'Devoir', 1.0, 1, '2024-10-15'),
-- (1, 1, 1, 18.0, 'Contrôle', 2.0, 1, '2024-11-20'),
-- -- Notes d'Emma (eleve 2) en Maths
-- (2, 1, 1, 17.0, 'Devoir', 1.0, 1, '2024-10-15'),
-- (2, 1, 1, 16.5, 'Contrôle', 2.0, 1, '2024-11-20'),
-- -- Notes de Lucas en Français
-- (1, 2, 2, 14.0, 'Devoir', 1.0, 1, '2024-10-18'),
-- (1, 2, 2, 13.5, 'Examen', 3.0, 1, '2024-12-10');

-- ============================================
-- REQUÊTES UTILES
-- ============================================

-- 1. Voir tous les élèves d'une classe avec leur classe
-- SELECT e.*, c.nom as classe_nom 
-- FROM eleves e 
-- JOIN classes c ON e.classe_id = c.id 
-- WHERE c.id = 1;

-- 2. Voir toutes les matières enseignées par un professeur
-- SELECT p.nom, p.prenom, m.nom as matiere, c.nom as classe
-- FROM enseignements en
-- JOIN professeurs p ON en.professeur_id = p.id
-- JOIN matieres m ON en.matiere_id = m.id
-- JOIN classes c ON en.classe_id = c.id
-- WHERE p.id = 1;

-- 3. Calculer la moyenne d'un élève pour une matière
-- SELECT e.nom, e.prenom, m.nom as matiere,
--        ROUND(SUM(n.note * n.coefficient) / SUM(n.coefficient), 2) as moyenne
-- FROM notes n
-- JOIN eleves e ON n.eleve_id = e.id
-- JOIN matieres m ON n.matiere_id = m.id
-- WHERE e.id = 1 AND m.id = 1 AND n.trimestre = 1
-- GROUP BY e.id, m.id;

-- 4. Voir le bulletin d'un élève pour un trimestre
-- SELECT m.nom as matiere, m.coefficient,
--        ROUND(AVG(n.note), 2) as moyenne,
--        COUNT(n.id) as nb_notes
-- FROM notes n
-- JOIN matieres m ON n.matiere_id = m.id
-- WHERE n.eleve_id = 1 AND n.trimestre = 1
-- GROUP BY m.id
-- ORDER BY m.nom;