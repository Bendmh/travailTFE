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

    $collectionButton.on('click', function (e) {
        buttonClass = $(this).attr('class').split(" ").pop();
        responseClass = $collectionResponse.first().attr('class').split(" ").pop();

        if(buttonClass === responseClass){
            $point++;
        }
        $collectionResponse.first().remove();
        $collectionResponse = $('h3.response');
        if($collectionResponse.length === 0){
            $final = $('.final').attr('href');

            //let url = this.href;
            axios.post($final, {
                total : $total,
                point : $point
            }).then(function(response){
                $collectionButton.hide();
                let json = jQuery.parseJSON(response.data.message);
                $('h3.result').html('Tu as obtenu ' + json.point + ' sur ' + json.total)
                $('h4.enonce').hide();
            })
        }
        $collectionResponse.first().show();
    })

});



