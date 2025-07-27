# Étapes du projet

## Dans Docker, démarrage du container MongoDB

## Principe des Tests Unitaires avec PHPUnit

On installe **Composer** pour pouvoir travailler avec **PHPUnit** (tests unitaires en PHP) :
`composer init`

*Would you like to define your dependencies (require) interactively [yes]?* `no`
*Would you like to define your dev dependencies (require-dev) interactively [yes]?* `yes`
*Search for a package:* `phpunit`
*Enter package # to add, or the complete package name if it is not listed:* `phpunit/phpunit`

On peut vérifier PHPUnit en faisant `./vendor/bin/phpunit`

J'ai installé avec le **namespace** proposé par défaut `Syl\BrecoCda`
Dans le dossier *src* créé à la racine de mon dossier, je crée un 1ère définition de test rapide, un fichier ***Math.php***

```php
<?php

namespace Syl\BrecoCda;

class Math{
    public static function double($nombre){
        return $nombre *2 ;
    }

}
```

Je vérifie que mon fichier ***composer.json*** a bien la bonne config pour l'autoload :

```json
{
    "name": "syl/breco_cda",
    "require-dev": {
        "phpunit/phpunit": "^12.2"
    },
    "autoload": {
        "psr-4": {
            "Syl\\BrecoCda\\": "src/"
        }
    },
    "authors": [
        {
            "name": "Sylvain Girault",
            "email": "syl@djro.fr"
        }
    ],
    "require": {}
}
```

Je demande à Composer de prendre en compte les modifs `composer dump-autoload`

On crée un dossier ***tests*** à la racine, où on mettra tous nos tests.

Je crée un 1er test rapide, un fichier ***MathTest.php***

```php
<?php

use PHPUnit\Framework\TestCase;

class MathTest extends TestCase {
    
    public function testDouble(){
        $this->assertEquals(4, \Syl\BrecoCda\Math::double(2) );
    }
}

```

Pour améliorer l'affichage, je crée un fichier phpunit.xml à la racine du projet

```xml

<?xml version="1.0" encoding="UTF-8"?>
<!-- active le mode testdox directement et garde les couleurs -->
<phpunit colors="true" testdox="true">
    <testsuites>
        <testsuite name="Test Suite">
            <directory>tests</directory>
        </testsuite>
    </testsuites>
</phpunit>

```

Et un raccourci composer dans composer.json

```json

{
    "name": "syl/breco_cda",
    "require-dev": {
        "phpunit/phpunit": "^12.2"
    },
    "autoload": {
        "psr-4": {
            "Syl\\BrecoCda\\": "src/"
        }
    },
    "authors": [
        {
            "name": "Sylvain Girault",
            "email": "syl@djro.fr"
        }
    ],
    "require": {
        "mongodb/mongodb": "^2.1"
    },
    "scripts": {
        "test": "phpunit --testdox --no-progress --display-deprecations --display-warnings"
    }
}


```

On lance les tests en faisant `composer test` dans le terminal

## MongoDB

Dans le terminal via **Mongosh** :

`show databases`
`use breco`
`show collections`
`db.createCollection("location");`
`db.location.insertMany( [ { nom: "Tinténiac"}, { nom: "Rennes"}, { nom: "Vignoc"}, { nom: "Brest"} ] );`

Je crée un fichier ***phpinfo.php*** à la racine contenant :

```php
<?php 
    echo extension_loaded("mongodb") ? "✅ mongodb chargée." : "❌ mongodb non chargée.";
    phpinfo(); 
?>
```

On installe l’extension *MongoDB* dans le PHP de **MAMP** :

- on exécute dans le navigateur *phpinfo.php* pour vérifier des infos, on cherche la version PHP -> ici j'ai 8.3.1
- on va installer PECL mais avant cela, on regarde la ligne Thread Safety dans *phpinfo.php*. Si activée, on doit installer la version TS de PECL
- on télécharge le ZIP du [gestionnaire de paquets PECL pour cette version](https://windows.php.net/downloads/pecl/releases/mongodb)
- on extrait le DLL du ZIP qu'on copie dans `C:\MAMP\bin\php\php8.3.1\ext`
- on édite *C:\MAMP\bin\php\php8.3.1\php.ini* et *C:\MAMP\conf\php8.3.1\php.ini*. On ajoute `extension=php_mongodb.dll`
- on relance MAMP et on vérifie qu'on a bien *MongoDB* dans *phpinfo*. On peut aussi faire `php -m`

On installe le client MongoDB PHP via Composer `composer require mongodb/mongodb`
