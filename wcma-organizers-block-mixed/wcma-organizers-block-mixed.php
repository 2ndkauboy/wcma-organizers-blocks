<?php
/**
 * Plugin Name: WordCamp Mannheim – Organizers Block Mixed
 * Description: A Gutenberg block that displays a list of organizers.
 * Version: 1.0.0
 * Author: Bernhard Kau
 * Text Domain: wcma-organizers-block-mixed
 * Requires at least: 6.7
 * Requires PHP: 8.1
 *
 * @package wcma_organizers_block_mixed
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the Organizers List block.
 */
function wcma_organizers_block_register_mixed() {
	register_block_type( __DIR__ . '/block' );
}

add_action( 'init', 'wcma_organizers_block_register_mixed' );
