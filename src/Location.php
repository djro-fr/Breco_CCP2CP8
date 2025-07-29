<?php

/** **************************************************************
 * Classe Location pour gérer les lieux
 * 
 *****************************************************************/  
namespace Syl\BrecoCcp2cp8;

use PDO;
use Exception;

require('globalFunctions.php');

class Location {

    // Config MySQL
    private $host;
    private $port;
    // Config Authentification
    private $user;
    private $pwd;
    private $dbName;
    private $pdo;

    /** *******************************************************************************
     *  Constructeur
     *
     ******************************************************************************* */

    public function __construct(
        string $host = 'localhost', 
        int $port = 3306, 
        string $user = 'root', 
        string $pwd = '', 
        string $dbName ='web_project')
    {
        $this->host = $host;
        $this->port = $port;
        $this->user = $user;
        $this->pwd = $pwd;
        $this->dbName = $dbName;
    }
    
    /** *******************************************************************************
    * Connection à la base MySQL
    * @return bool Retourne true si la connexion s'est bien passée, false sinon
    **********************************************************************************/
    public function connect():bool {        
        // Data Source Name
        $dsn = "mysql:host=$this->host;dbname=$this->dbName";

        try {
            // Connexion à la base de données
            $this->pdo = new PDO($dsn, $this->user, $this->pwd);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return true;

        } catch (\PDOException $e) {
            // Gestion d'erreur
            throw new Exception("Erreur de connexion : " . $e->getMessage());
            return false;
        }
    }

    /** *******************************************************************************
    *  Récupère les 5 premiers lieux de la table "etape" contenant ou pas une chaîne et renvoie un tableau PHP
    *  @param string $search
    *  @return array|false Retourne un array des lieux ou false en cas d'erreur
    */
    public function getLocationNamesWithSearch(String $search = ""): array|false
    {
        try {
            // on enlève les htmlspecialchars
            $search = sanitizeQuery($search);
            // Préparation de la requête SQL
            // On prend les 5 premiers noms de lieux, issus de la table "etape"
            if ($search === "") {
                $sql = "SELECT Nom FROM etape LIMIT 5;";
                $stmt = $this->pdo->prepare($sql);
            } else {
                $sql = "SELECT Nom FROM etape WHERE Nom LIKE :search LIMIT 5;";
                $stmt = $this->pdo->prepare($sql);
                // Utilisation de bindValue pour lier le paramètre
                $stmt->bindValue(':search', $search . '%', PDO::PARAM_STR);
            }         

            // Exécution de la requête
            $stmt->execute();

            // Récupération des résultats
            $fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $results = [];
            // Affichage des résultats
            foreach ($fetch as $row) {
                $results[] = $row['Nom'];
            }
            //On retourne le tableau PHP                   
            return $results;
        } 
        catch (Exception $e) {
            error_log("❌ Erreur lors de la récupération : " . $e->getMessage());  
            return false;
        }
    }

    
    /** *******************************************************************************
    *  Récupère les 5 premiers lieux de la table "etape" contenant ou pas une chaîne et renvoie un tableau PHP
    *  @param string $search
    *  @return array|false Retourne un array des lieux ou false en cas d'erreur
    */
    public function getAllLocations(): array|false
    {
        try {
            // Préparation de la requête SQL
            // On prend toutes les infos des lieux, issus de la table "etape"
            $sql = "SELECT * FROM etape;";
            $stmt = $this->pdo->prepare($sql);                     

            // Exécution de la requête
            $stmt->execute();

            // Récupération des résultats
            $fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $results = [];
            // Affichage des résultats
            foreach ($fetch as $row) {
                $results[] = [
                    'Nom' => $row['Nom'],
                    'GPS_lat' => $row['GPS_lat'],
                    'GPS_long' => $row['GPS_long']
                ];
            }
            //On retourne le tableau PHP                   
            return $results;
        } 
        catch (Exception $e) {
            error_log("❌ Erreur lors de la récupération : " . $e->getMessage());  
            return false;
        }
    }





}