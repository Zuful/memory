<?php
    include_once(dirname(__FILE__) . "/class/model/MemoryModel.php");

    use memory\MemoryModel;

    $memoryModel = new MemoryModel(4, 9);
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
    <table style="width:100%">
        <?php echo $memoryModel->generateFruitRow(); ?>
    </table>
</body>
</html>
