<?php

namespace memory;

use type\Score;

class MemoryModel
{
    private $_allFruits;
    private $_numberOfROws;
    private $_numberOfCells;

    public function __construct($numberOfRows, $numberOfCells){
        $this->newGame($numberOfRows, $numberOfCells);
    }

    public function newGame($numberOfRows, $numberOfCells){
        $this->_allFruits = array(
            "red-apple", "banana", "orange", "green-lemon", "cranberry", "orange-apricot", "yellow-lemon", "strawberry",
            "green-apple", "peach", "grapes", "watermelon", 'purple-apricot', "pear", "red-cherry", "raspberry", "mango",
            "yellow-cherry",
            "red-apple", "banana", "orange", "green-lemon", "cranberry", "orange-apricot", "yellow-lemon", "strawberry",
            "green-apple", "peach", "grapes", "watermelon", 'purple-apricot', "pear", "red-cherry", "raspberry", "mango",
            "yellow-cherry"
        );

        $this->_numberOfCells = $numberOfCells;
        $this->_numberOfROws = $numberOfRows;
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
            if(!empty($this->_allFruits)){
                $imgPath = "http://localhost/memory/assets/empty.gif";
                $fruitKey = array_rand($this->_allFruits, 1);
                $rowCells .= "<td>
                                <img alt='" . $this->_allFruits[$fruitKey] . "' class='" . $this->_allFruits[$fruitKey] . "' src='". $imgPath ."' width='1' height='1' />
                              </td>";

                unset($this->_allFruits[$fruitKey]);
            }

        }

        return $rowCells;
    }

}