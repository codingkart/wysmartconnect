<?php
/**
 * @class    Included_files
 * @category Class
 * @author   Codingkart
 **/

class Included_files_scrub_plugin
{
    public function __construct()
    {
        require_once 'helper.php';        
        require_once 'admin-module/class_admin_module.php';
        require_once 'fetch-ftp-data/class_fetch_traking_data.php';
     
    } 
}
new Included_files_scrub_plugin();
