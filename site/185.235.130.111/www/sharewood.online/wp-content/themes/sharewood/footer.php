<?php
/**
 * Шаблон подвала (footer.php)
 * @package WordPress
 * @subpackage your-clean-template-3
 */
?>
	<footer>
			<div class="container">
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
         <div class="logo"><a href="/">Share<span>wood</span></a></div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
          <div class="menu">
          <?php $args = array( 
								'theme_location' => 'top',
								'container'=> false,
						  		'menu_id' => 'top-nav-ul',
						  		'items_wrap' => '<ul id="%1$s" class="nav navbar-nav %2$s">%3$s</ul>',
								'menu_class' => 'top-menu',
						  		'walker' => new bootstrap_menu(true)		  		
					  			);
								wp_nav_menu($args);
							?>
          
          </div>
        </div>
      
<div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
  <div class="social-footer">
  <i class="fa fa-youtube-play" aria-hidden="true"></i>
  <i class="fa fa-vk" aria-hidden="true"></i>
  <i class="fa fa-twitter" aria-hidden="true"></i>
  </div>
</div>
        
			</div>
        
        
        	<div class="row copyright">
            
            <div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">
               <div class="copyright-left">
            &copy; 2019 SHAREWOOD Все права защищены
              </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
             <div class="copyright-right">
            sharewood@gmail.com
              </div>
            </div>
            </div>
      
        
		</div>
	</footer>

<!-- Modal поиск-->
<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
       <button type="button" class="close bform" data-dismiss="modal" aria-hidden="true">&times;</button>
      <div class="modal-body" style="background: #fff;padding: 40px;">
      
       <?php get_search_form(); ?>
      </div>
     
    </div>
  </div>
</div>
<script>
   jQuery( document ).ready(function() {
 jQuery(function(){
            jQuery('.bform').click(function(){
                jQuery('.container').toggleClass('blur');
            });
        });
   
   });
  
 
</script>
<?php wp_footer(); ?>
</body>
</html>
