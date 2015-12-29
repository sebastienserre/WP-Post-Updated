<?php

/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 27/08/15
 * Time: 18:12
 */

include_once plugin_dir_path( __FILE__ ).'/newsletterwidget.php';

class thfo_Newsletter
{
    public function __construct()
    {

        add_action('widgets_init', function () {
            register_widget('thfo_Newsletter_Widget');
        });
        add_action('wp_loaded', array($this, 'save_email'));
        add_action('admin_menu', array($this, 'add_admin_menu'), 20);
        add_action('admin_init', array($this, 'register_settings'));
        add_action('wp_loaded', array($this, 'save_email'));
        add_action('post_updated',array($this,'send_newsletter'));
        add_action('wp_loaded',array($this,'unsubscribe'));

    }

    public static function install() {
        global $wpdb;
        $wpdb->query( "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}thfo_newsletter_email(id INT AUTO_INCREMENT PRIMARY KEY, email VARCHAR (255) NOT NULL);" );
    }

    public static function uninstall(){
        global $wpdb;
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}thfo_newsletter_email;");
    }
    /**
     * Vérifie si l'email existe et ajoute en bdd
     */
    public function save_email()
    {
        if (isset($_POST['thfo_newsletter_email']) && !empty($_POST['thfo_newsletter_email'])) {
            global $wpdb;
            $email = $_POST['thfo_newsletter_email'];

            $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}thfo_newsletter_email WHERE email = '$email'");
            if (is_null($row)) {
                $wpdb->insert("{$wpdb->prefix}thfo_newsletter_email", array('email' => $email));
            }
        }
    }

    public function add_admin_menu() {
        $hook=add_submenu_page('wp-post-updated', 'Newsletter', 'Newsletter', 'manage_options', 'thfo_newsletter', array($this, 'menu_html'));
        add_action('load-'.$hook, array($this, 'process_action'));
    }

    public function menu_html()
    {
        echo '<h1>'.get_admin_page_title().'</h1>';
        ?>

        <form method="post" action="options.php">
            <?php settings_fields('thfo_newsletter_settings') ?>
            <?php do_settings_sections('thfo_newsletter_settings') ?>
            <?php submit_button(__('Save')); ?>


        </form>

        <form method="post" action="">
            <input type="hidden" name="send_newsletter" value="1"/>
            <?php submit_button(__('Send','wp-post-updated')); ?>
        </form>

        <?php
    }

    public function register_settings()
    {
        register_setting('thfo_newsletter_settings', 'thfo_newsletter_sender');
        register_setting('thfo_newsletter_settings', 'thfo_newsletter_sender_mail');
        register_setting('thfo_newsletter_settings', 'thfo_newsletter_object');
        register_setting('thfo_newsletter_settings', 'thfo_newsletter_content');

        add_settings_section('thfo_newsletter_section', 'Paramètres d\'envoi', array($this, 'section_html'), 'thfo_newsletter_settings');
        add_settings_field('thfo_newsletter_sender', __('Sender','wp-post-updated'), array($this, 'sender_html'), 'thfo_newsletter_settings', 'thfo_newsletter_section');
        add_settings_field('thfo_newsletter_sender_mail', __('email','wp-post-updated'), array($this, 'sender_mail_html'), 'thfo_newsletter_settings', 'thfo_newsletter_section');
        add_settings_field('thfo_newsletter_object', __('Object','wp-post-updated'), array($this, 'object_html'), 'thfo_newsletter_settings', 'thfo_newsletter_section');
        add_settings_field('thfo_newsletter_content', __('Content','wp-post-updated'), array($this, 'content_html'), 'thfo_newsletter_settings', 'thfo_newsletter_section');


    }

    public function section_html()

    {

        echo '<p>'.__('Advise about outgoing parameters.','wp-post-updated').'</p>';

    }

    public function sender_html()
    {?>
        <input type="text" name="thfo_newsletter_sender" value="<?php echo get_option('thfo_newsletter_sender')?>"/>
        <?php
    }

	public function sender_mail_html()
	{?>
		<input type="email" name="thfo_newsletter_sender_mail" value="<?php echo get_option('thfo_newsletter_sender_mail')?>"/>
		<?php
	}


	public function object_html()

    {?>

        <input type="text" name="thfo_newsletter_object" value="<?php echo get_option('thfo_newsletter_object')?>"/>
        <?php


    }


    public function content_html()

    {
        wp_editor(get_option('thfo_newsletter_content'),'thfo_newsletter_content' );
    }

    public function process_action()
    {

        if (isset($_POST['send_newsletter'])) {

            $this->send_newsletter();

        }

    }


    public function send_newsletter()
    {
        $post_type = get_post_type(get_the_ID());
        $post = get_option('thfo_post_type');
	    $sender = get_option('thfo_newsletter_sender');
	    $sender_mail = get_option('thfo_newsletter_sender_mail');

        if (isset($post) && $post == $post_type) {
            global $wpdb;
            $recipients = $wpdb->get_results("SELECT email FROM {$wpdb->prefix}thfo_newsletter_email");
            foreach ($recipients as $_recipient) {
                $object = get_option('thfo_newsletter_object', 'Newsletter');
                $content = apply_filters('the_content', get_option('thfo_newsletter_content'));
                $content .= '<p>' . __('To unsubscribe to this mail please follow this link: ', 'wp-post-updated');
                echo esc_url(home_url('/')) . '<p>';
                $content .= '<a href="'.home_url('wppu-unsubscribe').'" >'. __('Unsubscribe','wp-post-updated').'</a>';
                $sender = get_option('thfo_newsletter_sender', 'no-reply@example.com');
                $headers[] = 'Content-Type: text/html; charset=UTF-8';

	            $headers[] = 'From:'. $sender.'<'.$sender_mail.'>';
	            //$headers[] = 'From: ' . $sender;
                $result = wp_mail($_recipient->email, $object, $content, $headers);

                remove_filter( 'wp_mail_content_type', 'set_html_content_type' );

                function set_html_content_type() {
                    return 'text/html';
                }
            }
        }
    }

    public function unsubscribe()
    {
        if(isset($_GET['mail']))
        {
            echo $_GET['mail'];
        }
    }
}