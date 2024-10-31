<?php

function qt_change_title_text( $title ){
     $screen = get_current_screen();

     if  ( 'quote' == $screen->post_type ) {
          $title = 'Author Name';
     }

     return $title;
}

add_filter( 'enter_title_here', 'qt_change_title_text' );


function qt_add_custom_metabox()
{
    add_meta_box(
  'qt_meta',
  __('Single Quote or Testimonial'),
  'qt_content_meta_callback',
  'quote',
  'normal',
  'high',
  'core'
  );
}

add_action('add_meta_boxes', 'qt_add_custom_metabox');

function qt_content_meta_callback($post)
{
    wp_nonce_field(basename(__FILE__), 'qt_nonce');
    $qt_stored_meta = get_post_meta($post->ID);
    ?>

	<div>
		<div class="meta-row">
      <div class="meta-td">
        <p><?php _e('<strong>Note:</strong> See help page for info on display options.');?></p>
      </div>
      <br>
			<div class="meta-th">
				<label for="meta-image" class="qt-row-title"><?php _e('Quote Image', 'quotes-testimonials') ?></label>
			</div>
			<div class="meta-td">
				<input type="text" name="meta_image" id="meta-image" value="<?php if ( isset ( $qt_stored_meta['meta_image'])) echo esc_attr($qt_stored_meta['meta_image'][0]);?>"/>
         <input type="button" id="meta-image-button" class="button" value="<?php _e('Choose or Upload an Image', 'quotes-testimonials')?>" />
			</div>
		</div>
		<div class="meta">
			<div class="meta-th">
        <span><?php _e('Main Content', 'quotes-testimonials') ?></span>
			</div>
		</div>
		<div class="meta-editor"></div>
		<?php
    $content = get_post_meta($post->ID, 'qt_content', true);
    $editor = 'qt_content';
    $settings = array(
            'textarea_rows' => 12,
            'media_buttons' => false,
        );
    wp_editor($content, $editor, $settings);
    ?>
		</div>
	<?php

}

function qt_meta_save($post_id)
{
    // Checks save status
  $is_autosave = wp_is_post_autosave($post_id);
    $is_revision = wp_is_post_revision($post_id);
    $is_valid_nonce = (isset($_POST[ 'qt_nonce' ]) && wp_verify_nonce($_POST[ 'qt_nonce' ], basename(__FILE__))) ? 'true' : 'false';
  // Exits script depending on save status
  if ($is_autosave || $is_revision || !$is_valid_nonce) {
      return;
  }
    if (isset($_POST['meta_image'])) {
        update_post_meta($post_id, 'meta_image', sanitize_text_field($_POST[ 'meta_image' ]));
    }
    if (isset($_POST['qt_content'])) {
        update_post_meta($post_id, 'qt_content', sanitize_text_field($_POST[ 'qt_content' ]));
    }
}
add_action('save_post', 'qt_meta_save');
