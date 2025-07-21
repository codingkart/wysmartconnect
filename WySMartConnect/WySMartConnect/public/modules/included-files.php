<?php

/**
 * @class    Included_files
 * @category Class
 * @author   Codingkart
 * */
class Included_files
{

    public function __construct()
    {
        require_once 'helper.php';
        require_once 'global-module/class-global-module.php';
        require_once 'admin/class-admin-settings.php';
        require_once 'shop-page/class-shop-page.php';
        require_once 'product-page/class-product-page.php';
    }
}

new Included_files();
