<?php


namespace helper;

use PDO;

class HelperConnection{
    /**
     * Returns an PDO instance of a connexion to the database given in parameter
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
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(\PDOException $e){
            print "Error !: " . $e->getMessage();
            return false;
        }

        return $pdo;
    }
}