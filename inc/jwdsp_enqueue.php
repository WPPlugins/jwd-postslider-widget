<?php 
/********************************
 * JWD Show Posts Slider :: Enqueue Scripts & Styles
 ********************************/
/********************************
 * Blocking direct access to this file 
 ********************************/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/********************************
 * Register / Enqueue Scripts & Style for admin side 
 ********************************/
if ( ! function_exists( 'JWDSP_admin_scripts' ) ) { 
	add_action('admin_enqueue_scripts', 'JWDSP_admin_scripts'); 
	function JWDSP_admin_scripts() {
		if(is_admin()){	
			global $jwdsp_admin_page;
			$screen = get_current_screen();
			$jwdsp_general_settings = get_option('jwdsp_general_settings');
			$acceptedPosts = $jwdsp_general_settings['post_types'];
			if( $screen->id === 'widgets' || $screen->id == $jwdsp_admin_page) {
				/* Add the color picker css file */
				wp_enqueue_media();
				wp_enqueue_style( 'wp-color-picker' ); 
				wp_enqueue_style( 'jwdsp-admin-style', plugins_url('css/jwdsp_admin_style.min.css', dirname(__FILE__)), null,  jwdsp_plugin_data('Version'));
				wp_enqueue_script( 'jwdsp-admin-scripts', plugins_url('js/jwdsp_admin_scripts.min.js', dirname(__FILE__)) , array( 'jquery', 'wp-color-picker', 'jquery-ui-slider' ), jwdsp_plugin_data('Version'), true );
				/* Localize Script */
				wp_localize_script( 'jwdsp-admin-scripts', 'jwdsp_ajaxObject', array( 
					'ajax_url' 					=> admin_url( 'admin-ajax.php' ), 
					'jwdsp_ajaxObject_nonce' 	=> wp_create_nonce( 'jwdsp-ajaxObject-nonce' ),
					'jwdspConfirmMsg'			=> __('Changes you made may not be saved. Do you want to leave this page?', 'jwdsp')
					) 
				);
 			}
			foreach($acceptedPosts as $accepted){
				if( $screen->id === $accepted) {
					wp_enqueue_script( 'jwdsp-add-thumbnail', plugins_url('js/jwdsp_post_thumbnail.min.js', dirname(__FILE__)) , array('jquery'), jwdsp_plugin_data('Version'), true );
					/* Localize Script */
					wp_localize_script( 'jwdsp-add-thumbnail', 'jwdsp_postThb', array( 
						'ajax_url' 					=> admin_url( 'admin-ajax.php' ), 
						'jwdspRemoveImgMsg' 		=> __('Remove Image', 'jwdsp'),
						'jwdspAddImgMsg' 			=> __('Add Image', 'jwdsp'),
						'jwdspAddImgTitleMsg' 		=> __('Featured image for JWD PostSlider Widget', 'jwdsp'),
						'jwdspAddImgBtnMsg' 		=> __('Use This Image', 'jwdsp'),
						) 
					);
				}
			}
		}
	} 
}
/********************************
 * Register / Enqueue Scripts & Style for client side  
 ********************************/
if ( ! function_exists( 'JWDSP_client_scripts' ) ) { 
	add_action('wp_enqueue_scripts', 'JWDSP_client_scripts');
	function JWDSP_client_scripts(){ 
		if ( is_active_widget( false, false, 'jwdsp_postslider', true ) ) {
			wp_enqueue_style( 'jwdsp-front-style', plugins_url('css/jwdsp_front_style.min.css', dirname(__FILE__)), null,  jwdsp_plugin_data('Version'));
			wp_enqueue_script( 'jwdsp-front-scripts', plugins_url('js/jwdsp_front_scripts.min.js', dirname(__FILE__)) , array(), jwdsp_plugin_data('Version') );
			wp_add_inline_style( 'jwdsp-front-style', jwdsp_get_custom_css() );
 		}
	}
}