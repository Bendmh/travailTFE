let $collectionResponse;

let $point;

let $collectionButton;
let $total;

$(document).ready(function(){

    $collectionResponse = $('h3.response');
    $total = $collectionResponse.length;

    $collectionButton = $('button.groups');

    $collectionResponse.hide();

    //$collection.first().remove();

    $collectionResponse.first().show();

    $point = 0;

    $retour = {};
    $tab = [];
    $collectionButton.on('click', function (e) {
        buttonClass = $(this).attr('name');
        responseClass = $collectionResponse.attr('name');

        if(buttonClass === responseClass){
            $point++;
        }
        else {
            $retour = {'reponse' : $collectionResponse.html(), 'groupe' : buttonClass};
            $tab.push($retour);
        }
        $collectionResponse.first().remove();
        $collectionResponse = $('h3.response');
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
                if(json.point < json.total/2){
                    $('.lienActivite').removeClass('d-none');
                }
                $('h4.enonce').hide();
            })
        }
        $collectionResponse.first().show();
    })

});



