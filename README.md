# Résumé
Triplice a créé un site web "Looper" permettant de créer des exercices, prendre les exercices pour les effectuer avec la possibilité de les modifier plus tard, par l'intermédiaire de l'url, et enfin gérer les exercices.
La page de gestion des exercices permet de revenir sur un exercise qui était en cours de création `Building` en lui rajoutant, modifant ou en effaçant des question et quand l'exercice vous semble prêt, vous pouvez le passer en exercice à compléter (correspond au statut `Answering`).
Vous avez la possibilité de voir les réponses par question ou par utilisateur sur tous les exercices ayant le statut `Answering` ou `Closed`.

# Installation

> Tous les fichiers nécessaires se situent dans `Triplice/Looper/` et `Triplice/DB/`

1. Avoir un serveur Appache
2. Avoir une base de données MySQL
3. Créer la base de données via `DB_script.sql`
4. Mettre sur votre serveur Apache (à la racine) le contenu du dossier `Triplice/Looper/`
5. Dupliquer le fichier `configExample.php` et le renommer `config.php`
6. Modifier les données de celui-ci avec les informations de votre base de données


## wiki Français / French
Si vous souhaitez plus d'informations sur notre projet, nous possèdons un wiki:
https://github.com/CPNV-ES/Triplice/wiki
