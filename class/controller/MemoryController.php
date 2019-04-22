<?php
namespace memory;

include_once(dirname(__FILE__) . "/../model/MemoryModel.php");


class MemoryController
{
    private $_memoryModel;

    function __construct($difficulty){
        $this->_memoryModel = new MemoryModel();

        $this->_setDifficulty($difficulty);
    }

    private function _setDifficulty($difficulty) {
        switch ($difficulty) {
            case "easy":
                $this->_memoryModel->setEasyMode();
                break;
            case "normal":
                $this->_memoryModel->setNormalMode();
                break;
            case "hard":
                $this->_memoryModel->setHardMode();
                break;
            default:
                $this->_memoryModel->setNormalMode();
                break;
        }
    }

    public function getTableRows() {
       return $this->_memoryModel->generateFruitRow();
    }

    public function getSongPath() {
        return $this->_memoryModel->songPath;
    }

    public function getNumberOfPairs(){
        return $this->_memoryModel->numberOfPairs;
    }

    public function saveScore(){
        if( (isset($_POST["player_name"]) && $_POST["player_name"] != "") &&
            (isset($_POST["difficulty"]) && $_POST["difficulty"] != "") &&
            (isset($_POST["time"]) && $_POST["time"] != "")){

            $this->_memoryModel->saveScore($_POST["player_name"], $_POST["difficulty"], $_POST["time"]);

            exit;
        }
    }
}