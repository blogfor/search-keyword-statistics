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
	
	  global $wpdb;
        $rows=$wpdb->get_results( "SELECT * FROM " . SS_TABLE . " ORDER BY repeat_count DESC LIMIT 0,10" );
        
?>
<div>
	<h3> Search Keyword Statistics <span style="color: gray;">2.2</span> - Most repeated result</h3>

        <div>
            <?php 
            
            if(isset($error) && $error!='')
            {
                echo '<span style="color:red;font-size:12px;font-weight:bold;">'.$error.'</span>';
            }
            
           
            ?>
            
            
          
        </div>


<style type="text/css">
.ss_dasboard_table {
	margin:0px;padding:0px;
	width:100%;
	box-shadow: 10px 10px 5px #888888;
	border:1px solid #000000;
	
	-moz-border-radius-bottomleft:4px;
	-webkit-border-bottom-left-radius:4px;
	border-bottom-left-radius:4px;
	
	-moz-border-radius-bottomright:4px;
	-webkit-border-bottom-right-radius:4px;
	border-bottom-right-radius:4px;
	
	-moz-border-radius-topright:4px;
	-webkit-border-top-right-radius:4px;
	border-top-right-radius:4px;
	
	-moz-border-radius-topleft:4px;
	-webkit-border-top-left-radius:4px;
	border-top-left-radius:4px;
}.ss_dasboard_table table{
    border-collapse: collapse;
        border-spacing: 0;
	width:100%;
	height:100%;
	margin:0px;padding:0px;
}.ss_dasboard_table tr:last-child td:last-child {
	-moz-border-radius-bottomright:4px;
	-webkit-border-bottom-right-radius:4px;
	border-bottom-right-radius:4px;
}
.ss_dasboard_table table tr:first-child td:first-child {
	-moz-border-radius-topleft:4px;
	-webkit-border-top-left-radius:4px;
	border-top-left-radius:4px;
}
.ss_dasboard_table table tr:first-child td:last-child {
	-moz-border-radius-topright:4px;
	-webkit-border-top-right-radius:4px;
	border-top-right-radius:4px;
}.ss_dasboard_table tr:last-child td:first-child{
	-moz-border-radius-bottomleft:4px;
	-webkit-border-bottom-left-radius:4px;
	border-bottom-left-radius:4px;
}.ss_dasboard_table tr:hover td{
	
}
.ss_dasboard_table tr:nth-child(odd){ background-color:#b2b2b2; }
.ss_dasboard_table tr:nth-child(even)    { background-color:#ffffff; }.ss_dasboard_table td{
	vertical-align:middle;
	
	
	border:1px solid #000000;
	border-width:0px 1px 1px 0px;
	text-align:left;
	padding:7px;
	font-size:10px;
	font-family:Arial;
	font-weight:normal;
	color:#000000;
}.ss_dasboard_table tr:last-child td{
	border-width:0px 1px 0px 0px;
}.ss_dasboard_table tr td:last-child{
	border-width:0px 0px 1px 0px;
}.ss_dasboard_table tr:last-child td:last-child{
	border-width:0px 0px 0px 0px;
}
.ss_dasboard_table tr:first-child td{
		background:-o-linear-gradient(bottom, #7f7f7f 5%, #191919 100%);	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #7f7f7f), color-stop(1, #191919) );
	background:-moz-linear-gradient( center top, #7f7f7f 5%, #191919 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr="#7f7f7f", endColorstr="#191919");	background: -o-linear-gradient(top,#7f7f7f,191919);

	background-color:#7f7f7f;
	border:0px solid #000000;
	text-align:center;
	border-width:0px 0px 1px 1px;
	font-size:14px;
	font-family:Arial;
	font-weight:bold;
	color:#ffffff;
}
.ss_dasboard_table tr:first-child:hover td{
	background:-o-linear-gradient(bottom, #7f7f7f 5%, #191919 100%);	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #7f7f7f), color-stop(1, #191919) );
	background:-moz-linear-gradient( center top, #7f7f7f 5%, #191919 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr="#7f7f7f", endColorstr="#191919");	background: -o-linear-gradient(top,#7f7f7f,191919);

	background-color:#7f7f7f;
}
.ss_dasboard_table tr:first-child td:first-child{
	border-width:0px 0px 1px 0px;
}
.ss_dasboard_table tr:first-child td:last-child{
	border-width:0px 0px 1px 1px;
}
</style>
<div class="ss_dasboard_table">
    <form action="" method="post" name="frmKeyword" >
<table cellpadding="1" cellspacing="1" border="1" class="display" id="example" width="100%">
		<tr>
						<td>Sl No</td>
                        <td>keywords</td>
                       	<td>Repeat</td>
                        <td>No. of Result</td>
       </tr>
            <?php
            
            for($i=0;$i<count($rows);$i++)
	   {
                if(is_numeric($rows[$i]->user))
                {
                    $user_info =get_userdata($rows[$i]->user);
                    $user=$user_info->user_login;
                }
                else
                    $user='Non-Registered';
                
           ?>
		<tr class="odd gradeX">
                	<td><?php echo $i+1; ?></td>
			<td><?php echo $rows[$i]->keywords; ?></td>
			
			<td class="center"><?php echo $rows[$i]->repeat_count; ?></td>
                        <td class="center"><?php echo $rows[$i]->search_count; ?></td>
                        
		</tr>
                <?php
                }
            ?>
		
	
</table>
        
    </form>
</div>

</div>

<?php
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