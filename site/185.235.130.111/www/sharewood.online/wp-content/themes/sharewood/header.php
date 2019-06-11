<?php
/**
 * Шаблон шапки (header.php)
 * @package WordPress
 * @subpackage your-clean-template-3
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="/wp-content/themes/sharewood/fonts/font-awesome/css/font-awesome.min.css">

  <!--[if lt IE 9]>
  <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->


  <?php wp_head(); ?>

  <?php if (is_home()): ?>
    <script>
      jQuery(function () {
        var a = new String;
        a = jQuery('.word').html();
        var b = a.indexOf(' ');
        if (b == -1) {
          b = a.length;
        }
        jQuery('.word').html('<span class="first_word">' + a.substring(-2, b) + '</span>' + a.substring(b, a.length));
      });
    </script>
  <?php else: ?>
  <?php endif; ?>

</head>
<body <?php body_class(); ?>>


<?php if (is_home()): ?>
<header>
  <?php elseif (is_single()): ?>
  <header class="header-page">
    <?php else: ?>
    <header>
      <?php endif; ?>
      <div class="container">
        <div class="row">
          <div class="col-xs-12 col-sm-4 col-md-2 col-lg-3">
            <div class="logo"><a href="/">Share<span>wood</span></a></div>
          </div>
          <div class="col-xs-12 col-sm-4 col-md-7 col-lg-7">
            <div class="menu">
              <?php $args = array(
                'theme_location' => 'top',
                'container' => false,
                'menu_id' => 'top-nav-ul',
                'items_wrap' => '<ul id="%1$s" class="nav navbar-nav %2$s">%3$s</ul>',
                'menu_class' => 'top-menu',
                'walker' => new bootstrap_menu(true)
              );
              wp_nav_menu($args);
              ?>

            </div>
          </div>

          <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
            <div class="sform bform" data-toggle="modal" data-target="#myModal1">
              <!--<div class="sform" data-toggle="collapse" data-target="#block-form">-->
              <?php if (is_home()): ?>
                <img src="/wp-content/themes/sharewood/img/search%20(2).png">
              <?php elseif (is_single()): ?>
                <img src="/wp-content/themes/sharewood/img/search%20(1).png">

              <?php else: ?>
                <img src="/wp-content/themes/sharewood/img/search%20(2).png">
              <?php endif; ?>

            </div>
            <?php if (is_user_logged_in()): ?>
              <a href="/wp-login.php?action=logout" title="выйти">
                <div class="btn btn-primary btn-login"><i class="fa fa-user-o" aria-hidden="true"
                                                          style="padding-right: 10px;"></i> Выйти
                </div>
              </a>
            <?php else: ?>
              <?php echo do_shortcode('[login-with-ajax profile_link=1 registration=1 remember=1 template="modal"]'); ?>
            <?php endif; ?>

          </div>

        </div>
      </div>
    </header>

