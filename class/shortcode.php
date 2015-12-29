<?php

/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 04/09/15
 * Time: 19:09
 */
class thfo_unsubscribe
{
    public function __construct()
    {
        add_shortcode('thfo_unsubscribe_nl',array($this,'unsubscribe_html'));

    }

    public function unsubscribe_html()
    { ?>
        <form class="thfo_unsubscribe" method="post" action="#">
            <label><?php _e('Please add your mail', 'wp-post-updated'); ?></label>
            <input type="email" name="email" />
            <input type="submit" name="delete" value="<?php _e('unsubscribe','wp-post-updated'); ?>" />
        </form>
    <?php

        if(isset($_POST['delete']) && !empty($_POST['email']))
        {
            $mail = $_POST['email'];
        }
        if(isset($mail) && (!empty($mail)))
        {
	        global $wpdb;
	        $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}thfo_newsletter_email WHERE email = '$mail'");
	        if (!is_null($row)) {
		        $wpdb->delete( "{$wpdb->prefix}thfo_newsletter_email", array( 'email' => $mail ) );
		        echo _e('Your mail adress has been deleted from our database!','wp-post-updated');
	        }else{
		        echo _e('Your mail adress isn\'t in our database!','wp-post-updated');
	        }
        }

    }
}