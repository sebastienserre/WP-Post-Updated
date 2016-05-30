<?php

/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 27/05/16
 * Time: 11:40
 */
class thfo_post_subscription {
	
	public function __construct() {
		add_filter('the_content', array($this, 'thfo_add_subscription_field'));
	}

	/**
	 * Add a subscription form in bottom of selected post (in options)
	 * @author sebastienserre
	 * @param $content
	 * @return string
	 */

	public function thfo_add_subscription_field($content) {
		$type        = get_option( 'thfo_post_type' );
		$cur_post_type = get_post_type();
		$i             = 0;

			if ( in_array( $cur_post_type, $type, true ) ) {
				$content .= $cur_post_type;
				$content .= '<div class="thfo_mail">';
				$content .= '<p>' . __( 'Want to be kept informed?', 'wp-post-updated' ) . '</p>';
				$content .= '<form method="post">';
				$content .= '<input name="email" type="email" placeholder="' . __( 'Please enter your email here', 'wp-post-updated' ) . '" >';
				$content .= '<input name="id" type="text" value=" ' . get_the_id() . ' " hidden > ';
				$content .= '<input name="thfo_newsletter_email" type="submit">';
				$content .= '</form>';
				$content .= '</div>';

				$content = apply_filters( 'thfo_subcription_form', $content );

				$i ++;

				return $content;

			}

		}




}