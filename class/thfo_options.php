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
    <?php }

	public static function create_page() { ?>
		<h2><?php _e( 'Create the unsubscribe page', 'thfo_wppu' ); ?></h2>
		<form method="post" action="#">
			<input type="hidden" name="create" value="1">
			<?php submit_button( __( 'Create', 'thfo_wppu' ) ); ?>
		</form>
		<?php
		if(isset($_POST['create']) && $_POST['create'] === '1'){
			$post = array(
				'ID'                    => '',
				'post_content'          => '[thfo_unsubscribe_nl]',
				'post_name'             => 'wppu-unsubscribe',
				'post_title'            => __('unsubscribe','thfo_wppu'),
				'post_status'           => 'publish',
				'post_type'             => 'page',
				'post_author'           => '',
				'ping_status'           => 'closed',
				'post_parent'           => 0,
				'menu_order'            => 0,
				'to_ping'               => '',
				'pinged'                => '',
				'post_password'         => '',
				'guid'                  => '',// Skip this and let Wordpress handle it, usually.
				'post_content_filtered' => '',// Skip this and let Wordpress handle it, usually.
				'post_excerpt'          => '', // For all your post excerpt needs.
				'post_date'             => the_time( 'd - m - Y H:i:s' ),
				'post_date_gmt'         => the_time( 'd - m - Y H:i:s' ), // The time post was made, in GMT.
				'comment_status'        => 'closed', // Default is the option 'default_comment_status', or 'closed'.
				'post_category'         => '', // Default empty.
				'tags_input'            => '',
				'tax_input'             => '',
				'page_template'         => '',
			);

			wp_insert_post( $post );
		}


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
	    echo self::create_page();
    }


}