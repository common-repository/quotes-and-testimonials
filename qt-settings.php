<?php
function qt_add_submenu_page()
{
    add_submenu_page(
        'edit.php?post_type=quote',
        __('Reorder Quotes'),
        __('Reorder Quotes'),
        'manage_options',
        'reorder_quotes',
        'reorder_admin_quotes_callback'
    );
    add_submenu_page(
    'edit.php?post_type=quote',
    __('Help'),
    __('Help'),
    'manage_options',
    'quotes_help',
    'qt_help_submenu_callback'
  );
}
add_action('admin_menu', 'qt_add_submenu_page');
function qt_help_submenu_callback()
{
    ?>
<div class="help-page">
  <h1><?php _e('Help:');
    ?></h1>
  <br>
  <h3><?php _e('Displaying all Quotes and Testimonials with shortcodes:');
    ?></h3>
  <p><?php _e('To display your Quotes and Testimonials you must add the shorcode " <strong>[quotes]</strong> " without the quotaion marks to the section of any page you would like them to appear on. You may also add options to the shortcode to chage what is output. All Quotes and Testimonials will be displayed in the order set on the <strong><em>Reorder Quotes</em></strong> submenu page to the left.');
    ?></p>
  <p><?php _e('<strong>Default: </strong>[quotes] <br> Example: [quotes] would show 10 posts in the order set on the <strong><em>Reorder Quotes</em></strong> submenu page.');
    ?></p>
  <h5><?php _e('Options:');
    ?></h5>
  <p><?php _e('<strong>Amount displayed: </strong>[quotes count="NUMBER"] <br> Example: [quotes count=2] would show two posts.');
    ?></p>

</div>
  <?php

}

function reorder_admin_quotes_callback()
{
    $args = array(
    'post_type' => 'quote',
    'orderby' => 'menu_order',
    'order' => 'ASC',
    'post_status' => 'publish',
    'no_found_rows' => true,
    'update_post_term_cache' => false,
    'posts_per_page' => 150,
  );

    $quotes_testimonials = new WP_Query($args);

    ?>

<div class="wrap" id="quote-sort">
  <div class="icon32" id="icon-quote-admin">
    <br />
  </div>
  <h2><?php _e('Sort Quote Positions', 'quotes-testimonials') ?></h2>
  <img src="<?php echo esc_url(admin_url().'/images/loading.gif');
    ?>" id="loading-animation">
  <?php if ($quotes_testimonials->have_posts()) : ?>
				<p><?php _e('<strong>Note:</strong> this only affects the Quotes listed using the shortcode "[quote]"', 'quotes-testimonials');
    ?></p>
				<ul id="custom-type-list">
					<?php while ($quotes_testimonials->have_posts()) : $quotes_testimonials->the_post();
    ?>
						<li id="<?php esc_attr(the_id());
    ?>"><?php esc_html(the_title());
    ?></li>
					<?php endwhile;
    ?>
				</ul>
			<?php else: ?>
				<p><?php _e('You have no Quotes to sort.', 'quotes-testimonials');
    ?></p>
			<?php endif;
    ?>
</div>
<?php

}

function qt_save_reorder()
{
    if (!check_ajax_referer('qt-quote-order', 'security')) {
        return wp_send_json_error('Invalid Nonce');
    }
    if (!current_user_can('manage_options')) {
        return wp_send_json_error('You do not have the permissions required for that action');
    }

    $order = $_POST['order'];
    $counter = 0;

    foreach ($order as $item_id) {
        $post = array(
        'ID' => (int) $item_id,
        'menu_order' => $counter,
      );

        wp_update_post($post);

        ++$counter;
    }
    wp_send_json_success('Post Saved');
}

//action comes from "action: 'save_sort'" defined in the ajax request in reorder.js
add_action('wp_ajax_save_sort', 'qt_save_reorder');
