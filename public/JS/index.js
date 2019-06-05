$(document).ready(function(){

    $('.draggable').draggable();
    $('.draggable').css('cursor', 'pointer');

    $('select').select2({
        allowClear: true,
        placeholder: "choisis une option"
    });

    $('.selectChange').change(function () {

        if($('.selectChange option:selected').html() === 'Elève'){
            $('.selectClasses').removeAttr('multiple');
        }else {
            $('.selectClasses').attr('multiple', 'multiple');
        }
    })

});