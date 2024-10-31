jQuery(document).ready(function($) {

    var sortList = $('ul#custom-type-list');
    var animation = $('#loading-animation');
    var pageTitle = $('div h2');

    sortList.sortable({

        update: function(event, ui) {
            animation.show();

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'save_sort',
                    order: sortList.sortable('toArray'),
                    security: QUOTES.security
                },
                success: function(response) {
                    $('div#message').remove();
                    animation.hide();
                    if (true === response.success) {
                        pageTitle.after('<div id="message" class="updated"><p>' + QUOTES.success + '</p></div>');
                    } else {
                        pageTitle.after('<div id="message" class="error"><p>' + QUOTES.failure + '</p></div>');
                    }
                },
                error: function(error) {
                    $('div#message').remove();
                    animation.hide();
                    pageTitle.after('<div id="message" class="error"><p>' + QUOTES.failure + '</p></div>');
                }
            });
        }
    });

});
