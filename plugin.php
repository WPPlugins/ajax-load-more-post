<?php 
/*
  Plugin Name: Ajax Load More Post
  Description: Ajax Load More Post
  Author: iKhodal Team
  Plugin URI: http://www.ikhodal.com/ajax-load-more-post/
  Author URI: http://www.ikhodal.com/ajax-load-more-post/
  Version: 1.0
  License: GNU General Public License v2.0
  License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/ 
  
  
//////////////////////////////////////////////////////
// Defines the constants for use within the plugin. //
////////////////////////////////////////////////////// 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly  
/**
* Widget/Block Title
*/
define( 'aplm_widget_title', __( 'Ajax Posts', 'ajaxpostloadmore') );
  
/**
* Number of posts per next loading result
*/
define( 'aplm_number_of_post_display', '2' ); 
  
/**
* Post title text color
*/
define( 'aplm_title_text_color', '#000' );
 
/**
* Widget/block header text color
*/
define( 'aplm_header_text_color', '#fff' );

/**
* Widget/block header text background color
*/
define( 'aplm_header_background_color', '#00bc65' );

/**
* Display post title and text over post image
*/
define( 'aplm_display_title_over_image', 'no' );

/**
* Widget/block width
*/
define( 'aplm_widget_width', '100%' );  

/**
* Hide/Show widget title
*/
define( 'aplm_hide_widget_title', 'no' );
 
/**
* Template for widget/block
*/
define( 'aplm_template', 'pane_style_1' ); 

/**
* Hide/Show post title
*/
define( 'aplm_hide_post_title', 'no' );  
  
/**
* Security key for block id
*/
define( 'aplm_security_key', 'APLM_#s@R$@ASI#TA(!@@21M3' );
 
/**
*  Assets for posts grid list
*/
$aplm_plugins_url = plugins_url( "/assets/", __FILE__ );

define( 'APLM_MEDIA', $aplm_plugins_url );  

/**
*  Plugin DIR
*/
$aplm_plugin_DIR = plugin_basename(dirname(__FILE__));

define( 'aplm_plugin_DIR', $aplm_plugin_DIR ); 
 
/**
 * Include abstract class for common methods
 */
require_once 'include/abstract.php';


///////////////////////////////////////////////////////
// Include files for widget and shortcode management //
///////////////////////////////////////////////////////

/**
 * Admin panel widget configuration
 */ 
require_once 'include/admin.php';

/**
 * Load Ajax Post Load More on frontent pages
 */
require_once 'include/ajaxpostloadmore.php';  
 