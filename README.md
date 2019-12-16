# Résumé

Notre application sert à gérer des exercices. Elle permet premièrement de créer de nouveaux exercices. Ensuite, les utilisateurs peuvent répondre aux exercices créés. De plus, ils peuvent revenir modifier les réponses ultérieurement. Enfin, il est possible de consulter les toutes les réponses à un exercice.

# Installation

> Tous les fichiers nécessaires se situent dans `Triplice/Looper/` et `Triplice/DB/`

1. Avoir un serveur Apache 2.4.41
2. Avoir PHP 7.2.23
3. Avoir une base de données MySQL 8.0.17
4. Créer la base de données via `DB_script.sql`
5. Mettre sur votre serveur Apache (à la racine) le contenu du dossier `Triplice/Looper/`
6. Dupliquer le fichier `configExample.php` et le renommer `config.php`
7. Modifier les données de celui-ci avec les informations de votre base de données

# Librairies

> se situent dans `Triplice/Library`

## Router

Cette librairie permet de faire une gestion des pages par l'intermédiaire des url.
Elle fonctionne avec le fichier Routes.php à la racine de Looper.

Pour l'utiliser, vous devez commencer par ajouter les différentes routes (liens url) et 
dire quelle méthode de quel contrôleur vous souhaitez appeler. \
Exemple, nous voulons faire que quand nous écrivons `/home`, après le lien de notre site,
nous sommes redirigés sur la page d'accueil. 

Il va donc falloir écrire la ligne suivante:

```php 
Router::add("/home", "HomeController@index"); 
```

HomeController est le contrôleur de la page principale et index est une méthode dans ce contrôleur,
le @ sert de séparateur entre la méthode et le contrôleur.

si vous souhaitez passer des données par l'intermédiaire des liens, comme des id ou du texte, il vous 
suffit de créer des routes comme dans l'exemple suivant:

```php 
Router::add("/question/id/valeur/text", "ExerciseController@exemple");
```
Ici, nous répérons l'id de la question et la valeur que l'utilisateur a rentré.

Les valeurs seront sauvegardées dans les mot précédent le mot clé, vous pourrez le voir dans le prochain exemple.

Au niveau de la méthode, vous devez mettre que vous attendez des paramètres:

```php
public function exemple($params)
{
  $id=$params->exercise;
  $text=$params->valeur;  
}
```

Quand toutes les routes que vous avez définies sont créées, vous devez ajouter la ligne suivante:

```php 
Router::run(); 
```

## wiki Français / French
Si vous souhaitez plus d'informations sur notre projet, nous possèdons un wiki:
https://github.com/CPNV-ES/Triplice/wiki
