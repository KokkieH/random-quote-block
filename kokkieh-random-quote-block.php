<?php
/**
 * Plugin Name:       Random quote block
 * Plugin URI:        https://github.com/KokkieH/random-quote-block
 * Description:       Gutenberg block that displays a random quote
 * Version:           1.0.0
 * Requires at least: 5.3
 * Requires PHP:      5.6
 * Author:            Herman Kok
 * Author URI:        https://kokkieh.blog/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       kokkieh-random-quote-block
 * Domain Path:       /languages
 */

// testing that plugin works
function kokkieh_quote_hello_world () {
    _e( 'Hello World!', 'kokkieh-random-quote-block' );
}

add_filter( 'the_content', 'kokkieh_quote_hello_world' );

// Internationalize plugin

add_action( 'init', 'kokkieh_quote_load_textdomain' );

function kokkieh_quote_load_textdomain() {
    load_plugin_textdomain( 
        'kokkieh-random-quote-block',
        false,
        'kokkieh-random-quote-block/languages'
    );
}

// Function to add API attribution text

function kokkieh_quote_attribution_link() {
    $kokkieh_quote_api_url = 'http://www.quotes.net/quotes_api.php';
    return sprintf(
        esc_html__( 'Powered by the STANDS4 Web Services %s', 'kokkieh-random-quote-block'),
        sprintf(
            '<a href="%s">%s</a>',
            $kokkieh_quote_api_url,
            esc_html__( 'Quotes API', 'kokkieh-random-quote-block' )
        )
    );
}

// Add Settings page

const SETTINGS_SECTION = 'kokkieh_quote_main';
const PAGE_SLUG = 'kokkieh_random_quote_block';

// Create Settings menu item

add_action( 'admin_menu', 'kokkieh_quote_create_menu' );

function kokkieh_quote_create_menu() {

    add_options_page( 
        __( 'Random Quote Block Settings', 'kokkieh-random-quote-block' ),
        __( 'Random Quote Block', 'kokkieh-random-quote-block' ),
        'manage_options',
        PAGE_SLUG,
        'kokkieh_quote_settings_page'
     );
}

// Create Settings page

function kokkieh_quote_settings_page() {
    ?>
    <div class="wrap">
        <h2><?php _e( 'Random Quote Block', 'kokkieh-random-quote-block' ) ?></h2>
        <form action="options.php" method="POST">
            <?php

            settings_fields( PAGE_SLUG );
            do_settings_sections( PAGE_SLUG );
            submit_button ( __( 'Save Changes', 'kokkieh-random-quote-block' ), 'primary' );

            ?>
        </form>
        <p><?php echo kokkieh_quote_attribution_link() ?></p>
    </div>
    <?php
}

// Add settings to page

add_action( 'admin_init', 'kokkieh_quote_register_settings' );

function kokkieh_quote_register_settings() {
    add_settings_section(
        SETTINGS_SECTION,
        __( 'API Credentials', 'kokkieh-random-quote-block' ),
        'kokkieh_quote_section_text',
        PAGE_SLUG
    );
    add_settings_field(
        'kokkieh_quote_username',
        __( 'Username', 'kokkieh-random-quote-block' ),
        'kokkieh_quote_render_username',
        PAGE_SLUG,
        SETTINGS_SECTION
    );
    add_settings_field(
        'kokkieh_quote_api_key',
        __( 'API Key', 'kokkieh-random-quote-block' ),
        'kokkieh_quote_render_api_key',
        PAGE_SLUG,
        SETTINGS_SECTION
    );

    register_setting( PAGE_SLUG, 'kokkieh_quote_username' );
    register_setting( PAGE_SLUG, 'kokkieh_quote_api_key' );
}

// Insert section description

function kokkieh_quote_section_text() {
    echo '<p>' . __('Quotes.net API Credentials', 'kokkieh-random-quote-block' ) . '</p>';
}

// Insert form fields

function kokkieh_quote_render_username() {
    kokkieh_quote_render_options( 'kokkieh_quote_username');
}

function kokkieh_quote_render_api_key() {
    kokkieh_quote_render_options( 'kokkieh_quote_api_key' );
}

function kokkieh_quote_render_options( $option_name ) {
    $options = get_option( $option_name );
    $name = $options[ 'name' ];
    echo "<input id='name' 
        name='" . esc_attr( $option_name ) . "[name]'
        type='text' 
        value='" . esc_attr( $name ) . "'/>";
}