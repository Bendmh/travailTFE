$(document).ready(function(){

    $verifyButton = $('button.final');

    $tab = $('.point');
    $total = 0;

    for(let i=0; i < $tab.length; i++){
        $total += parseFloat($tab[i].innerHTML);
    }

    $verifyButton.on('click', function (e) {

        $retour = {};
        $tab = [];

        $('input:checked').each(function () {
            $retour = {'questionId' : $(this).attr('name'), 'value' : $(this).val()};
            $tab.push($retour);
        });

        $final = $('.final').attr('href');

        axios.post($final, {
            response: $tab,
            total: $total,
        }).then(function (response) {
            let json = jQuery.parseJSON(response.data.message);
            $('h3.result').html('Tu as obtenu ' + json.point + ' sur ' + json.total);
            if(json.point < json.total/2){
                $('.lienActivite').removeClass('d-none');
            }
            $('.question').hide();
        })
    })
});