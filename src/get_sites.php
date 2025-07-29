<?php

header('Content-Type: application/json');

/** **************************************************************
 * Récupération de la liste des lieux dans la base MySQL
 * 
 *****************************************************************/ 
require "configSQL.php";
// Classe Breco
require "Breco.php";

// On utilise les paquets de Composer
require_once '../vendor/autoload.php'; 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// on nettoie la query pour empêcher les injections
$q = sanitizeQuery($_GET['query']) ?? '';

// Créer une instance de la classe Breco à partir des infos de config
$BrecoManager = new Syl\BrecoCcp2cp8\Breco($mySQL_host, $mySQL_port, $mySQL_user, $mySQL_pwd, $mySQL_dbName);

// Se connecter à la base de données
if ($BrecoManager->connect()) {    
    // Afficher les lieux Breco selon la requête
    $results = $BrecoManager->getLocationNamesWithSearch($q);  
    echo json_encode($results);
} else {    
    error_log("❌ Échec de la connexion à MySQL");
    echo json_encode(['error' => 'Échec de la connexion à MySQL.']);
}


