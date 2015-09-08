<?php

/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 06/09/15
 * Time: 11:57
 */
class thfo_options
{
    public function __construct(){
    add_action('admin_init', array($this, 'register_setting'));


    }

    public static function type_list()
    { ?>
        <form method="post" action="options.php" xmlns="http://www.w3.org/1999/html">
            <?php settings_fields('thfo_options_settings') ?>
            <?php do_settings_sections('thfo_options_settings') ?>
            <?php submit_button(__('Save','thfo_wppu')) ?>
        </form>
        <?php
    }

    public function register_setting(){
        register_setting('thfo_options_settings','thfo_post_type');

        add_settings_section('thfo_option_section', __('Post type to follow','thfo_wppu'), array($this, 'section_html'), 'thfo_options_settings');
        add_settings_field('thfo_post_type', __('Post Type','thfo_wppu'), array($this,'select_html'), 'thfo_options_settings', 'thfo_option_section');

    }

    public function section_html()
    { ?>
        <h2><?php _e('Please select post type you want to be advised by mail in any update', 'thfo_wppu'); ?></h2>
       <?php
    }

    public function select_html()
    {
    $post_types = get_post_types('', 'names');
        ?>
           <?php foreach ($post_types as $post_type) {
        $type = get_option('thfo_post_type');
        ?>
                <input type="radio" name="<?php echo 'thfo_post_type'; ?>" value="<?php echo $post_type; ?>" <?php if(!empty($type)&& $type== $post_type) { echo 'checked=checked';} ?>><?php echo $post_type; ?>
            <?php } ?>
        <?php
    }


    public static function menu_html(){
        echo '<h1>'.get_admin_page_title().'</h1>';
        echo self::type_list();
    }

}