<?php

	
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
	font-size:13px;
	/*font-family:Arial;*/
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
?>