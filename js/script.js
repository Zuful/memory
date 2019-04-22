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

    resetCards();
    chronometer(300000);
    progressBarMove();

    $('img').click((e) => {
        const classValue = e.target.attributes[0].value;
        const classArray = classValue.split(' ');
        const fruitId = classArray[0];
        const fruitImg = $('#' + fruitId);
        const questionMarkImg = $('.' + fruitId);

        if(!isNaN(fruitId)){
            questionMarkImg.hide();
            fruitImg.show();

            lastCards.push(fruitId);

            currentStatus = fruitImg[0].attributes[0].value;

            if((currentStatus !== previousStatus) && (previousStatus !== '')){
                setTimeout(() => {
                    $('#' + lastCards[0]).hide();
                    $('.' + lastCards[0]).show();
                    $('#' + lastCards[1]).hide();
                    $('.' + lastCards[1]).show();
                    lastCards = [];

                    resetCards();
                }, 500);

            } else if (currentStatus === previousStatus) {
                currentNumberOfPairs++;
                lastCards = [];

                setTimeout(() => {
                    isGameFinished();
                }, 250);
                resetStatus();
            }

            previousStatus = currentStatus;
        }
    });

    function resetCards() {
        resetStatus();
    }

    function resetStatus(){
        currentStatus = '';
        previousStatus = '';
    }

    function isGameFinished() {
        if(currentNumberOfPairs === numberOfPairsToReach){
            const playerName = prompt('Vous avez gagné! Entrez votre nom pour le classement.');
            saveScore(playerName);
            alert(playerName + ' votre score a été enregistré.');
        }
    }

    function saveScore(player_name){
        const data = {
            player_name: player_name,
            difficulty: difficulty,
            time: timeElapsed
        };

        $.post( url, data);
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
            let seconds = Math.floor((distance % (1000 * 60)) / 1000);

            timeElapsed = minutes + ":" + seconds;
            // Affiche le résultat dans la balise ayant l'id 'chrono'
            $("#chrono").text(timeElapsed);

            let percentage = (100 * distance) / givenTimeInMilliseconds;
            progressBarMove(percentage);

            // Le temps imparti est écoulé on informe que la partie est perdue et on en relance une autre.
            if (distance >= givenTimeInMilliseconds) {
                clearInterval(x);
                alert('Temps écoulé, vous avez perdu... :(');

                window.location.href = url + '?difficulty=' + difficulty;
            }
        }, 1000);
    }
});
