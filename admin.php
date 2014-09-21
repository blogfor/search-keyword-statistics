<?php
/*
  Plugin Name: Search Keyword Statistics
  Plugin URI: http://www.blogfordeveloper.com/
  Description: Keep statistics of keywords being searched on your website.
  Version: 1.3
  Author: Ramen Dey & Bivash Kanti Pal
  Author URI: http://www.blogfordeveloper.com/about-us/
*/


global $wpdb;

define( 'SS_TABLE', $wpdb->prefix . 'search_statistics' );
define('SS_BG_VERSION',13);

/* Handlers for Detect and save search keywords */
add_action( 'wp_loaded', 'ss_keyword_trace' );

function ss_keyword_trace( ) {
    
    require 'ss-keyword-trace.php';
      if( strpos($_SERVER['PHP_SELF'], 'wp-admin')==false)
    {
	if(!empty($_GET['s']) ) {
		$keyword = trim($_GET['s']);
                
                save_keyword($keyword,@$_SERVER['HTTP_REFERER']);
	}
    }
	
}

/* Admin menu */
add_action( 'admin_menu', 'ss_menu' );

add_action( 'plugins_loaded', 'ss_plugins_update' );

function ss_plugins_update()
{
 include plugin_dir_path( __FILE__ ).'update.php';
}


function admin_dashboard() {
	require 'ss-admin-dashboard.php';
}

function ss_menu() {
	add_menu_page( 'Keyword Statistics', 'Keyword Statistics', 'administrator', 'ss-menu', 'admin_dashboard' );
}


/* Uninstall and Activation handlers */
register_activation_hook( __FILE__, 'ss_activate' );
register_deactivation_hook( __FILE__, 'ss_deactivate' );

register_uninstall_hook( __FILE__, 'ss_deactivate_uninstall' );

function ss_activate( ) {
	global $wpdb;
	if ( $wpdb->get_var( "SHOW TABLES LIKE '" . SS_TABLE . "'" ) != SS_TABLE ) {
		$query =  "CREATE TABLE IF NOT EXISTS " . SS_TABLE . "( 
			id INT PRIMARY KEY AUTO_INCREMENT, 
			keywords VARCHAR(255) NOT NULL, 
			query_date varchar(12) NOT NULL, 
			repeat_count INT,
			source VARCHAR(50),
                        user varchar(10),
                        agent varchar(150)
		)";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		$wpdb->query( $query );
	}
}

function ss_deactivate_uninstall( ) {
	global $wpdb;
	$query = "DROP TABLE IF EXISTS " . SS_TABLE;
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	$wpdb->query( $query );
}

function ss_deactivate( ) {
	delete_option( 'SS_BG_VERSION' );
}


?>