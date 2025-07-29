# Étapes du projet

## Principe des Tests Unitaires avec PHPUnit

On installe **Composer** pour pouvoir travailler avec **PHPUnit** (tests unitaires en PHP) :
`composer init`

*Would you like to define your dependencies (require) interactively [yes]?* `no`
*Would you like to define your dev dependencies (require-dev) interactively [yes]?* `yes`
*Search for a package:* `phpunit`
*Enter package # to add, or the complete package name if it is not listed:* `phpunit/phpunit`

On peut vérifier PHPUnit en faisant `./vendor/bin/phpunit`

J'ai installé avec le **namespace** proposé par défaut
Dans le dossier *src* créé à la racine de mon dossier, je crée un 1ère définition de test rapide, un fichier ***Math.php***

```php
<?php

namespace Syl\BrecoCcp2cp8;

class Math{
    public static function double($nombre){
        return $nombre *2 ;
    }

}
```

Je vérifie que mon fichier ***composer.json*** a bien la bonne config pour l'autoload :

```json
{
    "name": "syl/breco_ccp2cp8",
    "description": "Activite8",
    "require-dev": {
        "phpunit/phpunit": "^12.2"
    },
    "autoload": {
        "psr-4": {
            "Syl\\BrecoCcp2cp8\\": "src/"
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
        $this->assertEquals(4, \Syl\BrecoCcp2cp8\Math::double(2) );
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
    "name": "syl/breco_ccp2cp8",
    "description": "Activite8",
    "require-dev": {
        "phpunit/phpunit": "^12.2"
    },
    "autoload": {
        "psr-4": {
            "Syl\\BrecoCcp2cp8\\": "src/"
        }
    },
    "authors": [
        {
            "name": "Sylvain Girault",
            "email": "syl@djro.fr"
        }
    ],
    "scripts": {
        "test": "phpunit --testdox --no-progress --display-deprecations --display-warnings"
    }
}


```

On lance les tests en faisant `composer test` dans le terminal
