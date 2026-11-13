<?php
/**
 * ACF (free) — Local JSON.
 *
 * The field groups and the content post types (Features, Privacy points,
 * Plans, Questions) are kept in acf-json/ inside the theme. They show in
 * ACF → Field Groups / Post Types; whatever is changed there is written back
 * to acf-json/, so the structure travels with the theme. On another install,
 * ACF offers "Sync available" for anything newer in the files.
 *
 * @package AstroLabe
 */

defined( 'ABSPATH' ) || exit;

add_filter(
	'acf/settings/save_json',
	static fn(): string => get_template_directory() . '/acf-json'
);

add_filter(
	'acf/settings/load_json',
	static fn(): array => array( get_template_directory() . '/acf-json' )
);

/**
 * Front-page fields show their default text as the placeholder, taken from
 * inc/defaults.php when the form is drawn, so the two never drift apart.
 */
add_filter(
	'acf/prepare_field',
	static function ( $field ) {
		static $defaults = null;
		if ( ! is_array( $field ) || ! str_starts_with( (string) ( $field['key'] ?? '' ), 'field_astro_' ) ) {
			return $field;
		}
		if ( null === $defaults ) {
			$defaults = array();
			foreach ( astrolabe_defaults()['sections'] as $section => $values ) {
				foreach ( $values as $name => $value ) {
					$defaults[ "field_astro_{$section}_{$name}" ] = $value;
				}
			}
		}
		if ( isset( $defaults[ $field['key'] ] ) ) {
			$field['placeholder'] = $defaults[ $field['key'] ];
		}
		return $field;
	}
);
