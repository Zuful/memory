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
}