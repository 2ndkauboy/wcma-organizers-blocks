<?php
/**
 * Plugin Name: WordCamp Mannheim – Organizers Block PHP-only
 * Description: A Gutenberg block that displays a list of organizers.
 * Version: 1.0.0
 * Author: Bernhard Kau
 * Text Domain: wcma-organizers-block-php-only
 * Requires at least: 7.0
 * Requires PHP: 8.1
 *
 * @package wcma_organizers_block_php_only
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the Organizers List block.
 */
function wcma_organizers_block_register() {
	wp_register_style(
		'wcma-organizers-block-php-only-style',
		plugin_dir_url( __FILE__ ) . 'style.css',
		array(),
		'1.0.0'
	);

	register_block_type(
		'wcma/organizers-list-php-only',
		array(
			'title'           => __( 'Organizers List PHP-only', 'wcma-organizers-block-php-only' ),
			'description'     => __( 'Display a list of the organizers.', 'wcma-organizers-block-php-only' ),
			'icon'            => 'groups',
			'render_callback' => 'wcma_organizers_block_php_only_render',
			'style_handles'   => array( 'wcma-organizers-block-php-only-style' ),
			'supports'        => array(
				'autoRegister' => true,
				'color'        => array(
					'heading' => true,
					'link'    => true,
				),
			),
			'attributes'      => array(
				'showAvatar'  => array(
					'type'    => 'boolean',
					'label'   => __( 'Show avatar', 'wcma-organizers-block-php-only' ),
					'default' => true,
				),
				'avatarSize'  => array(
					'type'    => 'integer',
					'label'   => __( 'Avatar size', 'wcma-organizers-block-php-only' ),
					'default' => 128,
					'enum'    => array( 48, 96, 128, 192 ),
				),
				'showName'    => array(
					'type'    => 'boolean',
					'label'   => __( 'Show name', 'wcma-organizers-block-php-only' ),
					'default' => true,
				),
				'nameType'    => array(
					'type'    => 'string',
					'label'   => __( 'Name type', 'wcma-organizers-block-php-only' ),
					'default' => 'display_name',
					'enum'    => array( 'display_name', 'first_name', 'nickname' ),
				),
				'showBio'     => array(
					'type'    => 'boolean',
					'label'   => __( 'Show bio', 'wcma-organizers-block-php-only' ),
					'default' => true,
				),
				'showLink'    => array(
					'type'    => 'boolean',
					'label'   => __( 'Show link to author archive', 'wcma-organizers-block-php-only' ),
					'default' => true,
				),
				'linkText'    => array(
					'type'    => 'string',
					'label'   => __( 'Link text prefix', 'wcma-organizers-block-php-only' ),
					'default' => '',
				),
				'displayType' => array(
					'type'    => 'string',
					'label'   => __( 'Display type', 'wcma-organizers-block-php-only' ),
					'default' => 'list',
					'enum'    => array( 'list', 'grid' ),
				),
				'gridColumns' => array(
					'type'    => 'integer',
					'label'   => __( 'Grid columns', 'wcma-organizers-block-php-only' ),
					'default' => 3,
				),
			),
		)
	);
}

add_action( 'init', 'wcma_organizers_block_register' );

/**
 * Renders the Organizers List block on the frontend.
 *
 * @param array $attributes Block attributes.
 *
 * @return string Block HTML output.
 */
function wcma_organizers_block_php_only_render( $attributes ) {
	$show_avatar  = (bool) ( $attributes['showAvatar'] ?? true );
	$avatar_size  = (int) ( $attributes['avatarSize'] ?? 96 );
	$show_name    = (bool) ( $attributes['showName'] ?? true );
	$name_type    = in_array( $attributes['nameType'] ?? '', array( 'display_name', 'first_name', 'nickname' ), true )
		? $attributes['nameType']
		: 'display_name';
	$show_bio     = (bool) ( $attributes['showBio'] ?? true );
	$show_link    = (bool) ( $attributes['showLink'] ?? true );
	$link_text    = (string) ( $attributes['linkText'] ?? '' );
	$display_type = in_array( $attributes['displayType'] ?? '', array( 'list', 'grid' ), true )
		? $attributes['displayType']
		: 'list';
	$grid_columns = max( 1, (int) ( $attributes['gridColumns'] ?? 3 ) );

	$organizers = get_users(
		array(
			'has_published_posts' => array( 'post' ),
			'orderby'             => 'display_name',
			'order'               => 'ASC',
		)
	);

	if ( empty( $organizers ) ) {
		if ( ! wp_is_serving_rest_request() ) {
			return '';
		}

		return sprintf(
			'<div class="components-placeholder"><div class="components-placeholder__fieldset">%s</div></div>',
			esc_html__( 'No organizers found. Make sure there are users with published posts.', 'wcma-organizers-block-php-only' )
		);
	}

	$extra_attrs = array( 'class' => 'is-display-' . $display_type );
	if ( 'grid' === $display_type ) {
		$extra_attrs['style'] = 'grid-template-columns: repeat(' . $grid_columns . ', 1fr);';
	}

	$wrapper_attributes = get_block_wrapper_attributes( $extra_attrs );

	$html = '<ul ' . $wrapper_attributes . '>';

	foreach ( $organizers as $organizer ) {
		$name = 'display_name' === $name_type
			? $organizer->display_name
			: get_the_author_meta( $name_type, $organizer->ID );

		$item_class = 'wp-block-wcma-organizers-list-php-only__item';
		if ( $show_avatar ) {
			$item_class .= ' has-avatar';
		}
		$html .= '<li class="' . esc_attr( $item_class ) . '">';

		if ( $show_avatar ) {
			$html .= '<div class="wp-block-wcma-organizers-list-php-only__avatar">';
			$html .= get_avatar( $organizer->ID, $avatar_size );
			$html .= '</div>';
		}

		$html .= '<div class="wp-block-wcma-organizers-list-php-only__content">';

		if ( $show_name ) {
			$html .= '<h3 class="wp-block-wcma-organizers-list-php-only__name">';
			$html .= esc_html( $name );
			$html .= '</h3>';
		}

		if ( $show_bio ) {
			$bio = get_the_author_meta( 'description', $organizer->ID );
			if ( $bio ) {
				$html .= '<p class="wp-block-wcma-organizers-list-php-only__bio">';
				$html .= esc_html( $bio );
				$html .= '</p>';
			}
		}

		if ( $show_link ) {
			$html .= '<a class="wp-block-wcma-organizers-list-php-only__link" href="' . esc_url( get_author_posts_url( $organizer->ID ) ) . '">';
			if ( '' !== $link_text ) {
				$html .= esc_html( $link_text ) . ' ' . esc_html( $organizer->first_name );
			} else {
				$html .= sprintf(
				/* translators: %s: organizer display name */
					esc_html__( 'Posts by %s', 'wcma-organizers-block-php-only' ),
					esc_html( $organizer->first_name )
				);
			}
			$html .= '</a>';
		}

		$html .= '</div>';
		$html .= '</li>';
	}

	$html .= '</ul>';

	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	return $html;
}
