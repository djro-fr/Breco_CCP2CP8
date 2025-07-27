<?php

/** **************************************************************
 * Classe Location pour gérer les lieux
 * 
 *****************************************************************/  
namespace Syl\BrecoCda;

use MongoDB\Client;
use MongoDB\Database;
use Exception;

class Location {

    // Config MongoDB
    private $mongoHost;
    private $mongoPort;
    // Config Authentification
    private $user;
    private $pwd;
    private $dbName;
    private $authSource;

    // Client MongoDB
    private Client $client;
    private Database $database;

    /** *******************************************************************************
     *  Constructeur
     */
    public function __construct(
        string $mongoHost = 'localhost', 
        int $mongoPort = 27017, 
        string $user = '', 
        string $pwd = '', 
        string $dbName ='breco',
        string $authSource = 'admin'        
        )
    {
        $this->mongoHost = $mongoHost;
        $this->mongoPort = $mongoPort;
        $this->user = $user;
        $this->pwd = $pwd;
        $this->dbName = $dbName;
        $this->authSource = $authSource;
    }

    /** *******************************************************************************
     * Connection à la base MongoDB
     */
    public function connect(){
        $uri = $this->user && $this->pwd
            ? "mongodb://$this->user:$this->pwd@$this->mongoHost:$this->mongoPort/$this->authSource"
            : "mongodb://$this->mongoHost:$this->mongoPort";

        try {
            // Création du client
            $this->client = new Client($uri);

            // Ping pour vérifier la connexion
            $this->client->selectDatabase('admin')->command(['ping' => 1]);

            // Sélection de la base
            $this->database = $this->client->selectDatabase($this->dbName);
          
            //echo "✅ Connexion MongoDB réussie\n";
            return true;

        } catch (Exception $e) {
            // Gestion d'erreur
            echo "❌ Erreur inattendue : " . $e->getMessage() . "\n";
            return false;
        }


    }
    
    /** *******************************************************************************
     * Récupère tous les lieux de la collection contenant ou pas une chaîne
     * @param string $search
     * @return array|false Retourne un array des lieux ou false en cas d'erreur
     */

    public function getLocations(String $search = ""): array|false
    {
        try {
            // On prend la collection 'location'
            $collection = $this->database->selectCollection('location');
            // On récupère tous les documents de la collection qui commencent par $search.  
            // La variable liste contient un curseur MongoDB
            $liste = $collection->find( ['nom' => ['$regex' => '^'.$search, '$options' => 'i'] ] );        
            //On convertit en tableau PHP
            $locations = $liste->toArray();            
            return $locations;
        } 
        catch (Exception $e) {
            echo "❌ Erreur lors de la récupération : " . $e->getMessage() . "\n";
            return false;
        }
    }

       /** *******************************************************************************
     * Récupère le premier lieu de la collection contenant ou pas une chaîne
     * @param string $search
     * @return array|false Retourne un array du lieu ou false en cas d'erreur
     */

    public function getFirstLocation(String $search = ""): array|null|false
    {
        try {
            // On prend la collection 'location'
            $collection = $this->database->selectCollection('location');
            // On récupère tous les documents de la collection qui commencent par $search.  
            // La variable liste contient un tableau PHP ou null
            $document = $collection->findOne( ['nom' => ['$regex' => '^'.$search, '$options' => 'i'] ] );                               
            // On convertit le BSONDocument en array PHP
            $location = (array) $document;
            return $location;
        } 
        catch (Exception $e) {
            echo "❌ Erreur lors de la récupération : " . $e->getMessage() . "\n";
            return false;
        }
    }


    /** *******************************************************************************
     * Affiche tous les lieux de manière formatée
     * @return bool Retourne true si l'affichage s'est bien passé, false sinon
     */
    public function displayAllLocations(): bool
    {
        $locations = $this->getLocations();
        
        if ($locations === false) {
            return false;
        }

        if (empty($locations)) {
            echo "Aucune location trouvée dans la collection.\n";
            return true;
        }
        
        echo "\n\n======== ALL LOCATIONS ========\n\n";
        echo "Nombre total : " . count($locations) . "\n\n";

        foreach ($locations as $index => $location) {
            echo "--- Location #" . ($index + 1) . " ---\n";
            $this->displaySingleLocation($location);
            echo "\n";
        }
        
        echo "===============================\n\n\n";
        
        return true;
    }


   /** *******************************************************************************
     * Affiche les lieux contenant $search
     * @param string $search
     * @return bool Retourne true si l'affichage s'est bien passé, false sinon
     */
    public function displayLocationsBeginningBy(String $search = ""): bool
    {
        $locations = $this->getLocations($search);
        
        if ($locations === false) {
            return false;
        }

        if (empty($locations)) {
            echo "Aucune location trouvée dans la collection.\n";
            return true;
        }
        
        echo "\n\n======== ALL LOCATIONS ========\n\n";
        echo "Nombre total : " . count($locations) . "\n\n";

        foreach ($locations as $index => $location) {
            echo "--- Location #" . ($index + 1) . " ---\n";
            $this->displaySingleLocation($location);
            echo "\n";
        }
        
        echo "===============================\n\n\n";
        
        return true;
    }


    /** *******************************************************************************
     * Affiche un seul objet location de manière formatée
     * @param object $location
     */
    private function displaySingleLocation($location): void
    {       
        //on parcourt l'objet $location      
        foreach ($location as $key => $value) {
            //si le nom du champ est '_id', on affiche l'ID au format string
            if ($key === '_id') {
                echo "ID : " . (string) $value . "\n";
            } else {
                //sinon on affiche le nom du champ et son contenu formaté en string (JSON ou standard)
                echo ucfirst($key) . " : " . $this->formatValue($value) . "\n";
            }
        }
    }

    /** *******************************************************************************
     * Formate une valeur pour l'affichage (quel que soit le type de $value)
     * @param mixed $value
     * @return string
     */
    private function formatValue($value): string
    {
        // si $value est un tableau ou un objet
        if (is_array($value) || is_object($value)) {
            // on convertit au format string JSON (indentation, caractères unicode, )
            return json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        // sinon on convertit en simple chaîne de caractères
        return (string) $value;
    }












}