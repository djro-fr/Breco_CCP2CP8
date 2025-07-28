<?php

header('Content-Type: application/json');

/** **************************************************************
 * Récupération de la liste des lieux dans la base MongoDB
 * 
 *****************************************************************/ 
require "globalFunctions.php";
require "configNoSQL.php";
// Classe Location
require "Location.php";

// On utilise les paquets de Composer
require_once '../vendor/autoload.php'; 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$q = sanitizeQuery($_GET['query']) ?? '';



// Créer une instance de la classe Location
$locManager = new Syl\BrecoCcp2cp8\Location();

// Se connecter à la base de données
if ($locManager->connect()) {    

    // Afficher les locations selon la requête
    $queryMongo = $locManager->getLocationsQuery($q);  
    $results = [];
    foreach ($queryMongo as $document) {
        $results[] = [
            'nom' => $document['nom'],
            // l'ObjectID est converti en string
            'id' => (string)$document['_id']
        ];
    }
    echo json_encode($results);

} else {    
    error_log("❌ Échec de la connexion à MongoDB");
    echo json_encode(['error' => 'Échec de la connexion à MongoDB.']);
}


