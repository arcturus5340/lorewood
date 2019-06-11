<style>
    .inputbock {width:100%;clear:both;}
    .groupblock {border: 1px solid #aaa; margin: 5px; padding: 5px 10px; border-radius: 10px}
    .red label {color: red; font-weight:800}
    .groupblock h2 {margin-top: 5px;}
    .groupblock input[type="submit"] {max-width:80%; margin: 15px; padding: 4px  7px 7px 7px;}
    .alertblock {border-color: red; color:red; font-weight:800}
    input {max-width: 100%}
</style>

<div class="mainbock">
    <div class="groupblock">
        <h1><?php echo __('Settings', 'wp-translitera')?></h1>
        <form method=POST>
            <h3><?php echo __('Global settings','wp-translitera') ?></h3>
            <?php if (is_multisite()) {
                wp_translitera::get_template_object('use_global_mu_settings','checkbox', __('Use the settings of the main site', 'wp-translitera'));
            }
            wp_translitera::get_template_object('use_force_transliterations','checkbox',__('Use forces transliteration for title', 'wp-translitera'));
            wp_translitera::get_template_object('tranliterate_404','checkbox',__('Transliterate 404 url', 'wp-translitera'));
            wp_translitera::get_template_object('disable_in_front','checkbox',__("Don't use transliteration in frontend (enable when have a problem in front-end)", 'wp-translitera'),'red');
            ?>
            <h3><?php echo __('Media settings','wp-translitera') ?></h3>
            <?php
            wp_translitera::get_template_object('tranliterate_uploads_file','checkbox',__('Transliterate names of uploads files', 'wp-translitera'));
            wp_translitera::get_template_object('lowercase_filename','checkbox',__('Convert files names to lower case', 'wp-translitera'));
            wp_translitera::get_template_object('typefiles','text',__('File extensions, separated by commas , titles that do not need to transliterate', 'wp-translitera'),'','size="80"',$extforform);
            ?>
            <h3><?php echo __('Transliteration tables','wp-translitera') ?></h3>
            <?php wp_translitera::get_template_object('customrules','textarea',__('Custom transliteration rules, in format я=ja (Everyone ruled from a new line!). It is enough to create only the lower case.', 'wp-translitera'),'','cols="30" rows="10"',$customrulesstring);
            wp_translitera::get_template_object('apply','submit',__('Apply', 'wp-translitera')); ?>
        </form> 
    </div>
    <div class="groupblock">
        <h1><?php echo __('Convert existing', 'wp-translitera') ?></h1>
        <form method=POST>
        <?php wp_translitera::get_template_object('r1','checkbox',__('Pages and posts', 'wp-translitera'));
        wp_translitera::get_template_object('r2','checkbox',__('Headings, tags etc...', 'wp-translitera'));
        if (wp_translitera::wpforoactive()) {?>
            <h3>WPForo</h3> 
            <?php wp_translitera::get_template_object('f1','checkbox',__('Forums', 'wp-translitera'));
            wp_translitera::get_template_object('f2','checkbox',__('Topics', 'wp-translitera'));
        }
        wp_translitera::get_template_object('transliterate','submit',__('Transliterate', 'wp-translitera'));
        ?>
        </form>
    </div>
</div>