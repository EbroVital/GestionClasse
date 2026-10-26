#  Système de Gestion de Classe
Un système complet de gestion scolaire développé en PHP natif, permettant aux professeurs de gérer les notes, générer des bulletins et suivre les performances des élèves.
##  Fonctionnalités
###  Espace Professeur
- ✅ Dashboard interactif avec statistiques en temps réel
- ✅ Gestion des notes : ajout, modification, suppression avec validation
- ✅ Sélection dynamique des élèves par classe (AJAX)
- ✅ Gestion des enseignements (matières par classe)
- ✅ Génération de bulletins PDF individuels et en masse
- ✅ Téléchargement ZIP de tous les bulletins d'une classe
- ✅ Statistiques avancées : moyennes, taux de réussite
- ✅ Alertes intelligentes : élèves en difficulté, sans notes récentes
###  Espace Élève
- ✅ Dashboard personnalisé avec toutes les informations académiques
- ✅ Consultation des notes par trimestre et matière
- ✅ Calcul automatique des moyennes pondérées
- ✅ Téléchargement des bulletins au format PDF
- ✅ Visualisation de la progression et du classement
- ✅ Points forts et axes d'amélioration
###  Fonctionnalités techniques
- ✅ Sélection dynamique classe → élèves (jQuery AJAX)
- ✅ Auto-complétion des coefficients par matière
- ✅ Génération PDF avec TCPDF
- ✅ Confirmations élégantes avec SweetAlert2
- ✅ Design responsive (Bootstrap 4)
- ✅ Architecture MVC claire et maintenable
- ✅ Requêtes préparées (protection SQL injection)
- ✅ Gestion des sessions sécurisée
##  Technologies utilisées
### Backend
- PHP 8.2+ - Langage principal
- MySQL 8.0+ - Base de données relationnelle
- PDO - Accès sécurisé à la base de données
### Frontend
- HTML5 / CSS3 - Structure et style
- Bootstrap 4.5 - Framework CSS responsive
- jQuery 3.6 - Manipulation DOM et AJAX
- SweetAlert2 - Modales élégantes
- Font Awesome 5.15 - Icônes
## Bibliothèques PHP
- TCPDF 6.7 - Génération de PDF
- Composer - Gestion des dépendances
##  Prérequis
Avant de commencer, assurez-vous d'avoir :
- PHP >= 8.0
- MySQL >= 8.0 ou MariaDB >= 10.5
- Apache ou Nginx
- Composer (gestionnaire de dépendances PHP)
- WAMP/XAMPP/MAMP (recommandé pour Windows/Mac)
##  Installation
### 1️⃣ Cloner le projet
```bash
git clone https://github.com/EbroVital/GestionClasse.git
cd GestionClasse
```
### 2️⃣ Installer les dépendances
```bash
composer install
```
Cela installera automatiquement :
- TCPDF
### 3️⃣ Créer la base de données
### Option A : Via phpMyAdmin
- Ouvrez phpMyAdmin
- Créez une base de données nommée dbclasse
- Importez le fichier database/schema.sql
### Option B : Via ligne de commande
```bash
# Créer la base de données
mysql -u root -p -e "CREATE DATABASE gestion_classe CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Importer le schéma
mysql -u root -p gestion_classe < database/schema.sql
```
### 4️⃣ Configurer la connexion
Éditez le fichier database/connexion.php :
```bash
<?php
try {
    $connexion = new PDO(
        "mysql:host=localhost;dbname=gestion_classe;charset=utf8mb4",
        "root",        // Votre username MySQL
        "",            // Votre mot de passe MySQL
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
```
### 5️⃣ Générer des données de test
```bash
php seed.php
```
Cela créera :
- 8 professeurs
- 10 matières
- 8 classes
- ~200 élèves
- ~5000 notes
Identifiants de test :
- Professeur : marie.dupont / prof123
- Élève : Consultez la table eleves
### 6️⃣ Démarrer le serveur
Avec WAMP/XAMPP
- Démarrez les services Apache et MySQL
- Accédez à : http://localhost/Gestion_Classe/
##  Utilisation
### Connexion pour un professeur
Accédez à http://localhost/Gestion_Classe/views/login.php et connectez-vous avec :
Email : marie.dupont@ecole.fr
Mot de passe : 1234 
### Ajouter une note
- Allez dans "Ajouter une note"
- Sélectionnez la classe (les élèves se chargent automatiquement)
- Sélectionnez la matière (le coefficient s'affiche automatiquement)
- Remplissez la note et validez
## Générer des bulletins
### Un bulletin individuel
- Allez dans "Bulletins"
- Sélectionnez une classe
- Cliquez sur T1, T2 ou T3 pour un élève
### Tous les bulletins d'une classe
- Cliquez sur "Télécharger Trimestre X"
- Un fichier ZIP se télécharge avec tous les bulletins
### Consulter les statistiques
Le dashboard professeur affiche :
- Nombre de classes, élèves, notes
- Moyenne générale
- Taux de réussite par classe
- Notes récentes
- Alertes (élèves en difficulté, sans notes)

## Capture d'ecran
