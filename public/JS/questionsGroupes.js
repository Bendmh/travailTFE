var $collectionHolder;

var $addResponseButton = $('<button type="button" class="add_response_link btn btn-info">Ajouter une réponse</button>');
var $newLinkLi = $('<li class="list-unstyled"></li>').append($addResponseButton);

$(document).ready(function(){

    $('fieldset.form-group').hide();

    $collectionHolder = $('ul.response');

    $collectionHolder.append($newLinkLi);

    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    $addResponseButton.on('click', function (e) {
       addResponseForm($collectionHolder, $newLinkLi);
    });
});


function addResponseForm($collectionHolder, $newLinkLi) {
    var prototype = $collectionHolder.data('prototype');

    var index = $collectionHolder.data('index');

    var newForm = prototype;

    newForm = newForm.replace(/__name__/g, index);

    $collectionHolder.data('index', index + 1);

    var $newFormLi = $('<li class="list-unstyled"></li>').append(newForm);
    $newLinkLi.before($newFormLi);

    addTagFormDeleteLink($newFormLi);
}

function addTagFormDeleteLink($tagFormLi) {
    var $removeFormButton = $('<button type="button" class="btn btn-secondary mb-3">Supprimer cette réponse</button>');
    $tagFormLi.append($removeFormButton);

    $removeFormButton.on('click', function(e) {
        // remove the li for the tag form
        $tagFormLi.remove();
    });
}