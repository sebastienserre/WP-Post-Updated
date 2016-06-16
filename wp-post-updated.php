<?php
/*
Plugin Name: WP Post Updated
Plugin URI: http://www.thivinfo.com
Description: Send a mail when a post is updated
Version: 1.3.3
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
	    add_action( 'admin_init', array($this, 'wppu_register_admin_style') );

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
	    <a href="https://www.paypal.me/sebastienserre" title="<?php __('Donate', 'wp-post-updated') ?>"><button class="donate-btn"><?php _e('Donate', 'wp-post-updated') ?></button></a>
        <?php

    }

	public function wppu_register_admin_style(){
		wp_enqueue_style('thfo_mailalert_admin_style', plugins_url( 'assets/css/admin-styles.css',__FILE__ ));
	}


}

new thfo_Plugin();