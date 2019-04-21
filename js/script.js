$(document).ready(function(){
    let previousStatus = '';
    let currentStatus = '';

    resetCards();

    $('img').click((e) => {

        const classValue = e.target.attributes[0].value;
        const classArray = classValue.split(' ');
        const fruitId = classArray[0];
        const fruitImg = $('#' + fruitId);
        const questionMarkImg = $('.' + fruitId);

        if(!isNaN(fruitId)){
            questionMarkImg.hide();
            fruitImg.show();

            currentStatus = fruitImg[0].attributes[0].value;

            if((currentStatus !== previousStatus) && (previousStatus !== '')){
                setTimeout(() => {
                    resetCards();
                }, 500);

            } else if (currentStatus === previousStatus) {
                resetStatus();
            }

            previousStatus = currentStatus;
        }

    });


    function resetCards() {
        $('.fruit').hide();
        $('.question-mark').show();

        resetStatus();
    }

    function resetStatus(){
        currentStatus = '';
        previousStatus = '';
    }
});