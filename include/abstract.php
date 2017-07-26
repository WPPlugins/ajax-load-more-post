<?php 
/** 
 * Abstract class  has been designed to use common functions.
 * This is file is responsible to add custom logic needed by all templates and classes.  
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly   
if ( ! class_exists( 'ajaxPostLoadmoreLib' ) ) { 
	abstract class ajaxPostLoadmoreLib extends WP_Widget {
		
	   /**
		* Default values can be stored
		*
		* @access    public
		* @since     1.0
		*
		* @var       array
		*/
		public $_config = array();

		/**
		 * PHP5 constructor method.
		 *
		 * Run the following methods when this class is loaded.
		 * 
		 * @access    public
		 * @since     1.0
		 *
		 * @return    void
		 */ 
		public function __construct() {  
		
			/**
			 * Default values configuration 
			 */
			$this->_config = array(
				'widget_title'=>aplm_widget_title,
				'number_of_post_display'=>aplm_number_of_post_display, 
				'title_text_color'=>aplm_title_text_color, 
				'header_text_color'=>aplm_header_text_color,
				'header_background_color'=>aplm_header_background_color,
				'display_title_over_image'=>aplm_display_title_over_image, 
				'hide_widget_title'=>aplm_hide_widget_title, 
				'hide_post_title'=>aplm_hide_post_title,
				'template'=>aplm_template, 
				'vcode'=>$this->getUCode(),  
				'security_key'=>aplm_security_key,
				'tp_widget_width'=>aplm_widget_width    
			); 
			
			/**
			 * Load text domain
			 */
			add_action( 'plugins_loaded', array( $this, 'ajaxpostloadmore_text_domain' ) );
			
			parent::WP_Widget( false, $name = __( 'Ajax Post Load More', 'ajaxpostloadmore' ) ); 	
			
			/**
			 * Widget initialization for ajax posts
			 */
			add_action( 'widgets_init', array( &$this, 'initajaxPostLoadmore' ) ); 
			
			/**
			 * Load the CSS/JS scripts
			 */
			add_action( 'init',  array( $this, 'ajaxpostloadmore_scripts' ) );
			
			add_action( 'admin_enqueue_scripts', array( $this, 'aplm_admin_enqueue' ) ); 
			
		}
		
		/**
		 * Load the CSS/JS scripts
		 *
		 * @return  void
		 *
		 * @access  public
		 * @since   1.0
		 */
		function ajaxpostloadmore_scripts() {

			$dependencies = array( 'jquery' );
			 
			/**
			 * Include Ajax Post Load More JS/CSS 
			 */
			wp_enqueue_style( 'ajaxpostloadmore', APLM_MEDIA."css/ajaxpostloadmore.css" );
			 
			wp_enqueue_script( 'ajaxpostloadmore', APLM_MEDIA."js/ajaxpostloadmore.js", $dependencies  );
			
			/**
			 * Define global javascript variable
			 */
			wp_localize_script( 'ajaxpostloadmore', 'ajaxpostloadmore', array(
				'aplm_ajax_url' => admin_url( 'admin-ajax.php' ),
				'aplm_security'  =>  wp_create_nonce(aplm_security_key),
				'aplm_media'  => APLM_MEDIA,
				'aplm_all'  => __( 'All', 'ajaxpostloadmore' ),
				'aplm_plugin_url' => plugins_url( '/', __FILE__ ),
			)); 
		}	 
		
		/**
		 * Loads the text domain
		 *
		 * @access  private
		 * @since   1.0
		 *
		 * @return  void
		 */
		public function ajaxpostloadmore_text_domain() {

		  /**
		   * Load text domain
		   */
		   load_plugin_textdomain( 'ajaxpostloadmore', false, aplm_plugin_DIR . '/languages' );
			
		}
		 
		/**
		 * Load and register widget settings
		 *
		 * @access  private
		 * @since   1.0
		 *
		 * @return  void
		 */ 
		public function initajaxPostLoadmore() { 
			
		  /**
		   * Widget registration
		   */
		   register_widget( 'ajaxPostLoadmoreWidget_Admin' );
			
		}     
		
		/**
		* Register and load JS/CSS for admin widget configuration 
		*
		* @access  private
		* @since   1.0
		*
		* @return  bool|void It returns false if not valid page or display HTML for JS/CSS
		*/  
		public function aplm_admin_enqueue() {

			if ( ! $this->validate_page() )
				return FALSE;
			
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_style( 'admin-ajaxpostloadmore.css', APLM_MEDIA."css/admin-ajaxpostloadmore.css" );
			wp_enqueue_script( 'admin-ajaxpostloadmore.js', APLM_MEDIA."js/admin-ajaxpostloadmore.js" ); 
			
		}		
		
		
	   /**
		* Validate widget screen
		*
		* @access  private
		* @since   1.0
		*
		* @return  bool It returns true if page is post.php or widget otherwise returns false
		*/ 
	   function validate_page() {

			if ( ( isset( $_GET['post_type'] )  && $_GET['post_type'] == 'aplm_menu' ) || strpos($_SERVER["REQUEST_URI"],"widgets.php") > 0  || strpos($_SERVER["REQUEST_URI"],"post.php" ) > 0 || strpos($_SERVER["REQUEST_URI"], "ajaxpostloadmore_settings" ) > 0  )
				return TRUE;
		
		} 		
		 
		/**
		 * Get post image by given image attachment id
		 *
 		 * @access  public
		 * @since   1.0
		 *
		 * @param   int   $img  Image attachment ID
		 * @return  string  Returns image html from post attachment
		 */
		 public function getPostImage( $img ) {
		 
			$image_link = wp_get_attachment_url( $img ); 
			if( $image_link ) {
				$image_title = esc_attr( get_the_title( $img ) );  
				return wp_get_attachment_image( $img , array(180,180), 0, $attr = array(
									'title'	=> $image_title,
									'alt'	=> $image_title
								) );
			}else{
				return "<img src='".APLM_MEDIA."images/no-img.png' />";
			}
			
		 } 

		/**
		* Fetch post data from database by item limit
		*
		* @access  public
		* @since   1.0 
		* 
		* @param   int    $_limit_start  		Limit to fetch post starting from given position
		* @param   int    $_limit_end  			Limit to fetch post ending to given position
		* @param   int    $is_count  			Whether to fetch only number of posts from database as count of items 
		* @param   int    $_is_last_updated  	Whether to fetch only last updated post or not
		* @return  object Set of searched post data
		*/
		function getSqlResult(  $_limit_start, $_limit_end, $is_count = 0, $_is_last_updated = 0 ) {
			
			global $wpdb; 
			$_category_filter_query = "";
			$_post_text_filter_query = "";
			$_fetch_fields = "";
			$_limit = "";
			  
		   /**
			* Prepare safe mysql database query
			*/ 
			
			$_category_filter_query .= " INNER JOIN {$wpdb->prefix}term_taxonomy as wtt on wtt.taxonomy = 'category'  INNER JOIN {$wpdb->prefix}term_relationships as wtr on  wtr.term_taxonomy_id = wtt.term_taxonomy_id and wtr.object_id = wp.ID ";  
			 
			if( $is_count == 1 ) { 
				$_fetch_fields = " count(*) as total_val ";
			} else {  
				$_fetch_fields = " wp.post_type, pm_image.meta_value as post_image, wp.ID as post_id, wp.post_title as post_title, wp.post_date ";
				
				if( $_is_last_updated == 1 )
					$_limit = $wpdb->prepare( " order by wp.post_date DESC limit  %d, %d ", 0, 1 );
				else
					$_limit = $wpdb->prepare( " order by wp.post_date DESC limit  %d, %d ", $_limit_start, $_limit_end );
			} 
			 
			$_post_text_filter_query .=  " and wp.post_type = 'post' "; 
			
			$_limit = " group by wp.ID ".$_limit; 
			
		   /**
			* Fetch post data from database
			*/
			if( $is_count == 1 ) {
				$_result_items = $wpdb->get_results( " select $_fetch_fields  from (select $_fetch_fields from {$wpdb->prefix}posts as wp  
				$_category_filter_query LEFT JOIN {$wpdb->prefix}postmeta as pm_image on pm_image.post_id = wp.ID and pm_image.meta_key = '_thumbnail_id'
				where wp.post_status = 'publish' $_post_text_filter_query $_limit) as ct " );	
			} else {
				$_result_items = $wpdb->get_results( " select $_fetch_fields from {$wpdb->prefix}posts as wp  
				$_category_filter_query LEFT JOIN {$wpdb->prefix}postmeta as pm_image on pm_image.post_id = wp.ID and pm_image.meta_key = '_thumbnail_id'
				where wp.post_status = 'publish' $_post_text_filter_query $_limit " );			
			}	
				  
			return $_result_items;

		} 
		
		 
		/**
		 * Get Unique Block ID
		 *
		 * @access  public
		 * @since   1.0
		 *
		 * @return  string 
		 */
		public function getUCode() { 
			
			return 'uid_'.md5( "APLM32@#RPSDD@SQSITARAM@A$".time() );
		
		} 
		
		/**
		 * Get Ajax Post Load More Template
		 *
		 * @access  public
		 * @since   1.0
		 *
		 * @param   string $file Template file name
		 * @return  string Returns template file path
		 */
		public function getajaxPostLoadmoreTemplate( $file ) {
			
			// Get template file path
			if( locate_template( $file ) != "" ){
				return locate_template( $file );
			}else{
				return plugin_dir_path( dirname( __FILE__ ) ) . 'templates/' . $file ;
			}  
				
	   }
   }
}