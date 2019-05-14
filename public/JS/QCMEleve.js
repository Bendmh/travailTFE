let $bonneReponse;
let $mauvaiseReponse;

$(document).ready(function(){

    $bonneReponse = $('input.bonneReponse');
    $mauvaiseReponse = $('input.mauvaiseReponse');

    $verifyButton = $('button.final');

    $tab = $('.point');
    $total = 0;

    for(let i=0; i < $tab.length; i++){
        $total += parseFloat($tab[i].innerHTML);
    }

    $verifyButton.on('click', function (e) {

        $point = $('input.bonneReponse:checked').length - $('input.mauvaiseReponse:checked').length;

        $retour = {};
        $tab = [];

        $repsonse = $('input:checked').each(function () {
            $retour = {'activityId' : $('.activityId').attr('name'), 'questionId' : $(this).attr('name'), 'value' : $(this).val()};
            $tab.push($retour);
        });

        $final = $('.final').attr('href');

        axios.post($final, {
            response: $tab,
            total: $total,
            point: $point
        }).then(function (response) {
            let json = jQuery.parseJSON(response.data.message);
            $('h3.result').html('Tu as obtenu ' + json.point + ' sur ' + json.total);
            $('.question').hide();
        })
    })
});