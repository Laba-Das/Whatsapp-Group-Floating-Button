<?php
/*
/*
 * Plugin Name:       WhatsApp Group Floating Button
 * Plugin URI:        https://www.Teckshop.net/our-plugin/
 * Description:       A simple WordPress plugin To add a WhatsApp Group Floating Button Or Icon In your Wordpress Website.
 * Version:           1.0.1
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Teckshop.net
 * Author URI:        https://www.Teckshop.net
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://www.Teckshop.net/our-plugin/
 * Text Domain:       my-basics-plugin
 * Domain Path:       /languages
 */


// Add WhatsApp button to the footer
function add_whatsapp_button() {
    $phone_number = get_option('whatsapp_phone_number');
    $image_url = get_option('whatsapp_image_url');
    $text_before = get_option('whatsapp_text_before');
    $theme_color = get_option('whatsapp_theme_color');

    if ($phone_number && $image_url) {
    echo '<div class="whatsapp-float">';
    
    // Open a container for both the text and icon
    echo '<div class="whatsapp-link-container">';
    
    // Open the anchor tag here
    echo '<a href="' . esc_attr($phone_number) . '" target="_blank">';

    if ($text_before) {
        echo '<span class="whatsapp-text" style="background-color:' . esc_attr($theme_color) . '">' . esc_html($text_before) . '</span>';
    }

    echo '<img src="' . esc_url($image_url) . '" alt="WhatsApp" class="whatsapp-icon">';

    // Close the anchor tag here
    echo '</a>';

    // Close the container for text and icon
    echo '</div>';

    echo '</div>';
}

}

add_action('wp_footer', 'add_whatsapp_button');

// Add CSS to make the button float and style the WhatsApp icon and text
function whatsapp_float_css() {
    echo '<style>
	.whatsapp-link-container a {
    display: flex;
    align-items: center;
    justify-content: center;
}
        .whatsapp-float {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
			align-items: center;
    justify-content: center;
    display: flex;
	animation: bounce 2s infinite;

        }
        .whatsapp-text {
    font-size: 16px;
    color: white;
    font-weight: 600;
    padding: 5px 10px;
    border-radius: 5px;
    margin-right: 8px;
    
}
        .whatsapp-text:hover {
            animation: none; /* Disable animation on hover */
        }
		.whatsapp-float:hover {
		animation: none; /* Disable animation on hover */
        }
        .whatsapp-icon {
            width: 60px; /* Adjust the size as needed */
            height: auto;
			align-items: center;
    justify-content: center;
    display: flex;

        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-20px);
            }
            60% {
                transform: translateY(-10px);
            }
        }
    </style>';
}

add_action('wp_head', 'whatsapp_float_css');

// Add settings page to the dashboard
function whatsapp_button_settings_page() {
    add_menu_page(
        'WhatsApp Button Settings',
        'WhatsApp Button',
        'manage_options',
        'whatsapp-button-settings',
        'whatsapp_button_settings_content',
        'dashicons-whatsapp' // Change the settings page icon to the WhatsApp icon
    );
}

add_action('admin_menu', 'whatsapp_button_settings_page');

// Define the content of the settings page
function whatsapp_button_settings_content() {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (isset($_POST['whatsapp_settings_nonce'])) {
        if (wp_verify_nonce($_POST['whatsapp_settings_nonce'], 'whatsapp_settings_nonce')) {
            update_option('whatsapp_phone_number', sanitize_text_field($_POST['whatsapp_phone_number']));
            update_option('whatsapp_image_url', esc_url($_POST['whatsapp_image_url']));
            update_option('whatsapp_text_before', sanitize_text_field($_POST['whatsapp_text_before']));
            update_option('whatsapp_theme_color', sanitize_hex_color($_POST['whatsapp_theme_color'])); // Add this line
            echo '<div class="updated"><p>Settings updated successfully!</p></div>';
        }
    }

    $phone_number = get_option('whatsapp_phone_number');
    $image_url = get_option('whatsapp_image_url');
    $text_before = get_option('whatsapp_text_before');
    $theme_color = get_option('whatsapp_theme_color'); // Add this line

    ?>
    <div class="wrap">
        <h2>WhatsApp Button Settings</h2>
        <form method="post">
            <?php wp_nonce_field('whatsapp_settings_nonce', 'whatsapp_settings_nonce'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="whatsapp_phone_number">WhatsApp Link:</label></th>
                    <td><input type="text" name="whatsapp_phone_number" id="whatsapp_phone_number" value="<?php echo esc_attr($phone_number); ?>" /></td>
                </tr>
                <tr>
                    <th scope="row"><label for="whatsapp_image_url">WhatsApp Icon Image URL:</label></th>
                    <td><input type="text" name="whatsapp_image_url" id="whatsapp_image_url" value="<?php echo esc_url($image_url); ?>" /></td>
                </tr>
                <tr>
                    <th scope="row"><label for="whatsapp_text_before">Text Before WhatsApp Icon:</label></th>
                    <td><input type="text" name="whatsapp_text_before" id="whatsapp_text_before" value="<?php echo esc_html($text_before); ?>" /></td>
                </tr>
                <tr> <!-- Add this section -->
                    <th scope="row"><label for="whatsapp_theme_color">Text Background Color:</label></th>
                    <td><input type="color" name="whatsapp_theme_color" id="whatsapp_theme_color" value="<?php echo esc_attr($theme_color); ?>" /></td>
                </tr>
            </table>
            <p><input type="submit" class="button-primary" value="Save Settings" /></p>
        </form>
    </div>
    <?php
}
