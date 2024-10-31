/*
 * Attaches the image uploader to the input field
 */
jQuery(document).ready(function($) {
    var meta_image_frame;
    $('#meta-image-button').click(function(e) {

        e.preventDefault();
        if (meta_image_frame) {
            meta_image_frame.open();
            return;
        }
        meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
            title: meta_image.title,
            button: {
                text: meta_image.button
            },
            library: {
                type: 'image'
            }
        });
        meta_image_frame.on('select', function() {
            var media_attachment = meta_image_frame.state().get('selection').first().toJSON();

            $('#meta-image').val(media_attachment.url);
        });
        meta_image_frame.open();
    });
});
