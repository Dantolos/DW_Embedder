<?php

/**
 * Plugin Name: DW Embedder
 * Description: Allow to create embeds of posts
 * Version: 1.09
 * Author: Aaron Giaimo
 * Author URI: https://github.com/Dantolos
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once(__DIR__.'/options.php');



function post_teaser_embed_endpoint() {
    if (isset($_GET['embed']) && $_GET['embed'] === 'true') {
        // Get the post data
        $post_id = get_the_ID(); // Get the current post ID
        $post_title = get_the_title($post_id);
        $post_permalink = get_permalink($post_id);
        $post_thumbnail = get_the_post_thumbnail_url($post_id) ?: '';
        $post_excerpt = trimString(get_field('lead', $post_id)) ?: '';
        
        $css_file_path = __DIR__.'/style.css';
        $css_string = file_get_contents($css_file_path);

        $color = get_option('post_teaser_embed_font_color', '#ffffff');
 
        $domain = '';
        $home_url = home_url();
        $parsed_url = parse_url($home_url);
        if (isset($parsed_url['host'])) {
            $host = $parsed_url['host'];
            $host_parts = explode('.', $host);
            array_pop($host_parts);
            $host_without_tld = implode('.', $host_parts);
            $home_url = $host_without_tld;
        } 
        $domain = $home_url;
        


        $teaser_html = '
            <style> 
                :root { 
                    --dw_maincolor: '.$color.';
                    --dw_lightcolor: '.$color.'20;
                }                
                '.$css_string.'
            </style>
            <div>
            <a href="' . esc_url($post_permalink) . '" target="_blank">
                <div class="post-teaser"> 
                    <div class="dw-teaser-image" style="background-image:url(' . esc_url($post_thumbnail) . ');"></div>
                    <div class="dw-teaser-content">
                        <p>'.esc_html($domain).'</p>
                        <h2>' . esc_html($post_title) . '</h2>
                        <p style="font-size:18px;">'.esc_html($post_excerpt).' ...</p>
                    </div>
                </div>
            </a>
            </div>
        ';

        header('Content-Type: text/html');
        echo $teaser_html;
        exit;
    }
}
add_action('template_redirect', 'post_teaser_embed_endpoint');


function trimString($text) {
    $trimmedText = substr($text, 0, 80);
    $lastSpaceIndex = strrpos($trimmedText, ' ');
    return ($lastSpaceIndex !== false) ? substr($trimmedText, 0, $lastSpaceIndex) : $trimmedText;
}

?>
