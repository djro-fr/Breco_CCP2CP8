<?php

/** **************************************************************
 * Test PHPUnit sur la Classe Location (lieux)
 * 
 *****************************************************************/ 

use PHPUnit\Framework\TestCase;
use Syl\BrecoCda\Location;

class LocationTest extends TestCase {

    private Location $locations;

    // Initialisation
    protected function setUp() : void {
        $this->locations = new Location();
        $this->locations->connect();    
    }
    
    public function testConnect(){               
        $this->assertTrue($this->locations->connect() );
    }

    /*
    public function testGetLocations(){
        //Test de récupération de tous les lieux de la collection
        $result = $this->locations->getLocations();
        
        // Vérifier que le résultat n'est pas false (pas d'erreur)
        $this->assertNotFalse($result);
        
        // Vérifier que c'est bien un array
        $this->assertIsArray($result);
    }

    public function testListLocations(){
        //Test de récupération de tous les lieux de la collection
        //Et affichage de ces lieux
        $result = $this->locations->displayLocations();

        $this->assertTrue($result);       
    }
    */

    public function testGetLocationWithResults(){      
        $this->locations->connect();        
        // On cherche ceux commençant par "Re"
        $result = $this->locations->getFirstLocation("Re");       
        // On teste si le nom est Rennes en ignorant l'ID        
        $this->assertEquals('Rennes', $result['nom']);
    }

    public function testGetLocationWithoutResults(){    
        $this->locations->connect();                
        $result = $this->locations->getFirstLocation("MauvaiseEntrée");                
        $this->assertEmpty($result);  
    }

    public function testGetLocationWithSeveralResults(){      
        $this->locations->connect();        
        // On cherche ceux commençant par "Re"
        $result = $this->locations->getLocations("Re");       
        // On compte le nombre de villes qui commencent par Re          
        $count = count($result);
        //On doit en trouver 2
        $this->assertEquals($count, 2);
    }


}