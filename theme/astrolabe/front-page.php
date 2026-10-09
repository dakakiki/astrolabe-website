<?php
/**
 * Front page: the sections of the presentation site, in order.
 *
 * @package AstroLabe
 */

defined( 'ABSPATH' ) || exit;

get_header();

foreach ( array( 'hero', 'problem', 'features', 'charts', 'story', 'privacy', 'pricing', 'beta', 'faq', 'closing' ) as $astrolabe_part ) {
	get_template_part( 'template-parts/front/' . $astrolabe_part );
}

get_footer();
