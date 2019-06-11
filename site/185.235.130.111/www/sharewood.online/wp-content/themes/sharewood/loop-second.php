<?php
/**
 * Запись в цикле (loop-second.php)
 * Чётная запись в цикле
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

  <div class="row border-r post-2-cropped post-2-overlay"
       style="margin: 0; background: url('<?php the_post_thumbnail_url('large'); ?>')no-repeat;background-size: cover;">
    <div class="col-xs-12 col-sm-5 col-md-6 col-lg-6 border-l">
      <div class="name-cat"><?php //the_category(' - ', 'multiple'); ?></div>
    </div>
    <div class="col-xs-12 col-sm-7 col-md-6 col-lg-6">
      <div class="post-wrap">
        <div class="rating"><span><?php echo do_shortcode('[ratings results="true"]'); ?> <span class="eee">из 10</span></span>
        </div>

        <div class="post-wrap-h2"><h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2></div>
        <div class="excerpt clip">
          <?php if (get_field('subtitle')): ?>
            <?php the_field('subtitle'); ?>
          <?php else: ?>
            <?php the_excerpt(''); ?>
          <?php endif; ?>
        </div>
        <div class="meta-block">
          <div class="meta-item"><?php echo do_shortcode('[ratings results="true"]'); ?></div>
          <div class="meta-item"><i class="fa fa-commenting"
                                    aria-hidden="true"></i> <?php comments_number('0', '1', '%'); ?> </div>
          <div class="meta-item"><i class="fa fa-clock-o" aria-hidden="true"></i><?php echo get_the_date('d F Y'); ?>
          </div>
          <a href="<?php the_permalink(); ?>">
            <div class="btn btn-more"><strong>Читать</strong> полностью</div>
          </a>
        </div>
      </div>
    </div>


</article>