<?php

function post_teaser_embed_settings_page() {
    add_options_page(
        'Post Teaser Embed Settings',
        'Post Teaser Embed',
        'manage_options',
        'post-teaser-embed-settings',
        'post_teaser_embed_settings_page_content'
    );
}
add_action('admin_menu', 'post_teaser_embed_settings_page');


function post_teaser_embed_settings_page_content() {
    // Save settings if form is submitted
    if (isset($_POST['post_teaser_embed_save_settings'])) {
        check_admin_referer('post_teaser_embed_settings');
        $font_color = sanitize_hex_color($_POST['post_teaser_embed_font_color']);
        update_option('post_teaser_embed_font_color', $font_color);
        echo '<div class="notice notice-success"><p>Settings saved.</p></div>';
    }

    $font_color = get_option('post_teaser_embed_font_color', '#000000');
    $iFrame_snippet = '<iframe src="***URL***/?embed=true" width="100%" height="180px"></iframe>';

    // Display the settings form
    ?>
    <style>
        .iframe-snipped { background-color: #2b2b2b; padding: 25px; border-radius: 10px; }
        .iframe-snipped  { color: white !important;}
    </style>
    <div class="wrap">
        <h1>Post Teaser Embed Settings</h1>
        <form method="post">
            <?php wp_nonce_field('post_teaser_embed_settings'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="post_teaser_embed_font_color">Font Color</label></th>
                    <td>
                        <input type="text" name="post_teaser_embed_font_color" id="post_teaser_embed_font_color" class="regular-text" value="<?php echo esc_attr($font_color); ?>" />
                    </td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" name="post_teaser_embed_save_settings" class="button button-primary" value="Save Settings" />
            </p>
        </form>
        <div class="iframe-snipped"><code><?php
        echo esc_attr($iFrame_snippet);
        ?></code></div>

    </div>
    <?php
}
