<?php

/**********************************************
 *  Données STUB Exemple/Test
 * 
 * 
 *-------------------------------------------*/

include "configSQL.php";
include "Breco.php";

function exampleData()
{

    // Get config variables    
    global $mySQL_host, $mySQL_port, $mySQL_dbName, $mySQL_user, $mySQL_pwd;

    // Create object for the database
    $breco = new Syl\BrecoCcp2cp8\Breco($mySQL_host, $mySQL_port, $mySQL_user, $mySQL_pwd, $mySQL_dbName);
    $breco->connect();

    $breco->executeSQLQuery("
        INSERT INTO `etape` (`Etape_id`, `Nom`, `GPS_lat`, `GPS_long`) VALUES
        (1, 'Tinteniac', 48.32469033, -1.85252367),
        (2, 'Rennes', 48.15427533, -1.62583033),
        (3, 'Vignoc', 48.25107536, -1.77288642),
        (4, 'Reignat', 45.75137424, 3.35454242),
        (5, 'Vildé Guingalan', 48.43858255, -2.14443039),
        (6, 'Riec-Sur-Belon', 47.88658260, -3.70053248);
    ");    

    $breco->executeSQLQuery("
        INSERT INTO `etape_trajet` (`Etape_trajet_id`, `Trajet_id`, `Etape_id`) VALUES
        (1, 1, 3);
    ");    
    
    $breco->executeSQLQuery("
        INSERT INTO `trajet` (`Trajet_id`, `Arrivee_nom`, `Horaire_depart`, `Horaire_arrivee`, `Lundi`, `Mardi`, `Mercredi`, `Jeudi`, `Vendredi`, `Samedi`, `Dimanche`, `Utilisateur_Utilisateur_id`, `Depart_id`) VALUES
        (1, 'Rennes', '07:45:00', '08:10:00', b'1', b'1', b'1', b'1', b'1', b'0', b'0', 1, 1);
    ");    
    
    $breco->executeSQLQuery("
        INSERT INTO `utilisateur` (`Utilisateur_id`, `Nom`, `Mail`, `Password`) VALUES
        (1, 'Thomas', 'tom@le-mail.com', '1234$678'),
        (2, 'Alice', 'alice@le-mail.com', '1&2&3&4&');    
    ");    

}

exampleData();