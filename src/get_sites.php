<?php

header('Content-Type: application/json');

/** **************************************************************
 * Récupération de la liste des lieux dans la base MongoDB
 * 
 *****************************************************************/ 

require "configNoSQL.php";
// Classe Location
require "Location.php";

// On utilise les paquets de Composer
require_once '../vendor/autoload.php'; 

// On se connecte au serveur MongoDB local
$client = new MongoDB\Client("mongodb://localhost:27017");

// On récupère la collection location dans la base breco
$collection = $client->breco->location;

/*  On récupère la requête passée en argument
 *   ?? Coalescence null - équivalent de :
 *    isset($_GET['query']) ? $_GET['query'] : '';
****************************************************/
$query = $_GET['query'] ?? '';

// On recherche avec regex (nom qui commence par... & insensible à la casse)
$filter = [
    'nom' => ['$regex' => '^'.$query, '$options' => 'i']
];

// On limite les résultats pour l'autocomplétion
$options = ['limit' => 5];

$cursor = $collection->find($filter, $options);
$results = [];

foreach ($cursor as $document) {
    $results[] = [
        'nom' => $document['nom'],
        // l'ObjectID est converti en string
        'id' => (string)$document['_id']
    ];
}

// On convertit en JSON
echo json_encode($results);
