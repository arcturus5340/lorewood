<?php
/**
 * Шаблон комментариев (comments.php)
 * Выводит список комментариев и форму добавления
 * @package WordPress
 * @subpackage your-clean-template-3
 */
?>
<div id="comments">
  <div class="comment-number-top"><?php comments_number('Пока нет комментариев', '<span>1</span> комментарий', '<span>%</span> коментариев'); ?></div>
	<?php if (have_comments()) : ?>
	<ul class="comment-list media-list">
    <div class="inf">
		<?php
			$args = array(
				'walker' => new clean_comments_constructor,
			);
			wp_list_comments($args); 
		?>
    </div>
	</ul>
  
   <?php if (get_comment_pages_count() > 0 && get_option( 'page_comments')) : ?>
		<?php $args = array(
				'prev_text' => '<',
				'next_text' => '>',
				'type' => 'array',
				'echo' => false
        
			); 
			$page_links = paginate_comments_links($args);
			if( is_array( $page_links ) ) {
			    echo '<ul class="pagination comments-pagination">';
			    foreach ( $page_links as $link ) {
			    	if ( strpos( $link, 'current' ) !== false ) echo "<li class='active'>$link</li>";
			        else echo "<li>$link</li>"; 
			    }
			   	echo '</ul>';
		 	}
		?>
		<?php endif; ?>
	<?php endif; ?>
		
  
  <?php if ( is_user_logged_in() ):?>
  
	<?php if (comments_open()) {
		$fields =  array(
			'author' => '<div class="form-group"><label for="author">Имя</label><input class="form-control" id="author" name="author" type="text" value="'.esc_attr($commenter['comment_author']).'" size="30" required></div>',
			'email' => '<div class="form-group"><label for="email">Email</label><input class="form-control" id="email" name="email" type="email" value="'.esc_attr($commenter['comment_author_email']).'" size="30" required></div>',
			
			);
		$args = array(
			'fields' => apply_filters('comment_form_default_fields', $fields),
			'comment_field' => '<div class="media-left2">'.get_avatar($comment, 64, '', get_comment_author(), array('class' => 'media-object')).'</div><div class="form-group"><label for="comment"></label><textarea class="form-control" id="comment" name="comment" placeholder="Введите сообщение" cols="45" rows="8" required></textarea></div>',
			'must_log_in' => '<p class="must-log-in">Вы должны быть зарегистрированы! '.wp_login_url(apply_filters('the_permalink',get_permalink())).'</p>',
			'logged_in_as' => '<p class="logged-in-as">'.sprintf(__( ''), admin_url('profile.php'), $user_identity, wp_logout_url(apply_filters('the_permalink',get_permalink()))).'</p>',
			'comment_notes_before' => '<p class="comment-notes">Ваш email не будет опубликован.</p>',
			
			'id_form' => 'commentform',
			'id_submit' => 'submit',
			'title_reply' => '',
			'title_reply_to' => 'Ответить %s',
			'cancel_reply_link' => 'Отменить ответ',
			'label_submit' => '',
			'class_submit' => 'btn btn-default btn-comment'
		);
	    comment_form($args);
	} ?>
   
 
  <?php else:?>
  <div class="autorized">
    <p>Авторизоваться, чтобы оставлять комментарии</p>
  <?php echo do_shortcode('[login-with-ajax profile_link=1 registration=1 remember=1 template="modal"]');?>
  </div>
  <?php endif;?>
  

  
</div>