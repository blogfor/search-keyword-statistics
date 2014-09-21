<?php
global $wpdb;

if(get_option('SS_BG_VERSION')==null || get_option('SS_BG_VERSION') < 11)
{
    

            $query =  "ALTER TABLE " . SS_TABLE . " ADD  `search_count` INT NOT NULL DEFAULT  '0'";
            $wpdb->query( $query );
            
            update_option('SS_BG_VERSION',SS_BG_VERSION);
}

?>