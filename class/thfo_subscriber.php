<?php

/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 06/09/15
 * Time: 12:13
 */
class thfo_subscriber
{
    public function __construct(){
        //add_action('admin_init', array($this, 'subscriber_list'));


    }



    public static function subscriber_list(){
        global $wpdb;
        $row=$wpdb->get_row("SELECT email FROM {$wpdb->prefix}thfo_newsletter_email");
        if (is_null($row)){
            echo __('No Subscriber at the moment', 'wp-post-updated');;
        }else{
            $data = $wpdb->get_results("SELECT email FROM {$wpdb->prefix}thfo_newsletter_email", ARRAY_A);
            ?>
            <h2><?php _e('Subscribers list','wp-post-updated'); ?></h2>
                <ul>
                <?php
                foreach ($data as $datas){
                    ?>
                    <li><?php echo $datas['email']; ?></li>
                    <?php
                }
            ?>
            </ul>
        <?php }
    }

    public static function menu_html(){
        echo '<h1>'.get_admin_page_title().'</h1>';
        echo self::subscriber_list();
    }
}