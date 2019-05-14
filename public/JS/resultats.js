let $response;

$(document).ready(function(){
    $response = $('a.reponseEleve').on('click', function (e) {
        e.preventDefault();

        $url = $(this).attr('href');

        axios.post($url)
            .then(function (response) {
                $('.modal-body').html(response.data);
                $('#myModal').show();
                $(".fermer").on('click', function () {
                    $('#myModal').hide();
                });
            })
    })
});
