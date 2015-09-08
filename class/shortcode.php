<?php

/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 04/09/15
 * Time: 19:09
 */
class thfo_Recent
{
    public function __construct()
    {
        add_shortcode('thfo_recent_articles',array($this,'recent_html'));

    }

    public function recent_html($atts, $content)
    {
        $atts= shortcode_atts(array('numberposts'=>5), $atts);
        $posts = get_posts($atts);

        $html = array();
        $html[] = $content;
        $html[]='<ul>';
        foreach ($posts as $post){
            $html[] = '<li><a href="'.get_permalink($post).'">'.$post->post_title.'</a></li>';
        }
        $html[]='</ul>';
        echo implode('',$html);
    }



}