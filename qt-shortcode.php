<?php

function qt_taxonomy_shortcode($atts, $content = null)
{
    $atts = shortcode_atts(
        array(
            'title' => 'Categories',
            'count' => 10,
            'pagination' => false,
            ), $atts
    );
    $cats = get_terms('qt_category');
    if (!empty($cats) && !is_wp_error($cats)) {
        $displaylist = '<div id="quote-list">';
        $displaylist .= '<h4>'.esc_html__($atts[ 'title' ]).'</h4>';
        $displaylist .= '<ul>';
        foreach ($cats  as $cat) {
            $displaylist .= '<li class="quote-cat">';
            $displaylist .= '<a href="'.esc_url(get_term_link($cat)).'">';
            $displaylist .= esc_html__($cat->name).'</a></li>';
        }
        $displaylist .= '</ul></div>';

        return $displaylist;
    }
}
 add_shortcode('quotes_category', 'qt_taxonomy_shortcode');

function qt_list_quotes($atts, $content = null)
{
    $atts = shortcode_atts(
      array(
          'post_type' => 'quote',
          'count' => 10,
          'pagination' => 'off',
      ), $atts
  );

    $pagination = $atts[ 'pagination' ]  == 'on' ? false : true;
    $paged = get_query_var('paged') ? get_query_var('paged') : 1;

    $args = array(
    'post_type' => 'quote',
    'post_status' => 'publish',
    'no_found_rows' => $pagination,
    'posts_per_page' => $atts[ 'count' ],
    'paged' => $paged,
    'orderby' => 'menu_order',
    'order' => 'ASC',
  );

    $quotes = new WP_Query($args);

    if ($quotes->have_posts()) :

    $display_quote_table = '<div class="qt-content-container">';
    $display_quote_table .= '<table class="qt-table">';
    $display_quote_table .= '<tbody>';
    while ($quotes->have_posts()) : $quotes->the_post();

    $quote_image = get_post_meta(get_the_ID(), 'meta_image', true);
    $quote_content = get_post_meta(get_the_ID(), 'qt_content', true);
    $title = get_the_title();
    $link = get_permalink();
    $display_quote_table .= '<tr>';
  //Output the image meta in first column
  if (!empty($quote_image)) :
    $display_quote_table .= '<td class="qt-image-td">';
    $display_quote_table .= '<img class="qt-image" src="'.esc_url($quote_image).'"/>';
    $display_quote_table .= '</td>'; else :
    $display_quote_table .= '<td>';
    $display_quote_table .= '</td>';
    endif;
  //Output the content meta in second column
    $display_quote_table .= '<td>';
    $display_quote_table .= '<blockquote>';
    $display_quote_table .= '<q>'.esc_html($quote_content).'</q>';
    $display_quote_table .= '</blockquote>';
    $display_quote_table .= '<strong class="qt-author">'.esc_html($title).'</strong>';
    $display_quote_table .= '</td>';
    $display_quote_table .= '</tr>';

    endwhile;
    $display_quote_table .= '</tbody>';
    $display_quote_table .= '</table>';
    $display_quote_table .= '</div>'; else :
    $display_quote_table = sprintf(__('<p class="quote-error">Sorry, no quotes where found.</p>'));
    endif;
    wp_reset_postdata();

    if ($quotes->max_num_pages > 1 && is_page()) {
        $display_quote_table .= '<nav class="prev-next-posts">';
        $display_quote_table .= '<div call="nav-pervious">';
        $display_quote_table .= get_next_posts_link(__('<span class="meta-nav">&larr;</span> Previous'), $quotes->max_num_pages);
        $display_quote_table .= '</div';
        $display_quote_table .= '<div class="next-posts-link">';
        $display_quote_table .= get_previous_posts_link(__('<span class="meta-nav">&rarr;</span> Next'));
        $display_quote_table .= '</div>';
        $display_quote_table .= '</nav>';
    }

    return $display_quote_table;
}
  add_shortcode('quotes', 'qt_list_quotes');
