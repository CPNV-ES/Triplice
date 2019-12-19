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
Exemple, nous voulons faire que quand nous écrivons `notresite.ch/home`,
nous sommes redirigés sur la page d'accueil. 

Il va donc falloir écrire pour la route `/home` la ligne suivante:

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
Ici, nous répérons l'id de la question et la valeur que l'utilisateur a rentré, ce qui ressemblerai à : `notresite.ch/question/3/valeur/un%20%exemple`.

Les valeurs seront sauvegardées dans les mot précédent le mot clé, ce qui vous permettra de facilement récupèrer ses valeurs.

Au niveau de la méthode, vous devez mettre que vous attendez des paramètres, ce qui vous permettra de récupérer les donnez:

```php
public function exemple($paramsOfUrl)
{
  $id=$paramsOfUrl->exercise;
  $text=$paramsOfUrl->valeur;  
}
```

Quand toutes les routes que vous avez définies sont créées, vous devez ajouter la ligne suivante:

```php 
Router::run(); 
```

## View

Elle ajoute toutes nos views dans le gabarit, ce qui signifie que nous n'avons pas à recoder à chaque fois les parties qui seront identiques sur chaque page, comme par exemple un menu.

dans les contrôleurs il suffit d'ajouter une des lignes suivantes:

Si vous avez des variables à transmettre à votre vue il faudra faire:

```php
View::render("Home", $params);
```

Si vous n'avez pas besoin de transmettre d'informations, il suffit juste de faire:
```php
View::render("Home");
```

dans les paramètres de la méthode `render`, vous n'avez pas besoin de dire où se situe le fichier, car il est défini dans la librairie par l'intermédiaire de la variable `$dir`, de même que pour l'extention .php dans la variable `$ext`.

Si votre vue se situe dans un sous répertoire à `View`, vous n'avez qu'à écrire : `Mon Répertoir/Mon fichier`.


## wiki Français / French
Si vous souhaitez plus d'informations sur notre projet, nous possèdons un wiki:
https://github.com/CPNV-ES/Triplice/wiki
