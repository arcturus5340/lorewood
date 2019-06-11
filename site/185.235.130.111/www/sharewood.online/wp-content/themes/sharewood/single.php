<?php
/**
 * Шаблон отдельной записи (single.php)
 * @package WordPress
 * @subpackage your-clean-template-3
 */
get_header(); ?>
<div class="post-featured post-featured-cropped"
     style="background: url('<?php the_post_thumbnail_url('large'); ?>')no-repeat;background-size: cover;">
  <div class="post-featured-wrap">
    <div class="container">
      <div class="row">
        <div class="col-xs-12 col-sm-8 col-md-8">
          <h1><?php the_title(); ?></h1>
          <div class="post-featured-subtitle">
            <?php the_field('subtitle'); ?>
          </div>
        </div>
        <div class="col-xs-12 col-md-4 col-sm-4">
          <div class="rating"><span><?php echo do_shortcode('[ratings results="true"]'); ?> <span class="eee">из 10</span></span></div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="meta-block">
            <div class="meta-item"><i class="fa fa-commenting"
                                      aria-hidden="true"></i> <?php comments_number('0', '1', '%'); ?> </div>
            <div class="meta-item"><i class="fa fa-clock-o" aria-hidden="true"></i><?php echo get_the_date('d F Y'); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</div>
