<div
    class="lwa lwa-template-modal"><?php //class must be here, and if this is a template, class name should be that of template directory ?>
  <a href="<?php echo esc_attr(LoginWithAjax::$url_login); ?>"
     class="lwa-links-modal"><?php esc_html_e('Войти', 'login-with-ajax') ?></a>
  <?php
  //FOOTER - once the page loads, this will be moved automatically to the bottom of the document.
  ?>
  <div class="lwa-modal" style="display:none;">
    <form name="lwa-form" class="lwa-form" action="<?php echo esc_attr(LoginWithAjax::$url_login); ?>" method="post">
      <span class="lwa-status"></span>
      <div>
        <div class="lwa-username">
          <div class="username_label">
            <label>введите логин</label>
          </div>
          <div class="username_input">
            <input type="text" name="log" id="lwa_user_login" class="input"/>
          </div>
        </div>
        <div class="lwa-password">
          <div class="password_label">
            <label>введтие пароль</label>
          </div>
          <div class="password_input">
            <input type="password" name="pwd" id="lwa_user_pass" class="input" value=""/>
          </div>
        </div>
        <div><?php do_action('login_form'); ?></div>
        <div class="lwa-submit">
          <div class="lwa-submit-button">
            <button type="submit" name="wp-submit" class="lwa-wp-submit" value="выполнить вход" tabindex="100"><strong>выполнить</strong> вход</button>
            <input type="hidden" name="lwa_profile_link"
                   value="<?php echo !empty($lwa_data['profile_link']) ? 1 : 0 ?>"/>
            <input type="hidden" name="login-with-ajax" value="login"/>
            <?php if (!empty($lwa_data['redirect'])): ?>
              <input type="hidden" name="redirect_to" value="<?php echo esc_url($lwa_data['redirect']); ?>"/>
            <?php endif; ?>
          </div>

          <?php echo get_ulogin_panel(); ?>


          <div class="lwa-links">

            <?php if (!empty($lwa_data['remember'])): ?>
              <div class="links-f lf-l">
                <a class="lwa-links-remember" href="<?php echo esc_attr(LoginWithAjax::$url_remember); ?>"
                   title="<?php esc_attr_e('Забыли пароль?', 'login-with-ajax') ?>"><?php esc_attr_e('Lost your password?', 'login-with-ajax') ?></a>
              </div>

            <?php endif; ?>
            <?php if (get_option('users_can_register') && !empty($lwa_data['registration'])) : ?>

              <div class="links-f lf-r">
                Нет аккаунта? <a href="<?php echo esc_attr(LoginWithAjax::$url_register); ?>" class="lwa-links-register-inline"><strong><?php esc_html_e('Зарегистрировать', 'login-with-ajax'); ?></strong></a>
              </div>

            <?php endif; ?>
          </div>
        </div>
      </div>
    </form>
    <br>
    <?php if (!empty($lwa_data['remember']) && $lwa_data['remember'] == 1): ?>
      <form name="lwa-remember" class="lwa-remember" style="margin-top: 20px;"
            action="<?php echo esc_attr(LoginWithAjax::$url_remember); ?>" method="post" style="display:none;">
        <span class="lwa-status"></span>
        <div>
          <div>
            <div>

              <strong><p><?php esc_html_e("Восстановление пароля", 'login-with-ajax'); ?></p></strong>
              <br>
            </div>
          </div>
          <div class="lwa-remember-email">
            <div>
                <?php $msg = __("Enter username or email", 'login-with-ajax'); ?>
                <input type="text" name="user_login" id="lwa_user_remember" value="<?php echo esc_attr($msg); ?>"
                       onfocus="if(this.value == '<?php echo esc_attr($msg); ?>'){this.value = '';}"
                       onblur="if(this.value == ''){this.value = '<?php echo esc_attr($msg); ?>'}"/>
              <?php do_action('lostpassword_form'); ?>
            </div>
          </div>
          <div>
            <div>
              <input type="submit" value="<?php esc_attr_e("Get New Password", 'login-with-ajax'); ?>"/>
              <a href="#" class="lwa-links-remember-cancel"><?php esc_html_e("Cancel", 'login-with-ajax'); ?></a>
              <input type="hidden" name="login-with-ajax" value="remember"/>
            </div>
          </div>
        </div>
      </form>

    <?php endif; ?>
    <?php if (get_option('users_can_register') && !empty($lwa_data['registration']) && $lwa_data['registration'] == 1) : //Taken from wp-login.php ?>
      <div class="lwa-register" style="display:none;">
        <form name="lwa-register" action="<?php echo esc_attr(LoginWithAjax::$url_register); ?>" method="post">
          <span class="lwa-status"></span>
          <div>
            <div>
              <div>
                <strong><p><?php esc_html_e('Register For This Site', 'login-with-ajax') ?></p></strong>
              </div>
            </div>
            <div class="lwa-username">
              <div>
                <label>
                  <?php $msg = __('Username', 'login-with-ajax') ?>
                  <input type="text" name="user_login" id="user_login" value="<?php echo esc_attr($msg); ?>"
                         onfocus="if(this.value == '<?php echo esc_attr($msg); ?>'){this.value = '';}"
                         onblur="if(this.value == ''){this.value = '<?php echo esc_attr($msg); ?>'}"/>
                </label>
              </div>
            </div>
            <div class="lwa-email">
              <div>
                <label>
                  <?php $msg = __('E-mail', 'login-with-ajax') ?>
                  <input type="text" name="user_email" id="user_email" value="<?php echo esc_attr($msg); ?>"
                         onfocus="if(this.value == '<?php echo esc_attr($msg); ?>'){this.value = '';}"
                         onblur="if(this.value == ''){this.value = '<?php echo esc_attr($msg); ?>'}"/>
                </label>
              </div>
            </div>
            <div>
              <div>
                <?php
                //If you want other plugins to play nice, you need this:
                do_action('register_form');
                ?>
              </div>
            </div>
            <div>
              <div>
                <?php esc_html_e('A password will be e-mailed to you.', 'login-with-ajax'); ?><br/>
                <input type="submit" value="<?php esc_attr_e('Register', 'login-with-ajax'); ?>" tabindex="100"/>
                <a href="#"
                   class="lwa-links-register-inline-cancel"><?php esc_html_e("Cancel", 'login-with-ajax'); ?></a>
                <input type="hidden" name="login-with-ajax" value="register"/>
              </div>
            </div>
          </div>
        </form>
      </div>
    <?php endif; ?>
  </div>
</div>