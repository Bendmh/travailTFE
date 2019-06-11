let $collectionResponse;

let $point;

let $collectionButton;
let $total;
let $compteur;

$(document).ready(function(){

    $collectionResponse = $('h3.response');
    $total = $collectionResponse.length;

    $collectionButton = $('button.groups');

    $('#carouselExampleControls').carousel({
        interval: false
    });

    $('.carousel-control-prev').on('click', function () {
        colorButton('prev');
    });

    $('.carousel-control-next').on('click', function () {
        colorButton('next');
    });

    $point = 0;

    $retour = {};
    $tab = [];
    $compteur  = $total - $tab.length;
    $('#compteur').html($compteur + '/' + $total);
    $collectionButton.on('click', function (e) {
        buttonClass = $(this).attr('name');
        responseClass = $('.carousel-item.active h3').attr('name');
        let modification = false;

        for(let i=0; i < $tab.length; i++ ){
                if($tab[i].reponse === responseClass){
                    $tab[i].groupe = buttonClass;
                    modification = true;
                }
        }

        if(!modification){
            $retour = {'reponse' : responseClass, 'groupe' : buttonClass};
            $tab.push($retour);
        }
        $('#carouselExampleControls').carousel("next");

        colorButton('next');

        $compteur  = $total - $tab.length;
        $('#compteur').html($compteur + '/' + $total);

        if($tab.length === $total){
            $final = $('a.final').attr('href');

            $('button.final').removeClass('d-none');

            $('button.final').on('click', function () {
                sendDataAjax($final);
            });
        }
    })

});

function sendDataAjax(url) {
    //let url = this.href;
    axios.post(url, {
        response : $tab,
        total : $total,
        point : $point
    }).then(function(response){
        $collectionButton.hide();
        let json = jQuery.parseJSON(response.data.message);
        $('h3.result').html('Tu as obtenu ' + json.point + ' sur ' + json.total);
        $('.lienActivite').removeClass('d-none');
        $('#carouselExampleControls').addClass('d-none');
        if(json.point < json.total/2){
            $('.lienActivite').addClass('text-danger');
        }
        else{
            $('.lienActivite').addClass('text-success');
        }
        $('h4.enonce').addClass('d-none');
        $('button.final').addClass('d-none');
    })
}

function colorButton(move){
    responseClass = $('.carousel-item.active');
    if(move === 'next'){
        responseClassNextorPrev = responseClass.next('div').children().attr('name');
        if(responseClassNextorPrev === undefined){
            responseClassNextorPrev = $('.carousel-item').first().children().attr('name');
        }
    }
    else{
        responseClassNextorPrev = responseClass.prev('div').children().attr('name');
        if(responseClassNextorPrev === undefined){
            responseClassNextorPrev = $('.carousel-item').last().children().attr('name');
        }
    }
    $('button').removeClass('btn-warning');
    for(let i=0; i < $tab.length; i++ ){
        if($tab[i].reponse === responseClassNextorPrev){
            buttonReponse = $('button[name=' + $tab[i].groupe +']');
            buttonReponse.addClass('btn-warning');
        }
    }
}



