<?php
    include_once(dirname(__FILE__) . "/class/controller/MemoryController.php"); // import de la classe controller

    use memory\MemoryController; // déclaration du namespace utilisé

    // si une difficulté est renseignée alors on l'assigne sinon la difficulté "normal" est assignée par défaut
    $difficulty = (isset($_GET["difficulty"])) ? $_GET["difficulty"] : "normal";
    $difficulty = htmlentities($difficulty); // protection contre les insertion de balise html frauduleuses

    $memoryController = new MemoryController($difficulty); // instanciation du controller
    $memoryController->saveScore(); // sauvegarde le score si les informations nécessaires sont envoyées.
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Memory</title>

    <link rel="icon" type="image/x-icon" href="assets/favicon.ico">
    <!-- import du fichier css -->
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <!-- import des fonts -->
    <link href="https://fonts.googleapis.com/css?family=Righteous|Shanti|Voces" rel="stylesheet">
</head>
<body>
    <!-- la modale des meilleurs scores -->
    <div id="myModal" class="modal">
        <!-- le contenu de la modale -->
        <div class="modal-content">
            <span class="close">&times;</span>
            <table>
                <tr>
                    <th>Rang</th>
                    <th>Nom</th>
                    <th>temps</th>
                    <th>difficulté</th>
                </tr>
                <?php echo $memoryController->getHighscoreTableRows(); ?>
            </table>
        </div>
    </div>

    <div id="container">
        <div id="upper">
            <h1>Memory</h1>
            <!-- diffuse une musique d'ambiance, les paramètres "autoplay" et "loop" permettent respectivement
            le lancement automatique ainsi que la répétition en boucle de la musique  -->
            <audio controls autoplay="true" loop="true">
                <source src="<?php echo $memoryController->getSongPath(); ?>" type="audio/ogg">
            </audio>
        </div>

        <br>

        <!-- ces liens redirigent vers la page courante en y ajoutant le paramètre de difficulté -->
        <div id="difficulty">
            <h3>Difficulté : <?php echo $difficulty; ?></h3>

            <a href="<?php echo $_SERVER["PHP_SELF"] . '?difficulty=easy'; ?>" id="easy">
                <img class="difficulty" src="assets/goldfish.png" alt="un poisson rouge" title="facile">
            </a>

            <a href="<?php echo $_SERVER["PHP_SELF"] . '?difficulty=normal'; ?>" id="normal">
                <img class="difficulty"  src="assets/humans.png" alt="un homme et une femme" title="normal">
            </a>

            <a href="<?php echo $_SERVER["PHP_SELF"] . '?difficulty=hard'; ?>" id="hard">
                <img class="difficulty"  src="assets/elephant.png" alt="un éléphant" title="difficile">
            </a>
        </div>

        <!-- le contenu du tableau html est généré dynamiquement depuis le code php appelé dans la balise "table" -->
        <table>
            <?php echo $memoryController->getCardsTableRows(); ?>
        </table>
        <!-- ce span est utilisé par le code javascript pour afficher le chronomètre -->
        <span id="chrono"></span>
        <!-- cette div est utilisée par le code javascript pour afficher la barre de progrès -->
        <div id="myProgress">
            <div id="myBar"></div>
        </div>
    </div>

    <!-- ces informations sont récupérées et utilisées par le code en javascript -->
    <input id="numberOfPairs" type="hidden" value="<?php echo $memoryController->getNumberOfPairs(); ?>">
    <input id="index-url" type="hidden" value="<?php echo $_SERVER["PHP_SELF"]; ?>">
    <input id="difficulty-value" type="hidden" value="<?php echo $difficulty; ?>">

    <!-- import de jquery et du code javascript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>
