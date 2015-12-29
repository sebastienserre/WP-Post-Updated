<?php

/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 27/08/15
 * Time: 17:27
 */
class thfo_Page_Title
{
    public function __construct()
    {
        add_filter('wp_title', array($this, 'modify_page_title'), 20);
    }

    public function modify_page_title($title)
    {
        return $title . ' | Avec le plugin des zéros !';
    }


}