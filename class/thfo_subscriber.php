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

	    add_action('admin_menu', array($this, 'wppu_delete_subscriber'));


    }



    public static function subscriber_list(){
        global $wpdb;
        $row=$wpdb->get_row("SELECT email FROM {$wpdb->prefix}thfo_newsletter_email");
        if (is_null($row)){
            echo __('No Subscriber at the moment', 'wp-post-updated');;
        }

        if (!is_null($row)){
            $data = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}thfo_newsletter_email", ARRAY_A);
	        var_dump($data);
            ?>
            <h2><?php _e('Subscribers list','wp-post-updated'); ?></h2>
	        <table>
		        <tr>
			        <th><?php _e('Email', 'wp-post-updated') ?></th>
			        <th><?php _e('Post', 'wp-post-updated') ?></th>
			        <th><?php _e('Delete', 'wp-post-updated') ?></th>
		        </tr>
	        <?php foreach ($data as $datas){
		        $url = get_the_permalink($datas['post_id']);
		        if ($datas['post_id'] == 0){
			        $postname = __('All post', 'wp-post-updated');
			        $url = '#';
		        } else {
			        $postname = get_the_title( $datas['post_id'] );
		        }

		        ?>
		        <tr>
			        <td><?php echo $datas['email'] ?></td>
			        <td><a href="<?php echo $url; ?>"> <?php echo $postname; ?></a></td>
			        <td>
				        <?php
				        $id = $datas['id'];
				        $url = admin_url( 'admin.php?page=' );
				        $url .= basename(dirname( __DIR__));
				        $url .= '&id='. $id .'&delete=yes';
				        ?>
				        <a href="<?php echo esc_url($url); ?>" title="<?php _e('Delete', 'thfo-mail-alert') ?>"><span class="dashicons dashicons-trash"></span> </a> </td>
		        </tr>
	        <?php } ?>
	        </table>

        <?php }
    }

	public function wppu_delete_subscriber(){
		if (isset($_GET['delete']) && $_GET['delete'] == 'yes'){
			$id = $_GET['id'];
			global $wpdb;
			$wpdb->delete("{$wpdb->prefix}thfo_newsletter_email",array('id' => $id));
		}
	}

    public static function menu_html(){
        echo '<h1>'.get_admin_page_title().'</h1>';
        echo self::subscriber_list();
    }
}