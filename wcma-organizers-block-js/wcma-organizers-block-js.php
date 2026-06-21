<?php
/**
 * Plugin Name: WordCamp Mannheim – Organizers Block JS
 * Description: A Gutenberg block that displays a list of organizers.
 * Version: 1.0.0
 * Author: Bernhard Kau
 * Text Domain: wcma-organizers-block-js
 * Plugin URI: https://github.com/wcma/wcma-organizers-block
 * Requires at least: 6.7
 * Requires PHP: 8.1
 *
 * @package wcma_organizers_block_JS
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the Organizers List block.
 */
function wcma_organizers_block_register_js() {
	register_block_type( __DIR__ . '/build' );
}

add_action( 'init', 'wcma_organizers_block_register_js' );
