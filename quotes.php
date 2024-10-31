<?php
/**
*Plugin Name: Quotes & Testimonials
*Plugin URI: http://zenzora.com
*Description: This plugin allows you to add listing of quotes and testimonials and display them with a shortcode. It allow you to change how many quotes are displayed and in what order.
*Author: David Lakin
*Author URI: http://zenzora.com
*Version: 1.0.0
*License: GPLv3 or later.
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 3
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

*/

//Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__).'qt-cpt.php';
require_once plugin_dir_path(__FILE__).'qt-settings.php';
require_once plugin_dir_path(__FILE__).'qt-fields.php';
require_once plugin_dir_path(__FILE__).'qt-shortcode.php';

//Frontend Style & Script Enqueues
function qt_enqueue_scripts()
{
    //Shortcode CSS File.
  wp_enqueue_style('qt-shortcode-css', plugins_url('css/shortcode-quotes.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'qt_enqueue_scripts');

//Backend Style & Script Enqueues
function qt_admin_enqueue_scripts()
{
    global $pagenow, $typenow;

    if ($typenow == 'quote') {
        //Main Admin CSS File.
    wp_enqueue_style('qt-admin-css', plugins_url('css/admin-quotes.css', __FILE__));
    }

  // Enque scripts for image upload to feilds
  if ($typenow == 'quote') {
      wp_enqueue_media();
      wp_register_script('meta-box-image', plugins_url('js/meta-box-image.js', __FILE__), array('jquery'));
      wp_localize_script('meta-box-image', 'meta_image',
              array(
                  'title' => __('Choose or Upload an Image', 'quotes-testimonials'),
                  'button' => __('Use this image', 'quotes-testimonials'),
              )
          );
      wp_enqueue_script('meta-box-image');
  }

  // Enqueue jquery-ui-sortable for reorder on settings page
  if ($pagenow == 'edit.php' && $typenow == 'quote') {
      wp_enqueue_script('reorder-js', plugins_url('js/reorder.js', __FILE__), array('jquery', 'jquery-ui-sortable'), '20160502', true);
      wp_localize_script('reorder-js', 'QUOTES', array(
      'security' => wp_create_nonce('qt-quote-order'),
      'success' => __('Your new sort order has been saved.'),
      'failure' => __('Sorry, there was an error saveing the sort order.'),
    ));
  }
}
//This hook ensures our scripts and styles are only loaded in the admin.
add_action('admin_enqueue_scripts', 'qt_admin_enqueue_scripts');
