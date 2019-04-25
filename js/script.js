$(document).ready(function(){
    const url = $('#index-url').val();
    const difficulty = $('#difficulty-value').val();
    const numberOfPairsToReach = parseInt($('#numberOfPairs').val());
    let lastCards = [];
    let timeElapsed;
    let currentNumberOfPairs = 0;
    let previousStatus = '';
    let currentStatus = '';

    $('.fruit').hide();
    $('.question-mark').show();

    triggerHighscoreModal();
    progressBarMove();

    // se produit lorsque l'on clique sur une carte de fruit masquée
    $('img').click((e) => {
        const classValue = e.target.attributes[0].value; // récupère la classe de l'image masquant le fruit
        const classArray = classValue.split(' '); // sépare les différentes classes et les place dans un array
        const fruitId = classArray[0]; // la première classe représente l'id du fruit masqué
        const fruitImg = $('#' + fruitId); // on récupère le fruit masqué grâce à son id
        const questionMarkImg = $('.' + fruitId); // on récupère la carte masquant le fruit grâce à sa classe

        if(!isNaN(fruitId)){
            questionMarkImg.hide(); // la carte masquant le fruit est cachée
            fruitImg.show(); // la carte du fruit est affichée

            // array contenant l'id carte courrante et de la carte tirée précédement s'il s'agit du second tour
            lastCards.push(fruitId);

            currentStatus = fruitImg[0].attributes[0].value; // récupère le nom du fruit de la carte courante

            // 1.vérification que le fruit courant est différent du fruit précédent
            // 2.vérification qu'il y ait bien eu un fruit précédent et que nous somme donc au second tour
            if((currentStatus !== previousStatus) && (previousStatus !== '')){
                // delta de 500 millisecondes afin que le joueur puisse mémoriser le fruit avant de le masquer
                setTimeout(() => {
                    // on remet les deux dernières cartes tirées dans leur état initial
                    $('#' + lastCards[0]).hide();// masque la carte du fruit pour la première carte tirée
                    $('.' + lastCards[0]).show();// affiche la carte qui masque pour la première carte tirée

                    $('#' + lastCards[1]).hide();// masque la carte du fruit pour la seconde carte tirée
                    $('.' + lastCards[1]).show();// affiche la carte qui masque pour la seconde carte tirée

                    // on réinitialise l'array pour ne pas intéragir sur les mauvaises cartes aux prochains tours
                    lastCards = [];
                    // on réinitialise les status pour ne pas comparer les mauvaises cartes aux prochains tours
                    resetStatus();
                }, 500);

            }
            // véficicaton que le fruit courant est strictement égal au fruit précédent, une paire à été découverte
            else if (currentStatus === previousStatus) {
                currentNumberOfPairs++; // incrémentation du nombre de paires trouvés
                // on réinitialise l'array pour ne pas intéragir sur les mauvaises cartes aux prochains tours
                lastCards = [];

                // delta de 250 millisecondes afin que la dernière carte ait le temps de s'afficher
                setTimeout(() => {
                    isGameFinished(); // on vérifie si la paire qui vient d'être découverte était la dernière
                }, 250);
                // on réinitialise les status pour ne pas comparer les mauvaises cartes aux prochains tours
                resetStatus();
            }

            // initialise la carte courante comme étant la précédente en vue du prochain tour
            previousStatus = currentStatus;
        }
    });

    function resetStatus(){
        currentStatus = '';
        previousStatus = '';
    }

    function isGameFinished() {
        // vérifie si le nombre de paires trouvées est égal au nombre de paires total
        if(currentNumberOfPairs === numberOfPairsToReach){
            // récupération du nom du joueur
            const playerName = prompt('Vous avez gagné! Entrez votre nom pour le classement.');
            saveScore(playerName); // enregistrement du score en base de donnés
            alert(playerName + ' votre score a été enregistré.');

            window.location.href = url + '?difficulty=' + difficulty; // redirection en vue d'une nouvelle partie
        }
    }

    function saveScore(player_name){
        // création d'un objet contenant les informations à sauvegarder
        const data = {
            player_name: player_name,
            difficulty: difficulty,
            time: timeElapsed
        };

        $.post( url, data); // envoi d'une requête http POST déclanchant la sauvegarde du score
    }

    function progressBarMove(percentage) {
        const bar = $("#myBar");
        bar.css('width', percentage + '%');
    }

    function chronometer(givenTimeInMilliseconds){
        // Initialise la date et l'heure du lancement du chronomètre
        let startDate = new Date().getTime();

        // Mise à jour du chronomètre toute les secondes
        let x = setInterval(function() {

            // Récupère la date et l'heure courante
            let now = new Date().getTime();

            // Le lapse de temps écoulé depuis le lancement du chronomètre
            let distance = now - startDate;

            // Calcul des minutes et des secondes qui s'écoulent
            let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            minutes = (minutes < 10) ? '0' + minutes : minutes;
            let seconds = Math.floor((distance % (1000 * 60)) / 1000);
            seconds = (seconds < 10) ? '0' + seconds : seconds;

            timeElapsed = minutes + ":" + seconds;
            // Affiche le résultat dans la balise ayant l'id 'chrono'
            $("#chrono").text(timeElapsed);

            // on calcul le pourcentage de temps écoulé par rapport au total du temps accordé
            let percentage = (100 * distance) / givenTimeInMilliseconds;
            // on augmente la barre de progression en fonction du pourcentage de temps écoulé
            progressBarMove(percentage);

            // Le temps imparti est écoulé on informe que la partie est perdue et on en relance une autre.
            if (distance >= givenTimeInMilliseconds) {
                clearInterval(x);// on arrête le set interval
                alert('Temps écoulé, vous avez perdu... :(');

                window.location.href = url + '?difficulty=' + difficulty; //
            }
        }, 1000);
    }

    function triggerHighscoreModal(){
        const modal = document.getElementById('myModal');
        const span = document.getElementsByClassName("close")[0];

        // lorsque l'utilisateur clique sur <span> (x), la modale se ferme
        span.onclick = function() {
            modal.style.display = "none";
            chronometer(300000); // le chronomètre ne se lance qu'après consultation du classement
        };

        // lorsque 'lutilisateur clique en dehors de la modale, cette dernière est fermée
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
                chronometer(300000);// le chronomètre ne se lance qu'après consultation du classement

            }
        }
    }
});
