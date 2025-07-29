<?php

/** **************************************************************
 * Tests PHPUnit sur la Classe Location (lieux)
 * 
 *****************************************************************/ 

use PHPUnit\Framework\TestCase;
use Syl\BrecoCcp2cp8\Location;

class LocationTest extends TestCase {

    //Le ? permet d'avoir un NULL
    private ?Location $location;

    // Initialise un nouvel objet Location avant chaque test
    protected function setUp() : void {
        $this->location = new Location('localhost', 3306, 'root', '', 'web_project');
    }
    
    // Nettoyer après les tests, par exemple, fermer la connexion
    protected function tearDown(): void {    
        $this->location = null;
    }

    // Teste la connexion
    public function testConnection(){               
        $this->assertTrue($this->location->connect() );
    }

    // Teste la récupération des noms qui commencent par une string
    public function testGetLocationNamesWithSearch(){      
        $this->location->connect();        
        // On cherche ceux commençant par "Re"
        $result = $this->location->getLocationNamesWithSearch("Re");       
        // On compte le nombre de villes qui commencent par Re          
        $count = count($result);
        //On doit en trouver 2
        $this->assertEquals($count, 2);
    }
    
    // Teste la récupération des noms avec un paramètre vide
    public function testGetLocationNamesWithoutSearch()
    {
        $this->location->connect();  
        $results = $this->location->getLocationNamesWithSearch();
        $this->assertIsArray($results, "Le résultat doit être un tableau.");
        $this->assertNotEmpty($results, "Le tableau de résultats ne doit pas être vide.");
    }

    // Teste la récupération des noms qui commencent par une string
    public function testGetAllLocations(){      
        $this->location->connect();        
        // On cherche toutes les étapes
        $result = $this->location->getAllLocations();               
        $count = count($result);
        //On doit en trouver 6
        $this->assertEquals($count, 6);
    }




}