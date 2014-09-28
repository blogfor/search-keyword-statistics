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





// Function that outputs the contents of the dashboard widget
function ss_dashboard_widget_function($post, $callback_args ) {
	
	  global $wpdb;
        $rows=$wpdb->get_results( "SELECT * FROM " . SS_TABLE . " ORDER BY repeat_count DESC LIMIT 0,10" );
        
?>
<div>
	<h3> Search Keyword Statistics <span style="color: gray;">1.3</span> - Most repeated result</h3>

        <div>
            <?php 
            
            if(isset($error) && $error!='')
            {
                echo '<span style="color:red;font-size:12px;font-weight:bold;">'.$error.'</span>';
            }
            
           
            ?>
            
            
          
        </div>
<div>
    <form action="" method="post" name="frmKeyword" >
<table cellpadding="1" cellspacing="1" border="1" class="display" id="example" width="100%">
	<thead>
		<tr>
						<th>Sl No</th>
                        <th>keywords</th>
                       	<th>Repeat</th>
                        <th>No. of Result</th>
       </tr>
	</thead>
	<tbody>
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
		
	</tbody>
	<tfoot>
		<tr>
                       
			<th>Sl No</th>
                        <th>keywords</th>
                      		
			<th>Repeat</th>
                         <th>No. of Result</th>
                    
		</tr>
	</tfoot>
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