<?php
    include_once(dirname(__FILE__) . "/class/controller/MemoryController.php");

    use memory\MemoryController;

    $difficulty = (isset($_GET["difficulty"])) ? $_GET["difficulty"] : "normal";
    $difficulty = htmlentities($difficulty);

    $memoryController = new MemoryController($difficulty);
    $memoryController->saveScore(); // Sauvegarde le score si les informations nécessaires sont envoyées.
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Memory</title>

    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Averia+Sans+Libre|Baloo+Bhaina|Righteous|Shanti|Sniglet|Voces" rel="stylesheet">
</head>
<body>
    <div id="container">
        <div id="upper">
            <h1>Memory</h1>
            <audio controls loop="true">
                <source src="<?php echo $memoryController->getSongPath(); ?>" type="audio/ogg">
                <p>Message</p>
            </audio>
        </div>

        <br>

        <div id="difficulty">
            <h3>Difficulté : <?php echo $difficulty; ?></h3>

            <a href="<?php echo $_SERVER["PHP_SELF"] . '?difficulty=easy'; ?>" id="easy">
                <img class="difficulty" src="assets/goldfish.png" alt="un poisson rouge" title="facile">
            </a> &nbsp;-

            <a href="<?php echo $_SERVER["PHP_SELF"] . '?difficulty=normal'; ?>" id="normal">
                <img class="difficulty"  src="assets/humans.png" alt="un homme et une femme" title="normal">
            </a> &nbsp;-

            <a href="<?php echo $_SERVER["PHP_SELF"] . '?difficulty=hard'; ?>" id="hard">
                <img class="difficulty"  src="assets/elephant.png" alt="un éléphant" title="difficile">
            </a>
        </div>

        <table>
            <?php echo $memoryController->getTableRows(); ?>
        </table>
        <span id="chrono"></span>
        <div id="myProgress">
            <div id="myBar"></div>
        </div>
    </div>
    <input id="numberOfPairs" type="hidden" value="<?php echo $memoryController->getNumberOfPairs(); ?>">
    <input id="index-url" type="hidden" value="<?php echo $_SERVER["PHP_SELF"]; ?>">
    <input id="difficulty-value" type="hidden" value="<?php echo $difficulty; ?>">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>
