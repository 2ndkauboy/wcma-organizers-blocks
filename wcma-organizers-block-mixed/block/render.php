<?php
/**
 * Server-side render template for the Organizers List block.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block default content.
 * @var WP_Block $block      Block instance.
 *
 * @package wcma_organizers_block_mixed
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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
		return;
	}

	printf(
		'<div class="components-placeholder"><div class="components-placeholder__fieldset">%s</div></div>',
		esc_html__( 'No organizers found. Make sure there are users with published posts.', 'wcma-organizers-block-mixed' )
	);

	return;
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

	$item_class = 'wp-block-wcma-organizers-list-mixed__item';
	if ( $show_avatar ) {
		$item_class .= ' has-avatar';
	}
	$html .= '<li class="' . esc_attr( $item_class ) . '">';

	if ( $show_avatar ) {
		$html .= '<div class="wp-block-wcma-organizers-list-mixed__avatar">';
		$html .= get_avatar( $organizer->ID, $avatar_size );
		$html .= '</div>';
	}

	$html .= '<div class="wp-block-wcma-organizers-list-mixed__content">';

	if ( $show_name ) {
		$html .= '<h3 class="wp-block-wcma-organizers-list-mixed__name">';
		$html .= esc_html( $name );
		$html .= '</h3>';
	}

	if ( $show_bio ) {
		$bio = get_the_author_meta( 'description', $organizer->ID );
		if ( $bio ) {
			$html .= '<p class="wp-block-wcma-organizers-list-mixed__bio">';
			$html .= esc_html( $bio );
			$html .= '</p>';
		}
	}

	if ( $show_link ) {
		$html .= '<a class="wp-block-wcma-organizers-list-mixed__link" href="' . esc_url( get_author_posts_url( $organizer->ID ) ) . '">';
		if ( '' !== $link_text ) {
			$html .= esc_html( $link_text ) . ' ' . esc_html( $organizer->first_name );
		} else {
			$html .= sprintf(
				/* translators: %s: author display name */
				esc_html__( 'Posts by %s', 'wcma-organizers-block-mixed' ),
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
echo $html;
