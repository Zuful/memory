<?php

namespace memory;

include_once dirname(__FILE__) . "/../helper/HelperConnection.php";

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
    private $_numberOfCells;
    public $songPath;
    public $numberOfPairs;

    public function __construct(){
        $this->_helperConnection = new HelperConnection();
        $this->_gameFruits = array(
            "red-apple", "banana", "orange", "green-lemon", "cranberry", "orange-apricot", "yellow-lemon", "strawberry",
            "green-apple", "peach"
        );

        $this->_normalFruits = array_merge($this->_gameFruits, array("grapes", "watermelon", 'purple-apricot', "pear"));
        $this->_hardFruits = array_merge($this->_normalFruits, array("red-cherry", "raspberry", "mango", "yellow-cherry"));

        $this->_numberOfROws = 4;
    }

    public function setEasyMode() {
        $this->songPath = "audio/final-fantasy-VIII-breezy.ogg";
        $this->numberOfPairs = count($this->_gameFruits);
        $this->_numberOfCells = 5;

        $this->_gameFruits = array_merge($this->_gameFruits, $this->_gameFruits);
    }

    public function setNormalMode() {
        $this->songPath = "audio/ffx -soundtrack-omega-ruins-theme.ogg";
        $this->numberOfPairs = count($this->_normalFruits);
        $this->_numberOfCells = 7;

        $this->_gameFruits = array_merge($this->_normalFruits, $this->_normalFruits);

    }

    public function setHardMode() {
        $this->songPath = "audio/metal-gear-solid-2-Twilight-Sniping.ogg";
        $this->numberOfPairs = count($this->_hardFruits);
        $this->_numberOfCells = 9;

        $this->_gameFruits = array_merge($this->_hardFruits, $this->_hardFruits);
    }

    /**
     * @param string $playerName
     * @param string $finishingTime
     * @return bool
     */
    public function saveScore($playerName, $diffiulty, $finishingTime){
        $sql = "INSERT INTO ranking (player_name, difficulty, `time`) VALUES (:player_name, :difficulty, :time)";
        $pdo = $this->_helperConnection->getPdo();
        $req = $pdo->prepare($sql);

        return $req->execute(array(":player_name" => $playerName, ":difficulty" => $diffiulty, ":time" => $finishingTime));
    }

    /**
     * @return Score[]
     */
    public function getHighScores(){
        $highScores = array();
        $sql = "SELECT * FROM ranking ORDER BY `time` DESC LIMIT 10";
        $pdo = $this->_helperConnection->getPdo();
        $req = $pdo->query($sql);
        $res = $req->fetchAll(PDO::FETCH_ASSOC);

        foreach ($res as $oneScore){
            $scoreObs = new Score();
            $scoreObs->playerName = $oneScore["player_name"];
            $scoreObs->difficulty = $oneScore["difficulty"];
            $scoreObs->completionTime = $oneScore["time"];

            $highScores[] = $scoreObs;
        }

        return $highScores;
    }

    /**
     * @return string
     */
    public function generateFruitRow() {

        $tableContent = "";

        for ($i = 0; $i < $this->_numberOfROws; $i++) {
            $tableContent .= "<tr>" . $this->generateFruitCell() . "</tr>";
        }

        return $tableContent;
    }

    /**
     * @return string
     */
    public function generateFruitCell() {

        $rowCells = "";

        for ($i = 0; $i < $this->_numberOfCells; $i++) {
            if(!empty($this->_gameFruits)){
                $imgFruitPath = "assets/empty.gif";
                $imgQuestionMark = "assets/question-mark.png";

                $fruitKey = array_rand($this->_gameFruits, 1);
                $rowCells .= "<td>
                                <img    alt='" . $this->_gameFruits[$fruitKey] . "' 
                                        id='" . $fruitKey . "'
                                        class='" . $this->_gameFruits[$fruitKey] . " fruit' 
                                        src='". $imgFruitPath ."' width='1' height='1' />
                                        
                                <img    class='" . $fruitKey . " question-mark' 
                                        src='". $imgQuestionMark ."'/>
                              </td>";

                unset($this->_gameFruits[$fruitKey]);
            }

        }

        return $rowCells;
    }

}
