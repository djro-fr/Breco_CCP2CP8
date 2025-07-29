<?php

/**********************************************
 *  Initialisation Base de données Breco
 * 
 *-------------------------------------------*/

include "configSQL.php";
include "Breco.php";

function initDatabase()
{

    // Get config variables    
    global $mySQL_host, $mySQL_port, $mySQL_dbName, $mySQL_user, $mySQL_pwd;

    // Create object for the database
    $breco = new Syl\BrecoCcp2cp8\Breco($mySQL_host, $mySQL_port, $mySQL_user, $mySQL_pwd, $mySQL_dbName);
    $breco->connect();
    $breco->initDb();
}

initDatabase();