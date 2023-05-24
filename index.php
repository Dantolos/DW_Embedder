<?php

/**
 * Plugin Name: DW Embedder
 * Description: Allow to create embeds of posts
 * Version: 1.02
 * Author: Aaron Giaimo
 * Author URI: https://github.com/Dantolos
 */

 require_once(__DIR__.'/options.php');



 function post_teaser_embed_endpoint() {
    if (isset($_GET['embed']) && $_GET['embed'] === 'true') {
        // Get the post data
        $post_id = get_the_ID(); // Get the current post ID
        $post_title = get_the_title($post_id);
        $post_permalink = get_permalink($post_id);
        $post_thumbnail = get_the_post_thumbnail_url($post_id) ?: '';
        $post_excerpt = trimString(get_the_excerpt($post_id));
        

        $color = get_option('post_teaser_embed_font_color', '#ffffff');
 
        $home_url = home_url();
        $parsed_url = parse_url($home_url);
        $domain = $parsed_url['host'];
      

        $teaser_html = '
            <style>
                /* Custom CSS styles for the post teaser */
                body {margin:0 !important; }
                a { color: '.$color.'; text-decoration: none; }
                .post-teaser {
                    background-color: '.$color.'15;
                    border-radius: 20px;
                    overflow:hidden;
                    display: flex;
                    flex-direction:row;
                    flex-wrap: nowrap;
                    justify-content:left;
                    align-items:stretch;
                    padding:0;
                    
                    position:relative;
                   
                    height:180px;
                }
                .post-teaser a {
                    /* Add your CSS styles here */
                }
                .dw-teaser-image {
                    width: 40%;
                   background-position: center;
                    background-size: cover;
                }
                .dw-teaser-content {
                    width:60%;
                    padding:20px 30px;
                
                    display:flex;
                    flex-direction:column;
                    justify-content:center;
                }
                .dw-teaser-content::after {
                    position:absolute;
                    content:"⬤⬤⬤⬤";
                    top: 20px;
                    right: 20px;
                    color: '.$color.';
                    font-size: 12px;
                }
                .dw-teaser-content > h2 { margin:10px 0; }
                .dw-teaser-content > p { margin:0; }v
            </style>
            <div>
            <a href="' . esc_url($post_permalink) . '" target="_blank">
                <div class="post-teaser"> 
                    <div class="dw-teaser-image" style="background-image:url(' . esc_url($post_thumbnail) . ');"></div>
                    <div class="dw-teaser-content">
                        <p>'.esc_html($domain).'</p>
                        <h2>' . esc_html($post_title) . 'dsafasf</h2>
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
