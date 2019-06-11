<?php
/**
 * Страница архивов записей (archive.php)
 * @package WordPress
 * @subpackage your-clean-template-3
 */
get_header(); ?> 
<section>
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-md-8 inf">
				<h1><?php // заголовок архивов
					if (is_day()) : printf('Daily Archives: %s', get_the_date());
					elseif (is_month()) : printf('Monthly Archives: %s', get_the_date('F Y'));
					elseif (is_year()) : printf('Yearly Archives: %s', get_the_date('Y'));
					else : 'Archives';
				endif; ?></h1>
				<?php if  ( have_posts() ) : $count = 0; ?>
<?php while ( have_posts() )  : the_post(); ?>
<?php $count++; ?>
<?php if ($count % 2 == 0 ) : ?>
				<div class="post-block post-block-2">
					<?php get_template_part('loop-second'); ?>
          </div>
        <?php else:?>
        <div class="post-block post-block-1">
					<?php get_template_part('loop-first'); ?>
          </div>
				 <?php endif;?>
				<?php endwhile; ?>
				<?php endif; ?>	 
				<?php pagination(); ?>
			</div>
			<?php get_sidebar(); ?>
		</div>
	</div>
</section>
<?php get_footer(); ?>