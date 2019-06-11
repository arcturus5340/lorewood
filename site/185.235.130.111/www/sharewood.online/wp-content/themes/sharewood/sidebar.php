<?php
/**
 * Шаблон сайдбара (sidebar.php)
 * @package WordPress
 * @subpackage your-clean-template-3
 */
?>
<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">

  <div class="row" style="padding: 0px 15px;">
    <?php if (have_posts()) ; ?>
    <?php query_posts(array('showposts' => '1', 'meta_key' => 'ratings_average', 'orderby' => 'meta_value_num', 'order' => 'DESC')); ?>
    <?php while (have_posts()) : the_post(); ?>

      <div id="post-<?php the_ID(); ?>" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 post-sidebar-top">
        <div class="rating"><span><?php echo do_shortcode('[ratings  results="true"]'); ?> <span
                class="eee">из 10</span></span></div>
        <div class="post-wrap-h2"><h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2></div>
        <div class="excerpt clip">
          <?php if (get_field('subtitle')): ?>
            <?php the_field('subtitle'); ?>
          <?php else: ?>
            <?php the_excerpt(''); ?>
          <?php endif; ?>
        </div>
      </div>

    <?php endwhile; ?>

    <?php wp_reset_query(); ?>
  </div>
  <div class="triangle2">
    <div class="row">
      <?php $cat = new WP_query();
      $cat->query('showposts=2&meta_key=ratings_average&orderby=meta_value_num&order=DESC&offset=1'); ?>
      <?php if (have_posts()) : $count = 0;
        while ($cat->have_posts())  : $cat->the_post();
          $count++; ?>
          <?php //if ( have_posts() ) : $count = 0; while ( have_posts() )  : the_post(); $count++; ?>
          <?php if ($count % 2 == 0) : ?>

            <div class="col-xs-12 col-sm-6 col-md-6 post-green-sidebar post-sidebar-1 pl-0">
              <?php get_template_part('loop-sidebar-first'); ?>
            </div>
          <?php else: ?>
            <div class="col-xs-12 col-sm-6 col-md-6 post-green-sidebar post-sidebar-2 pr-0">
              <?php get_template_part('loop-sidebar-second'); ?>
            </div>

          <?php endif; ?>
        <?php endwhile; ?>
      <?php endif; ?>
      <?php wp_reset_query(); ?>
    </div>
    <div class="row">
      <?php $cat = new WP_query();
      $cat->query('showposts=2&meta_key=ratings_average&orderby=meta_value_num&order=DESC&offset=3'); ?>
      <?php if (have_posts()) : $count = 0;
        while ($cat->have_posts())  : $cat->the_post();
          $count++; ?>

          <?php if ($count % 2 == 0) : ?>

            <div class="col-xs-12 col-sm-6 col-md-6 post-green-sidebar post-sidebar-1 pl-0">
              <?php get_template_part('loop-sidebar-second'); ?>
            </div>
          <?php else: ?>
            <div class="col-xs-12 col-sm-6 col-md-6 post-green-sidebar post-sidebar-2 pr-0">
              <?php get_template_part('loop-sidebar-first'); ?>
            </div>

          <?php endif; ?>
        <?php endwhile; ?>
      <?php endif; ?>
      <?php wp_reset_query(); ?>
    </div>
  </div>

  <div class="social-sidebar">
    <div style="text-align:left;" id="__utl-buttons-1">
      <div class="social-groups uptl_container uptl_container-share uptlw-container uptl_container-horizontal uptlk_wdgt_VeqQA">
        <div class="uptl_toolbar uptl_toolbar_simple __utl-reset uptl_toolbar_share">
          <ul class="horizontal style-1 size-40 horizontal">
            <li data-snid="tw" class="utl-icon-num-0 share-style-1 utl-icon-tw effect-0">
              <a href="https://twitter.com/sharewoodbiz" target="_blank" data-snid="tw" class="sn-icon"></a>
            </li>
            <li data-snid="vk" class="utl-icon-num-1 share-style-1 utl-icon-vk effect-0">
              <a href="https://vk.com/sharewood_biz" target="_blank" data-snid="vk" class="sn-icon"></a>
            </li>
            <li data-snid="fb" class="utl-icon-num-2 share-style-1 utl-icon-fb effect-0">
              <a href="https://www.facebook.com/sharewood_biz" target="_blank" data-snid="fb" class="sn-icon"></a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <div class="contact-sidebar">

    <div class="title"><span>Связаться с администрацией</span> по электронной почте:</div>
    <div class="mail">sharewood@gmail.com</div>
  </div>
</div>
  