<?php
/**
 * Запись в цикле (loop.php)
 * @package WordPress
 * @subpackage your-clean-template-3
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

  <div class="row post-sidebar-cropped post-sidebar-overlay" style="margin: 0; background: #51b956;">


    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <div class="post-wrap-sidebar">
        <div class="rating"><span><?php echo do_shortcode('[ratings results="true"]'); ?></span></div>
        <div class="post-wrap-h2"><h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2></div>
        <div class="excerpt clip">
          <?php if (get_field('subtitle')): ?>
            <?php the_field('subtitle'); ?>
          <?php else: ?>
            <?php the_excerpt(''); ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</article>