<?php
namespace memory;

include_once(dirname(__FILE__) . "/../model/MemoryModel.php");


class MemoryController{
    private $_memoryModel;

    // le constructeur permet d'initialiser des éléments à la création d'une instance
    function __construct($difficulty){
        $this->_memoryModel = new MemoryModel();

        $this->_setDifficulty($difficulty);
    }

    /**
     * Modifie le nombre de paires de cartes à retrouver ainsi que
     * la durée d'une partie en fonction de la difficulté.
     *
     * @param string $difficulty
     */
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

    /**
     * Renvoi le contenu du tableau html contenant les meilleurs scores.
     *
     * @return string;
     */
    public function getHighscoreTableRows() {
       return $this->_memoryModel->getHighScoresRows();
    }

    /**
     * Renvoi le contenu du tableau html contenant les cartes de fruits.
     *
     * @return string;
     */
    public function getCardsTableRows() {
       return $this->_memoryModel->generateFruitRows();
    }

    /**
     * Renvoi le contenu du tableau html contenant les cartes de fruits.
     *
     * @return string;
     */
    public function getSongPath() {
        return $this->_memoryModel->songPath;
    }

    /**
     * Renvoi le nombre de paires nécessaire pour gagner une partie.
     *
     * @return string;
     */
    public function getNumberOfPairs(){
        return $this->_memoryModel->numberOfPairs;
    }

    /**
     * Enregistre les informations et le score du joueur.
     *
     * @return void;
     */
    public function saveScore(){
        // Afin d'éviter l'enregistrement d'infos non valides on s'assure que toutes les variables existent
        // et ne sont pas vides
        if( (isset($_POST["player_name"]) && $_POST["player_name"] != "") &&
            (isset($_POST["difficulty"]) && $_POST["difficulty"] != "") &&
            (isset($_POST["time"]) && $_POST["time"] != "")){

            $this->_memoryModel->saveScore($_POST["player_name"], $_POST["difficulty"], $_POST["time"]);

            exit;
        }
    }
}