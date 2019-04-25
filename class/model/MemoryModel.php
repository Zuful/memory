<?php

namespace memory;

include_once dirname(__FILE__) . "/../helper/HelperConnection.php";
include_once dirname(__FILE__) . "/../type/Score.php";

use helper\HelperConnection;
use type\Score;
use PDO;

class MemoryModel
{
    private $_helperConnection;
    private $_gameFruits;
    private $_normalFruits;
    private $_hardFruits;
    private $_numberOfROws;
    private $_numberOfFruitsPerRows;
    public $songPath;
    public $numberOfPairs;

    public function __construct(){
        $this->_helperConnection = new HelperConnection();
        $this->_gameFruits = array( // liste de base, une liste partielle des fruits
            "red-apple", "banana", "orange", "green-lemon", "cranberry", "orange-apricot", "yellow-lemon", "strawberry",
            "green-apple", "peach"
        );
        // pour le mode normal on ajoute 4 fruits à la liste de base en fusionnant les deux arrays
        $this->_normalFruits = array_merge($this->_gameFruits, array("grapes", "watermelon", 'purple-apricot', "pear"));
        // pour le mode difficile on ajoute 4 fruits à la liste du mode normal en fusionnant les deux arrays
        $this->_hardFruits = array_merge($this->_normalFruits, array("red-cherry", "raspberry", "mango", "yellow-cherry"));

        $this->_numberOfROws = 4; // Le nombre de rangées à afficher
    }

    public function setEasyMode() {
        $this->songPath = "audio/final-fantasy-VIII-breezy.ogg"; // chemin du fichier audio du mode facile
        $this->numberOfPairs = count($this->_gameFruits); // le nombre de paires à trouver en mode facile
        $this->_numberOfFruitsPerRows = 5;// le nombre de fruits à afficher par rangées en mode facile

        // on double l'array afin de créer la paire de fruits
        $this->_gameFruits = array_merge($this->_gameFruits, $this->_gameFruits);
    }

    public function setNormalMode() {
        $this->songPath = "audio/ffx -soundtrack-omega-ruins-theme.ogg";// chemin du fichier audio du mode normal
        $this->numberOfPairs = count($this->_normalFruits);// le nombre de paires à trouver en mode normal
        $this->_numberOfFruitsPerRows = 7; // le nombre de fruits à afficher par rangées en mode normal

        // on double l'array afin de créer la paire de fruits
        $this->_gameFruits = array_merge($this->_normalFruits, $this->_normalFruits);
    }

    /**
     * Initialise les propriétés du mode difficile
     *
     * @return void
     */
    public function setHardMode() {
        $this->songPath = "audio/metal-gear-solid-2-Twilight-Sniping.ogg";// chemin du fichier audio du mode difficile
        $this->numberOfPairs = count($this->_hardFruits);// le nombre de paires à trouver en mode difficile
        $this->_numberOfFruitsPerRows = 9;// le nombre de fruits à afficher par rangées en mode difficile

        // on double l'array afin de créer la paire de fruits
        $this->_gameFruits = array_merge($this->_hardFruits, $this->_hardFruits);
    }

    /**
     * Enregistre le score du joueur en base de donnés
     *
     * @param string $playerName
     * @param string $difficulty
     * @param string $finishingTime
     *
     * @return bool
     */
    public function saveScore($playerName, $difficulty, $finishingTime){
        // requête préparée d'insertion du score, les valeurs sont présente sous forme de placeholder
        $sql = "INSERT INTO ranking (player_name, difficulty, `time`) VALUES (:player_name, :difficulty, :time)";
        $pdo = $this->_helperConnection->getPdo(); // récupération d'une instance de connection PDO
        $req = $pdo->prepare($sql);

        // exécution de la requête préparée avec mapping entre les placeholders et les valeurs
        return $req->execute(array(":player_name" => $playerName, ":difficulty" => $difficulty, ":time" => $finishingTime));
    }

