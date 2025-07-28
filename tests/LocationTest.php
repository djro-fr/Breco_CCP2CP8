<?php

/** **************************************************************
 * Test PHPUnit sur la Classe Location (lieux)
 * 
 *****************************************************************/ 

use PHPUnit\Framework\TestCase;
use Syl\BrecoCcp2cp8\Location;

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

    public function testGetLocationPHPWithSeveralResults(){      
        $this->locations->connect();        
        // On cherche ceux commençant par "Re"
        $result = $this->locations->getLocationsPhp("Re");       
        // On compte le nombre de villes qui commencent par Re          
        $count = count($result);
        //On doit en trouver 2
        $this->assertEquals($count, 2);
    }

    public function testGetLocationJSONWithSeveralResults(){      
        $this->locations->connect();         
        // On cherche ceux commençant par "Re"
        $queryMongo = $this->locations->getLocationsQuery("Re");    
        $count = 0;
        foreach ($queryMongo as $document) {
            $count++;
        }        
        // On doit en trouver 2
        $this->assertEquals($count, 2);
    }


}