<?php
/**
 * A plain page — e.g. the site's privacy notice or an imprint.
 *
 * @package AstroLabe
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<article <?php post_class( 'page-article' ); ?>>
		<header class="page-hero">
			<div class="wrap wrap-narrow">
				<h1><?php the_title(); ?></h1>
				<?php if ( has_excerpt() ) : ?>
					<p class="page-lede"><?php echo esc_html( get_the_excerpt() ); ?></p>
				<?php endif; ?>
			</div>
		</header>
		<div class="wrap wrap-narrow">
			<div class="prose">
				<?php the_content(); ?>
			</div>
		</div>
	</article>
	<?php
endwhile;

get_footer();
