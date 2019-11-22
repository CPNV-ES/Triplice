## Résumé
Triplice a créé un site web "Looper" permettant de créer des exercices, prendre les exercices pour les effectuer avec la possibilité de les modifier plus tard, par l'intermédiaire de l'url, et enfin gérer les exercices.
La page de gestion des exercices permet de revenir sur un exercise qui était en cours de création `Building` en lui rajoutant, modifant ou en effaçant des question et quand l'exercice vous semble prêt, vous pouvez le passer en exercice à compléter (correspond au statut `Answering`).
Vous avez la possibilité de voir les réponses par question ou par utilisateur sur tous les exercices ayant le statut `Answering` ou `Closed`.

# Mise en place

## Prérequis:
* Serveur Apache avec php 7.2
* Base de données MySQL

## Installation
### Base de données
> (optionnel) créer sur la base de données un utilisateur uniquement pour le site

Ouvrir le fichier `DB_script.sql` dans le répertoire `BD` et éxécuter le scripts pour créer la base de données.

> Pour avoir des données de test, éxécutez  `DB_insert.sql` (3 exercices ayant chacun un statut différent)

### Site
1. Prendre tout le contenu du dossier `Looper` et le mettre à la racine du répertoire de votre serveur.
> **Attention**, si votre site commence par exemple `monsite.com/www/....` il vous faudra modifier sur le fichier `routes.php` en ajoutant à tous les chemins le `/www/` pour indiquer les routes commencent par www ainsi que les différents liens sur les pages dans les dossier `view` et `controller`

2. Ouvrir le fichier `Database.php`, dans le dossier `Library`, et modifier les données de connections suivantes: 

>    private static $ip = "Nom_de_votre_site.ch ou adresse_ip";\
>    private static $dbName = "Triplice";\
>    private static $user = "Nom_d_utilisateur_de_votre_base_de_données";\
>    private static $password = "Mot_de_passe_de_l_utilisateur";

# Wiki
Si vous souhaitez plus d'informations sur notre projet, nous possèdons un wiki:
https://github.com/CPNV-ES/Triplice/wiki
