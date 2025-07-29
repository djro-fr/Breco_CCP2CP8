<?php

/** **************************************************************
 * Tests PHPUnit sur la Classe Breco 
 * 
 *****************************************************************/ 

use PHPUnit\Framework\TestCase;
use Syl\BrecoCcp2cp8\Breco;

class BrecoTest extends TestCase {

    //Le ? permet d'avoir un NULL
    private ?Breco $breco;

    // Initialise un nouvel objet Breco avant chaque test
    protected function setUp() : void {
        $this->breco = new Breco('localhost', 3306, 'root', '', 'breco');
    }
    
    // Nettoyer après les tests, par exemple, fermer la connexion
    protected function tearDown(): void {    
        $this->breco = null;
    }

    // Teste la connexion
    public function testConnection(){               
        $this->assertTrue($this->breco->connect() );
    }

    // Teste si une table existe
    public function testTableExist(){      
        $this->breco->connect();          
        $this->assertTrue($this->breco->tableExist("etape") );
    }

    // Teste si les requetes se font bien
    public function testExecuteSqlQueryWithoutParams(){      
        $this->breco->connect();          
        $result = $this->breco->executeSQLQuery("SELECT * FROM etape");
        $this->assertIsArray($result);
    }    
    public function testExecuteSqlQueryWithParams(){      
        $this->breco->connect();          
        $result = $this->breco->executeSQLQuery("SELECT Nom FROM etape WHERE Nom LIKE :N;",[':N' => '%re']);
        $this->assertIsArray($result);
    }

    // Teste la récupération des noms qui commencent par une string
    public function testGetLocationNamesWithSearch(){      
        $this->breco->connect();        
        // On cherche ceux commençant par "Re"
        $result = $this->breco->getLocationNamesWithSearch("Re");       
        // On compte le nombre de villes qui commencent par Re          
        $count = count($result);
        //On doit en trouver 2
        $this->assertEquals($count, 2);
    }
    
    // Teste la récupération des noms avec un paramètre vide
    public function testGetLocationNamesWithoutSearch()
    {
        $this->breco->connect();  
        $results = $this->breco->getLocationNamesWithSearch();
        $this->assertIsArray($results, "Le résultat doit être un tableau.");
        $this->assertNotEmpty($results, "Le tableau de résultats ne doit pas être vide.");
    }

    // Teste la récupération des noms qui commencent par une string
    public function testGetAllLocations(){      
        $this->breco->connect();        
        // On cherche toutes les étapes
        $result = $this->breco->getAllLocations();               
        $count = count($result);
        //On doit en trouver 6
        $this->assertEquals($count, 6);
    }




}