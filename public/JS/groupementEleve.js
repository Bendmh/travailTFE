let $collectionResponse;

let $point;

let $collectionButton;
let $total;
let $compteur;

$(document).ready(function(){

    $collectionResponse = $('h3.response');
    $total = $collectionResponse.length;
    $compteur  = $collectionResponse.length;

    $collectionButton = $('button.groups');

    $collectionResponse.hide();

    //$collection.first().remove();

    $collectionResponse.first().show();

    $point = 0;

    $('#compteur').html($compteur + '/' + $total);

    $retour = {};
    $tab = [];
    $collectionButton.on('click', function (e) {
        buttonClass = $(this).attr('name');
        responseClass = $collectionResponse.attr('name');

        $retour = {'reponse' : responseClass, 'groupe' : buttonClass};
        $tab.push($retour);
        $collectionResponse.first().remove();
        $collectionResponse = $('h3.response');
        $compteur--;
        $('#compteur').html($compteur + '/' + $total);
        if($collectionResponse.length === 0){
            $final = $('.final').attr('href');

            //let url = this.href;
            axios.post($final, {
                response : $tab,
                total : $total,
                point : $point
            }).then(function(response){
                $collectionButton.hide();
                let json = jQuery.parseJSON(response.data.message);
                $('h3.result').html('Tu as obtenu ' + json.point + ' sur ' + json.total);
                $('.lienActivite').removeClass('d-none');
                if(json.point < json.total/2){
                    $('.lienActivite').addClass('text-danger');
                }
                else{
                    $('.lienActivite').addClass('text-success');
                }
                $('h4.enonce').hide();
            })
        }
        $collectionResponse.first().show();
    })

});



