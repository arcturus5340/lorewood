<?php
/**
 * Главная страница (index.php)
 * @package WordPress
 * @subpackage your-clean-template-3
 */
get_header(); ?> 
<section>
	<div class="container">
   
    	<div class="row" style="padding: 15px;">
		
        <?php $my_query = new WP_Query('showposts=1&cat=3');
while ($my_query->have_posts()) : $my_query->the_post();
$do_not_duplicate[] = $post->ID; ?>
        
<?php //$cat = new WP_query(); $cat->query('showposts=1&cat=3' ); 
     
        ?>
<?php //while ($cat->have_posts()) : $cat->the_post(); 
      //$do_not_duplicate = $post->ID;
       
        ?>
  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 border-top no-gutters post-top-overlay post-block-top" style="margin: 0; background: url('<?php the_post_thumbnail_url( 'full' ); ?>')no-repeat;background-size: 100%;background-position: top center;object-fit: cover;object-position: top;height: 380px;width: 100%;">
    
    <div class="meta-block"><?php echo get_the_date('d F'); ?> </div>
   
<div class="post-wrap">
 <div class="post-wrap-h2"><h2><a href="<?php the_permalink(); ?>"><span class="word"><?php the_title(); ?></span></a></h2></div>
        <div class="excerpt clip">
   <?php if( get_field('subtitle') ): ?> 
          <?php the_field('subtitle'); ?>
          <?php else:?>
          <?php the_excerpt(''); ?>
        <?php endif;?>
  </div>
        <div class="meta-block">
         
        </div>
        <a href="<?php the_permalink(); ?>"><div class="btn btn-more"><strong>Читать</strong> полностью</div></a>
        </div>
     
     </div>
    
     <?php endwhile; ?>

<?php wp_reset_query(); ?>

</div>
    <div class="triangle">
    <div class="row" style="padding: 15px;margin-top: -30px;">
      
<?php //$cat = new WP_query(); $cat->query('showposts=3&cat=3&offset=1');
      
      ?>
<?php //while ($cat->have_posts()) : $cat->the_post(); 
      //$do_not_duplicate[] = $post->ID
      ?>
      
      <?php $my_query = new WP_Query('showposts=3&cat=3');
while ($my_query->have_posts()) : $my_query->the_post();
      if( $post->ID == $do_not_duplicate ) continue;
$do_not_duplicate[] = $post->ID; ?>
      
<div id="post-<?php the_ID(); ?>"  class="col-xs-12 col-sm-4 col-md-4 col-lg-4 post-middle post-middle-overlay no-gutters" >
  <div class="post-middle-img grid1">
    	<figure class="effect-duke">
<?php the_post_thumbnail('full', array('class' => 'img-responsive cropped-middle-post')); ?>
   
<div class="post-middle-wrap">
<div class="post-wrap-h2"><h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2></div>
<div class="meta-block"><?php echo get_the_date('d F'); ?></div>
</div>
          <figcaption>
							<a href="<?php the_permalink(); ?>"></a>
						</figcaption>			
					</figure>
</div>

</div>
     
<?php endwhile; ?>
<?php wp_reset_query(); ?>
</div>
 </div>
    
		<div class="row" style="margin-top: 7px;">
			<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 inf pr-5">
        
 <?php 
  // $page_num = $paged;
 // if ($pagenum='') $pagenum =1;
  //query_posts(array( 'offset' => 4, 'paged' => get_query_var('page') ) );     
 
        ?>
        
       
    <?php if  ( have_posts() ) : $count = 0; ?>
      
<?php while ( have_posts() )  : the_post(); ?>
          
     
<?php $count++; ?>
        <?php if (in_array($post->ID, $do_not_duplicate)) continue;?>
<?php if ($count % 2 == 0 ) : ?>
        
         
        <div class="post-block post-block-1">
					<?php get_template_part('loop-first'); ?>
          </div>
        <?php else:?>
        <div class="post-block post-block-2">
					<?php get_template_part('loop-second'); ?>
          </div>
      
        <?php endif;?>
				<?php endwhile; ?>
				<?php endif; ?>	 
       
      
        <?php wp_reset_query(); ?>
			
       <?php pagination(); ?>
     
			</div>
       
      
      <?php get_sidebar(); ?>
      
		</div>
     <?php //pagination(); ?>
    
	</div>
</section>
<?php get_footer(); ?>