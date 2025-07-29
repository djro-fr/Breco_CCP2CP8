<?php

/** **************************************************************
 * Classe Breco pour gérer la base de données
 * 
 *****************************************************************/  
namespace Syl\BrecoCcp2cp8;

use PDO;
use Exception;

require('globalFunctions.php');

class Breco {

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
     *--------------------------------------------------------------------------------*/

    public function __construct(
        string $host = 'localhost', 
        int $port = 3306, 
        string $user = 'root', 
        string $pwd = '', 
        string $dbName ='breco')
    {
        $this->host = $host;
        $this->port = $port;
        $this->user = $user;
        $this->pwd = $pwd;
        $this->dbName = $dbName;
    }
    
    /** *******************************************************************************
    * Connection à la base MySQL
    *
    * @return bool Retourne true si la connexion s'est bien passée, false sinon
    *--------------------------------------------------------------------------------*/
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
            //throw new Exception("Erreur de connexion : " . $e->getMessage());
            echo 'Erreur de connexion : ' . $e->getMessage();
            return false;
        }
    }

    /** *******************************************************************************
    * Vérifie si la table existe dans notre base MySQL (breco)
    *
    * @param string $tableName - le nom de la table à chercher
    * @return bool Retourne true ou false
    *---------------------------------------------------------------------------------*/
    public function tableExist(String $tableName){
        try{
            // On vérifie l'existence de la table dans information_schema pour notre base
            $query = "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '$this->dbName' AND table_name = :table";
            // Préparer et exécuter la requête
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':table', $tableName);
            $stmt->execute();

            // Récupérer le résultat
            $tableExists = $stmt->fetchColumn();

            if ($tableExists) {
                echo "La table '$tableName' existe dans la base de données 'breco'.\n\n";
                return true;
            } else {
                echo "La table '$tableName' n'existe pas dans la base de données 'breco'.\n\n";
                return false;
            }
        } catch (\PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
            error_log('Erreur  : ' . $e->getMessage());
            return false;
        }
    }


    /** *******************************************************************************
    * Prépare et exécute une requête SQL dans notre base MySQL (breco)
    *
    * @param string $query La requête SQL à exécuter.
    * @param array $params Les paramètres à lier à la requête.
    * @return array|false Un tableau associatif des résultats ou false en cas d'erreur.
    *---------------------------------------------------------------------------------*/
    public function executeSQLQuery(string $query, array $params = []) : array|false
    {
        try {
            $stmt = $this->pdo->prepare(trim($query));
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo 'Erreur d\'exécution de la requête : ' . $e->getMessage();
            error_log('Erreur d\'exécution de la requête : ' . $e->getMessage());
            return false;
        }
    }

    /** *******************************************************************************
    * Pour initialiser la base de donnée
    *
    *--------------------------------------------------------------------------------- */
    public function initDb()
    {
        // On vérifie si les tables existent pour les créer
        if (!$this->tableExist("utilisateur")) {
            echo "On crée les tables de la base Breco\n\n";
            $this->executeSQLQuery("CREATE TABLE `breco`.`utilisateur` (
                `Utilisateur_id` INT NOT NULL AUTO_INCREMENT ,
                `Nom` VARCHAR(64) NOT NULL ,
                `Mail` VARCHAR(64) NOT NULL ,
                `Password` VARCHAR(64) NOT NULL , 
                PRIMARY KEY (`Utilisateur_id`)); ");
            $this->executeSQLQuery("CREATE TABLE `breco`.`etape` (
                 `Etape_id` INT NOT NULL AUTO_INCREMENT ,
                 `Nom` VARCHAR(64) NOT NULL,
                 `GPS_lat` DECIMAL(10,8) NULL,
                 `GPS_long` DECIMAL(11,8) NULL,
                 PRIMARY KEY (`Etape_id`));");
            $this->executeSQLQuery("CREATE TABLE `breco`.`trajet` (
                `Trajet_id` INT NOT NULL AUTO_INCREMENT ,
                `Depart_id` INT NOT NULL , `Arrivee_nom` VARCHAR(64) NOT NULL ,
                `Horaire_depart` TIME NOT NULL ,
                `Horaire_arrivee` TIME NOT NULL ,
                `Lundi` BIT NOT NULL ,
                `Mardi` BIT NOT NULL ,
                `Mercredi` BIT NOT NULL ,
                `Jeudi` BIT NOT NULL ,
                `Vendredi` BIT NOT NULL ,
                `Samedi` BIT NOT NULL ,
                `Dimanche` BIT NOT NULL ,
                `Utilisateur_Utilisateur_id` INT NOT NULL , 
                PRIMARY KEY (`Trajet_id`),
                FOREIGN KEY (`Utilisateur_Utilisateur_id`) REFERENCES `utilisateur`(`Utilisateur_id`),
                FOREIGN KEY (`Depart_id`) REFERENCES `etape`(`Etape_id`));");
            $this->executeSQLQuery("CREATE TABLE `breco`.`etape_trajet` (
                `Etape_trajet_id` INT NOT NULL AUTO_INCREMENT ,
                `Trajet_id` INT NOT NULL ,
                `Etape_id` INT NOT NULL ,
                PRIMARY KEY (`Etape_trajet_id`),
                FOREIGN KEY (`Etape_id`) REFERENCES `etape`(`Etape_id`),
                FOREIGN KEY (`Trajet_id`) REFERENCES `trajet`(`Trajet_id`));");
        }
    }



    /** **********************************************************************************************************
    *  Récupère les 5 premiers lieux de la table "etape" contenant ou pas une chaîne et renvoie un tableau PHP
    *
    *  @param string $search
    *  @return array|false Retourne un array des lieux ou false en cas d'erreur
    *--------------------------------------------------------------------------------------------------------*/
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
            //error_log("❌ Erreur lors de la récupération : " . $e->getMessage());  
            echo '❌ Erreur lors de la récupération : ' . $e->getMessage();
            return false;
        }
    }

    
    /** *******************************************************************************************************
    *  Récupère les 5 premiers lieux de la table "etape" contenant ou pas une chaîne et renvoie un tableau PHP
    *
    *  @param string $search
    *  @return array|false Retourne un array des lieux ou false en cas d'erreur
    *--------------------------------------------------------------------------------------------------------*/
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
            //error_log("❌ Erreur lors de la récupération : " . $e->getMessage());  
            echo '❌ Erreur lors de la récupération : ' . $e->getMessage();
            return false;
        }
    }





}