    /**
     * Retourne le contenu html de la liste des meilleurs scores
     *
     * @return string
     */
    public function getHighScoresRows(){
        $rank = 1;
        $listContent = "";
        $highScores = $this->_getHighScores();

        foreach ($highScores as $oneScore){
            $listContent .= "<tr>
                                <td>" . $rank . "</td>
                                <td>" . htmlentities($oneScore->playerName). "</td>
                                <td>" . htmlentities($oneScore->completionTime) . "</td>
                                <td>" . htmlentities($oneScore->difficulty) . "</td>
                             </tr>";

            $rank++;
        }

        return $listContent;
    }

    /**
     * Retourne la collection des 10 meilleurs scores
     *
     * @return Score[]
     */
    private function _getHighScores(){
        $highScores = array();
        $sql = "SELECT * FROM ranking ORDER BY `time` ASC LIMIT 10";
        $pdo = $this->_helperConnection->getPdo();// récupération d'une instance de connection PDO
        $req = $pdo->query($sql);
        $results = $req->fetchAll(PDO::FETCH_ASSOC);// récupération des résultats de la requête

        foreach ($results as $oneScore){
            // Instanciation et hydratation de l'objet Score
            $scoreObs = new Score();
            $scoreObs->playerName = $oneScore["player_name"];
            $scoreObs->difficulty = $oneScore["difficulty"];
            $scoreObs->completionTime = $oneScore["time"];

            $highScores[] = $scoreObs; // ajout de l'objet score à la collection
        }

        return $highScores;
    }

    /**
     * Retourne le contenu du tableau html contenant les cartes fruits et les cartes retournés
     *
     * @return string
     */
    public function generateFruitRows() {

        $tableContent = "";// initialisation du contenu avec une chaine vide à laquelle on concaténera le contenu

        // cette boucle tourne tant que l'itération n'est pas égale au nombre de rangées
        for ($i = 0; $i < $this->_numberOfROws; $i++) {
            // création d'une rangée en appelant la méthode de génération de cellule pour une rangée
            $tableContent .= "<tr>" . $this->generateFruitCell() . "</tr>";
        }

        return $tableContent;
    }

    /**
     * Retourne le contenu d'une rangée du tableau html contenant les cartes fruits et les cartes retournés
     *
     * @return string
     */
    public function generateFruitCell() {

        $rowCells = ""; // initialisation du contenu avec une chaine vide à laquelle on concaténera le contenu

        // cette boucle tourne tant que l'itération n'est pas égale au nombre de fruits par rangées
        for ($i = 0; $i < $this->_numberOfFruitsPerRows; $i++) {
            if(!empty($this->_gameFruits)){ // on s'assure que la propriété contenant les fruit n'est pas vide
                $imgFruitPath = "assets/empty.gif"; // chemin de l'image servant de placeholder
                $imgQuestionMark = "assets/question-mark.png"; // chemin de l'image masquant les fruits

                $fruitKey = array_rand($this->_gameFruits, 1); // on récupère un fruit de façon aléatoire

                // concaténation du contenu html représentant une cellule de la rangée du tableau
                // 2 images : celle du fruit et celle qui masque le fruit :
                // si l'une est visible l'autre sera cachée côté javascript
                $rowCells .= "<td>
                                <img    alt='" . $this->_gameFruits[$fruitKey] . "' 
                                        id='" . $fruitKey . "'
                                        class='" . $this->_gameFruits[$fruitKey] . " fruit' 
                                        src='". $imgFruitPath ."' width='1' height='1' />
                                        
                                <img    class='" . $fruitKey . " question-mark' 
                                        src='". $imgQuestionMark ."'/>
                              </td>";

                // une fois utilisé le fruit est retiré de la liste afin d'éviter d'être à nouveau choisit dans une
                // prochaine itération
                unset($this->_gameFruits[$fruitKey]);
            }

        }

        return $rowCells;
    }

}