<section>
  <div class="container">
    <div class="row">
      <div class="col-xs-12 col-md-12">
        <?php if (have_posts()) while (have_posts()) : the_post(); ?>
          <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <div class="post-container">
              <div class="row">
                <div class="col-xs-12 col-sm-3 col-md-2 col-lg-2">
                  <div class="media-left-post"><?php echo get_avatar(array('class' => 'media-object')); ?></div>
                  <div class="media-heading-post"><?php echo get_the_author(); ?></div>
                  <div class="autor-post-meta"><?php the_author_meta('description'); ?></div>
                </div>
                <div class="col-xs-12 col-sm-8 col-md-10 col-lg-10">
                  <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                      <?php if (get_field('text2')): ?>
                        <div class="epilog-text"><?php the_field('text2'); ?></div>
                      <?php endif; ?>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3"></div>
                  </div>
                  <div class="row mt-25">
                    <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                      <?php the_field('text1'); ?>
                      <?php the_content(); ?>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                      <?php if (get_field('textgreen1')): ?>
                        <div class="green-block"><?php the_field('textgreen1'); ?></div>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row mt-25">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                  <?php if (get_field('imgfull')): ?>
                    <img src="<?php the_field('imgfull'); ?>" class="img-responsive cropped-post">
                  <?php endif; ?>
                </div>
              </div>
              <div class="row mt-25">
                <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2"></div>
                <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                  <?php the_field('text3'); ?>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2"></div>
              </div>
              <div class="row mt-25">
                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                  <?php if (get_field('imgrow')): ?>
                    <img src="<?php the_field('imgrow'); ?>" class="img-responsive cropped-post-2">
                  <?php endif; ?>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-5 col-lg-5">
                  <?php the_field('text5'); ?>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                  <?php if (get_field('textgreen2')): ?>
                    <div class="green-block"><?php the_field('textgreen2'); ?></div>
                  <?php endif; ?>
                </div>
              </div>
              <div class="row mt-25">
                <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2"></div>
                <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                  <?php the_field('text6'); ?>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2"></div>
              </div>
              <div class="row mt-25">
                <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2"></div>
                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                  <div class="tags-content"> <?php the_tags('<div><span>', '</span><span>', '</span></div>'); ?></div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3"></div>
              </div>
            </div>
          </article>
        <?php endwhile; ?>
        <div class="social-container">
          <div class="row">
            <div class="col col-md-6 col-xs-12">
              <div class="post-ratings-wrapper">
                <?php echo do_shortcode('[ratings]'); ?>
              </div>
            </div>
            <div class="col col-md-6 col-xs-12">
              <div class="social-wrapper">
                <?php echo do_shortcode("[uptolike]"); ?>
              </div>
            </div>
          </div>
        </div>
        <div class="post-next-container">
          <div id="" class="row navigation">
            <?php

            $prevPost = get_previous_post(true);
            $nextPost = get_next_post(true);

            if(!$prevPost || !$nextPost){

              $current_post_id = get_the_ID();

              $most_viewed_posts = top_views(array(
                'range' => 'week',
                'limit' => 3
              ));

              $most_viewed_data = array();

              if (false === ($most_viewed_data = get_transient('most_viewed_data_' . $current_post_id))) {

                $most_viewed_data = array(
                  'prev_post_id'    => $prevPost->ID ? $prevPost->ID : 0,
                  'next_post_id'    => $nextPost->ID ? $nextPost->ID : 0,
                  'current_post_id' => $current_post_id
                );

                set_transient('most_viewed_data_' . $current_post_id, $most_viewed_data, MINUTE_IN_SECONDS );
              }

              foreach ($most_viewed_posts as $most_viewed_post_index => $most_viewed_post) {

                if($most_viewed_post->ID == $most_viewed_data['prev_post_id']) {
                  unset($most_viewed_posts[$most_viewed_post_index]);
                }

                if($most_viewed_post->ID == $most_viewed_data['next_post_id']) {
                  unset($most_viewed_posts[$most_viewed_post_index]);
                }

                if($most_viewed_post->ID == $most_viewed_data['current_post_id']) {
                  unset($most_viewed_posts[$most_viewed_post_index]);
                }
              }

              $most_viewed_post = false;

              if(!empty($most_viewed_posts)) {
                $most_viewed_post = array_shift($most_viewed_posts);
              }

              if(!$most_viewed_post) {

                $random_post_query = new WP_Query( array(
                  'post_type'      => 'post',
                  'orderby'        => 'rand',
                  'posts_per_page' => 1,
                  'post__not_in'   => array($most_viewed_data['prev_post_id'], $most_viewed_data['next_post_id'], $most_viewed_data['current_post_id'])
                ));

                $random_posts = $random_post_query->get_posts();

                if(!empty($random_posts)) {

                  $most_viewed_post = array_shift($random_posts);
                }
              }
            }

            ?>
            <div class="col-xs-12 col-sm-6 col-md-6 nav-box previous">
              <div class="nav-wrap">
                <?php

                if ($prevPost) {

                  $prevthumbnail = get_the_post_thumbnail($prevPost->ID, 'full');

                  previous_post_link('%link', "$prevthumbnail  <div>%title</div>", TRUE);

                  print '<span class="meta-nav" aria-hidden="true">Предыдущий пост</span>';

                } else {

                  if($most_viewed_post) {

                    $most_viewed_post_thumbnail = get_the_post_thumbnail($most_viewed_post->ID, 'full');

                    print '
                      <a href="' . get_permalink($most_viewed_post) . '" rel="next">
                        ' . $most_viewed_post_thumbnail . '
                        <div>' . $most_viewed_post->post_title . '</div>
                      </a>
                      <span class="meta-nav" aria-hidden="true">Популярный пост</span>
                    ';
                  }
                }

                ?>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 nav-box next" style="float:right;">
              <div class="nav-wrap">
                <?php

                if ($nextPost) {

                  $nextthumbnail = get_the_post_thumbnail($nextPost->ID, 'full');

                  next_post_link('%link', "$nextthumbnail  <div>%title</div>", TRUE);

                  print '<span class="meta-nav" aria-hidden="true">Следующий пост</span>';

                } else {

                  if($most_viewed_post) {

                    $most_viewed_post_thumbnail = get_the_post_thumbnail($most_viewed_post->ID, 'full');

                    print '
                      <a href="' . get_permalink($most_viewed_post) . '" rel="next">
                        ' . $most_viewed_post_thumbnail . '
                        <div>' . $most_viewed_post->post_title . '</div>
                      </a>
                      <span class="meta-nav" aria-hidden="true">Популярный пост</span>
                    ';
                  }
                }

                ?>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">
            <div class="comment-container">
              <?php if (comments_open() || get_comments_number()) comments_template('', true); ?>
            </div>
          </div>
          <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
            <!-- Похожие записи-->
            <div class="related-block">
              <h3>Может быть <span>интересно</span></h3>
              <?php $tags = wp_get_post_tags($post->ID);
              if ($tags) {
                $tag_ids = array();
                foreach ($tags as $individual_tag) $tag_ids[] = $individual_tag->term_id;
                $args = array(
                  'tag__in' => $tag_ids, // Сортировка производится по тегам
                  'post__not_in' => array($post->ID),
                  'showposts' => 2,
                  'caller_get_posts' => 1,
                  'orderby' => rand
                );
                $my_query = new wp_query($args);
                if ($my_query->have_posts()) {
                  echo '<ul>';
                  while ($my_query->have_posts()) {
                    $my_query->the_post();
                    ?>
                    <li>
                      <div class="cell">
                        <a href="<?php the_permalink() ?>"></a>
                        <?php the_post_thumbnail('full', array('class' => 'img-responsive related-cropped')); ?>
                        <div class="related-wrap">
                          <div class="tags"> <?php the_tags(' ', '  '); ?></div>
                          <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>">
                            <h2><?php the_title(); ?></h2>
                          </a>
                          <div class="excerpt">
                            <?php if (get_field('subtitle')): ?>
                              <?php the_field('subtitle'); ?>
                            <?php else: ?>
                              <?php the_excerpt(''); ?>
                            <?php endif; ?>
                          </div>
                          <div class="meta-item">
                            <i class="fa fa-clock-o" aria-hidden="true"></i>
                            <?php echo get_the_date('d F Y'); ?>
                          </div>
                        </div>
                      </div>
                    </li>
                    <?php
                  }
                  echo '</ul>';
                }
                wp_reset_query();
              }
              ?></div>
          </div>
        </div>
      </div>
    </div>
    <?php //get_sidebar(); ?>
  </div>
  </div>
</section>
<?php get_footer(); ?>
