<?php
	
	wp_register_script( 'ss-dashboard-js', WP_PLUGIN_URL . '/search-keyword-statistics/js/jquery.dataTables.js' );
	wp_enqueue_script( 'ss-dashboard-js' );
        
	wp_register_script( 'ss-dashboard-js-ui', WP_PLUGIN_URL . '/search-keyword-statistics/js/jquery-ui.min.js' );
	wp_enqueue_script( 'ss-dashboard-js-ui' );
        
        wp_register_script( 'ss-dashboard-js-ui-date', WP_PLUGIN_URL . '/search-keyword-statistics/js/jquery.ui.datepicker.min.js' );
	wp_enqueue_script( 'ss-dashboard-js-ui-date' );
        
        global $wpdb;
        
        if(isset($_POST) && count($_POST)>0 && isset($_POST['keywords']))
        {
            foreach($_POST['keywords'] as $kw)
            {
                $deleQuery="DELETE FROM ".SS_TABLE." WHERE id='".$kw."'";
                $wpdb->query($deleQuery);
                $msg=true;
            }
            
        }
        
        
        
        if(isset($_POST) && count($_POST)>0 && isset($_POST['ss_search']))
        {
            $search=false;
            if(trim($_POST['ss_search_frm'])=='' || trim($_POST['ss_search_to'])=='' )
            {
            $error='Select both dates';
            }
          else
             {  
                $search=true;
                
                $frm=  date('Y-m-d',strtotime($_POST['ss_search_frm']));
                $to=  date('Y-m-d',strtotime($_POST['ss_search_to']));
                $rows=$wpdb->get_results( "SELECT * FROM " . SS_TABLE . " WHERE  STR_TO_DATE(`query_date`,'%Y%m%d') BETWEEN '".$frm."' AND '". $to."' ORDER BY id DESC" );
             }
        }
        else
        $rows=$wpdb->get_results( "SELECT * FROM " . SS_TABLE . " ORDER BY id DESC" );
        
?>

<link rel="stylesheet" type="text/css" href="<?php echo WP_PLUGIN_URL ?>/search-keyword-statistics/css/demo_table.css">
<link rel="stylesheet" type="text/css" href="<?php echo WP_PLUGIN_URL ?>/search-keyword-statistics/css/style.css">
<link rel="stylesheet" type="text/css" href="<?php echo WP_PLUGIN_URL ?>/search-keyword-statistics/css/jquery-ui.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo WP_PLUGIN_URL ?>/search-keyword-statistics/css/jquery.ui.datepicker.min.css">

<script>
	jQuery(function() {
		jQuery( "#ss_search_frm" ).datepicker();
                jQuery( "#ss_search_to" ).datepicker();
	});
        
        function form_submit()
        {
           
                if(jQuery('ss_search_frm').val()=='' || jQuery('ss_search_to').val()=='' || jQuery('ss_search_frm').val()==undefined || jQuery('ss_search_to').val()==undefined)
                {
                    alert('Please select both dates');
                    return false;
                }
                else
                    return true;
        }
        
        
	</script>
<div>
	<h3> Search Keyword Statistics <span style="color: gray;">2.2</span> </h3>

        <div>
            <?php 
            
            if(isset($error) && $error!='')
            {
                echo '<span style="color:red;font-size:12px;font-weight:bold;">'.$error.'</span>';
            }
            
           
            ?>
                
            <form action="" method="post" enctype="multipart/formdata" name="frmSortSS" class="BFDfrmSortSS" >
                <input type="hidden" name="ss_search" value="<?php echo rand(88,8888);?>" />
                
                From : <input type="text" name="ss_search_frm" id="ss_search_frm" value="" size="10" class="BFDsearchKeyWordtextBox" readonly/>
                To : <input type="text" name="ss_search_to" id="ss_search_to" value="" size="10" class="BFDsearchKeyWordtextBox" readonly/>
                
                <input type="submit" name="ss_search_submit" value="Search" class="ss_search_submit"/>
                
                  <?php
            if(isset($search) && $search==true)
            {
                echo '<span style="color:green;font-size:12px;font-weight:bold; margin-left:20px;"> Result between '.$frm.' To '.$to.' </span>';
            }
            
            if(isset($msg) && $msg==true)
            {
                echo '<span style="color:green;font-size:12px;font-weight:bold; margin-left:20px;"> Delete successfull. </span>';
            }
            ?>
            </form>
            
          
        </div>
<div>
    <form action="" method="post" name="frmKeyword" >
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" width="100%">
	<thead>
		<tr>
                        <th></th>
			<th>Sl No</th>
                        <th>keywords</th>
                        <th>User</th>
			<th>Browser</th>
			<th>Repeat</th>
                        <th>No. of Result</th>
                        <th>Source</th>
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
                    <td><input type="checkbox" name="keywords[]" id="keywords<?php echo $i+1; ?>" value="<?php echo $rows[$i]->id; ?>" /></td>
			<td><?php echo $i+1; ?></td>
			<td><?php echo $rows[$i]->keywords; ?></td>
			<td><?php echo $user; ?></td>
			<td class="center"> <?php echo $rows[$i]->agent; ?></td>
			<td class="center"><?php echo $rows[$i]->repeat_count; ?></td>
                        <td class="center"><?php echo $rows[$i]->search_count; ?></td>
                        <td class="center"><?php echo $rows[$i]->source; ?></td>
		</tr>
                <?php
                }
            ?>
		
	</tbody>
	<tfoot>
		<tr>
                        <th></th>
			<th>Sl No</th>
                        <th>keywords</th>
                        <th>User</th>
			<th>Browser</th>
			<th>Repeat</th>
                         <th>No. of Result</th>
                        <th>Source</th>
		</tr>
	</tfoot>
</table>
        <div style="outline: 2px ridge #1B121C; margin:30px 10px; text-align:right; font-size:18px; font-family:Georgia, 'Times New Roman', Times, serif; padding:5px 5px;">
            <input type="submit" name="keywordSubmit" value="Submit" class="ss_search_submit" />
</div>
    </form>
</div>
<div style="outline: 2px ridge #1B121C; margin:30px 10px; text-align:center; font-size:18px; font-family:Georgia, 'Times New Roman', Times, serif; padding:5px 5px;">
Plugin Developed by : <a href="http://www.blogfordeveloper.com/" title="Blog For Developer" target="_blank">Blogfordeveloper.com</a>
</div>

<div>
<div style="outline: 2px ridge #1B121C; margin:10px 10px; text-align:center; font-size:14px; font-family:Georgia, 'Times New Roman', Times, serif; padding:2px 2px; float:left;">
<h4>Recent Posts from Our Blog</h4>

<div>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<div class="fb-like-box" data-href="https://www.facebook.com/BlogForDevelopers" data-width="500" data-colorscheme="light" data-show-faces="true" data-header="true" data-stream="true" data-show-border="true"></div>
</div>
</div>

<div style="outline: 2px ridge #1B121C; margin:10px 10px; text-align:center; font-size:14px; font-family:Georgia, 'Times New Roman', Times, serif; padding:2px 2px; float:left;">
<h4>Recent Posts from Our Free Photo Stocks</h4>

<div>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div class="fb-like-box" data-href="https://www.facebook.com/indianphotographsnphotography" data-width="500" data-colorscheme="light" data-show-faces="true" data-header="true" data-stream="true" data-show-border="true"></div>
</div>
<div style="clear:both;"></div>
</div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function() {
	jQuery('#example').dataTable();
    });
</script>
</div>