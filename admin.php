<?php
/*
  Plugin Name: Search Keyword Statistics
  Plugin URI: http://www.blogfordeveloper.com/
  Description: Keep statistics of keywords being searched on your website.
  Version: 2.2
  Author: Ramen Dey & Bivash Kanti Pal
  Author URI: http://www.blogfordeveloper.com/about-us/
*/


global $wpdb;

define( 'SS_TABLE', $wpdb->prefix . 'search_statistics' );
define('SS_BG_VERSION',22);

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
	
	//add_submenu_page( 'ss-menu', 'Active Post Type', 'Active Post Type', 'administrator','active-post-type', 'active_post_type_func' );
}

function active_post_type_func()
{
	$args = array(
	   'public'   => true,
	   '_builtin' => true
	);

	$output = 'names'; // names or objects, note names is the default
	//$operator = 'and'; // 'and' or 'or'
	
	if(isset($_POST['ss_post']))
	{
		$arr=serialize($_POST['ss_post']);
		update_option('ss_post_types',$arr);
	}
	
	$post_types = get_post_types( $args, $output ); 
	echo '<form method="post">';
	echo '<div><div style="float:left; width:200px;">Operation</div><div style="float:left; width:200px;"> Post Type</div></div>';
	//var_dump($post_types);
	
	foreach ( $post_types  as $post_type ) {
	if($post_type!='attachment')
	   echo '<div style="clear:both;"><div style="float:left; width:200px;"><input type="checkbox" name="ss_post[]" value="'.$post_type.'" checked="checked"></div><div style="float:left; width:200px;"> ' . $post_type . '</div></div>';
	}
	echo '<input type="submit" name="submit" value="submit">';
	echo '</form>';
}



// Function that outputs the contents of the dashboard widget
function ss_dashboard_widget_function($post, $callback_args ) {
	require 'ss-dashboard-widget.php';
}

// Function used in the action hook
function add_dashboard_widgets() {
	wp_add_dashboard_widget('dashboard_widget', 'Keyword Statistics', 'ss_dashboard_widget_function');
}

// Register the new dashboard widget with the 'wp_dashboard_setup' action
add_action('wp_dashboard_setup', 'add_dashboard_widgets' );



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