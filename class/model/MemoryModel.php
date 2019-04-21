<?php

namespace memory;

use type\Score;

class MemoryModel
{
    private $_gameFruits;
    private $_normalFruits;
    private $_hardFruits;
    private $_numberOfROws;
    private $_numberOfCells;
    public $songPath;

    public function __construct(){
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
        $this->_gameFruits = array_merge($this->_gameFruits, $this->_gameFruits);

        $this->_numberOfCells = 5;
    }

    public function setNormalMode() {
        $this->songPath = "audio/ffx -soundtrack-omega-ruins-theme.ogg";
        $this->_gameFruits = array_merge($this->_normalFruits, $this->_normalFruits);

        $this->_numberOfCells = 7;
    }

    public function setHardMode() {
        $this->songPath = "audio/metal-gear-solid-2-Twilight-Sniping.ogg";
        $this->_gameFruits = array_merge($this->_hardFruits, $this->_hardFruits);

        $this->_numberOfCells = 9;
    }

    /**
     * @param string $playerName
     * @param string $finishingTime
     * @return bool
     */
    public function saveTime($playerName, $finishingTime){
        $isSaved = false;

        return $isSaved;
    }

    /**
     * @return Score[]
     */
    public function displayHighScores(){
        $highScores = array();

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