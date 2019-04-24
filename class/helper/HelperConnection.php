<?php


namespace helper;

use PDO;
use PDOException;

class HelperConnection{
    /**
     * Retourne une instance pdo de connexion à la base de donnée
     *
     * @return PDO|false
     */
    public function getPdo(){
        $dbName = "3BDBktRgQ7";
        $username = "3BDBktRgQ7";
        $dbHost = "remotemysql.com";
        $password = "Z5xhTuSJ06";
        $dns = "mysql:host=" . $dbHost . ";dbname=" . $dbName;

        try{
            $pdo = new PDO($dns, $username ,  $password);
            // Permet l'affichage de message en cas d'erreur MySQL
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e){ // On attrape l'exception en cas de problème de connexion et on affiche le msg d'erreur
            print "Error !: " . $e->getMessage();
            return false;
        }

        return $pdo;
    }
}