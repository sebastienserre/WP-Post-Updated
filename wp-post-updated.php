<?php
/*
Plugin Name: WP Post Updated
Plugin URI: http://www.thivinfo.com
Description: Send a mail when a post is updated
Version: 1.2.0
Author: Sébastien Serre
Author URI: http://www.thivinfo.com
License: GPL2
Text Domain: wp-post-updated
Domain Path: /languages
*/

class thfo_Plugin

{

    public function __construct()

    {


        include_once plugin_dir_path(__FILE__).'/class/newsletter.php';
        include_once plugin_dir_path( __FILE__ ).'/class/shortcode.php';
        include_once plugin_dir_path( __FILE__ ).'/class/thfo_options.php';
        include_once plugin_dir_path( __FILE__ ).'/class/thfo_subscriber.php';
        include_once plugin_dir_path( __FILE__ ).'/class/thfo_post_subscription.php';

        new thfo_Newsletter();
        new thfo_unsubscribe();
        new thfo_options();
        new thfo_subscriber();
        new thfo_post_subscription();

        register_activation_hook(__FILE__, array('thfo_Newsletter', 'install'));
        register_uninstall_hook(__FILE__, array('thfo_Newsletter', 'uninstall'));

        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action( 'plugins_loaded', array($this,'wppu_load_textdomain' ));
	    add_action( 'admin_init', array($this, 'wppu_add_column') );

	    define( 'PLUGIN_VERSION','1.2.0' );
    }

	public function wppu_add_column() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'thfo_newsletter_email';
		$row = $wpdb->get_results( "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$table_name' AND COLUMN_NAME  = 'post_id' " );
		if (empty($row)) {
			$wpdb->query( "ALTER TABLE $table_name ADD post_id INT (10) " );
		}
		update_option( 'wp_post_updated_version', PLUGIN_VERSION );
	}

    public function wppu_load_textdomain()
    {
        load_plugin_textdomain( 'wp-post-updated', false,dirname( plugin_basename( __FILE__ ) ) . '/languages');
    }


    public function add_admin_menu()

    {

        add_menu_page('WP Post Updated', 'WP Post Updated', 'manage_options', 'wp-post-updated', array($this, 'menu_html'),plugin_dir_url( __FILE__ ) . '/assets/img/icon.png' );
        add_submenu_page('wp-post-updated', 'Abonnés', 'Abonnés', 'manage_options', 'subscriber', array('thfo_subscriber', 'menu_html'));
        add_submenu_page('wp-post-updated', 'Options', 'Options', 'manage_options', 'options', array('thfo_options', 'menu_html'));

    }

    public function menu_html()
    {
        echo '<h1>' . get_admin_page_title() . '</h1>';
        echo '<h2>' . _e('Hello, Many thanx to use this plugin', 'wp-post-updated'); ?> </h2>
        <h2><?php _e('If You Enjoy this plugin... encourage me!', 'wp-post-updated') ?></h2>
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
            <input type="hidden" name="cmd" value="_s-xclick">
            <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
">
            <input type="image" src="https://www.paypalobjects.com/fr_FR/FR/i/btn/btn_donateCC_LG.gif" border="0"
                   name="submit" alt="PayPal - la solution de paiement en ligne la plus simple et la plus sécurisée !">
            <img alt="" border="0" src="https://www.paypalobjects.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
        </form>
        <?php

    }


}

new thfo_Plugin();