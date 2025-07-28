<?php

/** **************************************************************
 * Protection contre les injections SQL
 * 
 *****************************************************************/ 
function sanitizeQuery($query){
    if (isset($query)) {        
        $query = trim($_GET['query']);
        // Convertit les caractères spéciaux en entités HTML
        $query = htmlspecialchars($query, ENT_QUOTES, 'UTF-8');
        // Maintenant $query est plus sûr à utiliser
        return $query;
    }
}