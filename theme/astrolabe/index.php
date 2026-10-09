<?php
/**
 * Fallback for anything without its own template (the site has no blog yet).
 *
 * @package AstroLabe
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<div class="page-hero">
	<div class="wrap wrap-narrow">
		<h1><?php echo esc_html( is_home() ? get_bloginfo( 'name' ) : wp_strip_all_tags( get_the_archive_title() ) ); ?></h1>
	</div>
</div>
<div class="wrap wrap-narrow">
	<div class="prose">
		<?php if ( have_posts() ) : ?>
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article <?php post_class(); ?>>
					<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<?php the_excerpt(); ?>
				</article>
			<?php endwhile; ?>
			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<p><?php esc_html_e( 'Nothing here yet.', 'astrolabe' ); ?></p>
		<?php endif; ?>
	</div>
</div>
<?php
get_footer();
