$(document).ready(function(){
    let $sendButton = $('#send');

    $sendButton.on('click', function (e) {
        let $reponses = $('.response');

        retour = {};
        tab = [];
        $reponses.each(function () {
            if($(this).val() !== ''){
                retour = {'value' : $(this).val()};
                tab.push(retour);
            }
        });

        let activityId = $('h1').attr('name');

        let url = '/brainstorming/' + activityId + '/answer';

        axios.post(url, {
            response : tab
        }).then(function (response) {
                $('h1').after(response.data.message);
            });
    })
});