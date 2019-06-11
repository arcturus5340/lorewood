<?php
/**
 * Шаблон обычной страницы (page.php)
 * @package WordPress
 * @subpackage your-clean-template-3
 */
get_header(); ?>
<section>
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-md-12">
				<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<h1><?php the_title(); ?></h1>
						<?php the_content(); ?>
					</article>
				<?php endwhile; ?>
			</div>
			<?php //get_sidebar(); ?>
		</div>
	</div>
</section>
<?php get_footer(); ?>