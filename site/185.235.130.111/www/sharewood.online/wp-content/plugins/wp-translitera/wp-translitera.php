<?php
/*
Plugin Name: WP Translitera
Plugin URI: http://yur4enko.com/category/moi-proekty/wp-translitera
Description: Plug-in for transliteration permanent permalink records , pages, and tag
Version: p1.2.5
Author: Evgen Yurchenko
Text Domain: wp-translitera
Domain Path: /languages/
Author URI: http://yur4enko.com/
*/

/*  Copyright 2015 Evgen Yurchenko  (email: evgen@yur4enko.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc.
*/

class wp_translitera {//wp=>3.2 php=>5.2.4
    
    //Создаем локализации
    // добавлено 160306
    // возвращает МАССИВ правил транслитерации
    protected static function createlocale() {//wp=>3.2 php=>5.2.4
        $loc = get_locale();
        $ret = array();
        
        if ($loc == 'ru_RU') {//Русская локализация 
            $ret = array(
                'А' => 'A', 'а' => 'a', 'Б' => 'B', 'б' => 'b', 'В' => 'V', 'в' => 'v', 'Г' => 'G',
                'г' => 'g', 'Д' => 'D', 'д' => 'd', 'Е' => 'E', 'е' => 'e', 'Ё' => 'Jo', 'ё' => 'jo',
                'Ж' => 'Zh', 'ж' => 'zh', 'З' => 'Z', 'з' => 'z', 'И' => 'I', 'и' => 'i', 'Й' => 'J',
                'й' => 'j', 'К' => 'K', 'к' => 'k', 'Л' => 'L', 'л' => 'l', 'М' => 'M', 'м' => 'm',
                'Н' => 'N', 'н' => 'n', 'О' => 'O', 'о' => 'o', 'П' => 'P', 'п' => 'p', 'Р' => 'R',
                'р' => 'r', 'С' => 'S', 'с' => 's', 'Т' => 'T', 'т' => 't', 'У' => 'U', 'у' => 'u',
                'Ф' => 'F', 'ф' => 'f', 'Х' => 'H', 'х' => 'h', 'Ц' => 'C', 'ц' => 'c', 'Ч' => 'Ch',
                'ч' => 'ch', 'Ш' => 'Sh', 'ш' => 'sh', 'Щ' => 'Shh', 'щ' => 'shh', 'Ъ' => '',
                'ъ' => '', 'Ы' => 'Y', 'ы' => 'y', 'Ь' => '', 'ь' => '', 'Э' => 'Je', 'э' => 'je',
                'Ю' => 'Ju', 'ю' => 'ju', 'Я' => 'Ja', 'я' => 'ja'
            );
        } elseif ($loc == 'uk') {//Украинская локализация Добавлено 160415
            $ret = array(
                'А' => 'A', 'а' => 'a', 'Б' => 'B', 'б' => 'b', 'В' => 'V', 'в' => 'v', 'Г' => 'H',
                'г' => 'h', 'Ґ' => 'G', 'ґ' => 'g', 'Д' => 'D', 'д' => 'd', 'Е' => 'E', 'е' => 'e',
                'Є' => 'Ie', 'є' => 'ie', 'Ж' => 'Zh', 'ж' => 'zh', 'З' => 'Z', 'з' => 'z', 'И' => 'Y',
                'и' => 'y', 'І' => 'I', 'і' => 'i', 'Ї' => 'I', 'ї' => 'i', 'Й' => 'I', 'й' => 'i',
                'К' => 'K', 'к' => 'k', 'Л' => 'L', 'л' => 'l', 'М' => 'M', 'м' => 'm', 'Н' => 'N',
                'н' => 'n', 'О' => 'O', 'о' => 'o', 'П' => 'P', 'п' => 'p', 'Р' => 'R', 'р' => 'r',
                'С' => 'S', 'с' => 's', 'Т' => 'T', 'т' => 't', 'У' => 'U', 'у' => 'u', 'Ф' => 'F',
                'ф' => 'f', 'Х' => 'Kh', 'х' => 'kh', 'Ц' => 'Ts', 'ц' => 'ts', 'Ч' => 'Ch', 'ч' => 'ch',
                'Ш' => 'Sh', 'ш' => 'sh', 'Щ' => 'Shch', 'щ' => 'shch', 'Ь' => '', 'ь' => '', 'Ю' => 'Iu',
                'ю' => 'iu', 'Я' => 'Ia', 'я' => 'ia'
            );
        } elseif ($loc == 'bg' || $loc == 'bg_BG') {//bulgarian locale добавлено 170114
            $ret = array(
                'А' => 'A', 'а' => 'a', 'Б' => 'B', 'б' => 'b', 'В' => 'V', 'в' => 'v', 'Г' => 'G',
                'г' => 'g', 'Д' => 'D', 'д' => 'd', 'Е' => 'E', 'е' => 'e', 'Ё' => 'Jo', 'ё' => 'jo',
                'Ж' => 'Zh', 'ж' => 'zh', 'З' => 'Z', 'з' => 'z', 'И' => 'I', 'и' => 'i', 'Й' => 'J',
                'й' => 'j', 'К' => 'K', 'к' => 'k', 'Л' => 'L', 'л' => 'l', 'М' => 'M', 'м' => 'm',
                'Н' => 'N', 'н' => 'n', 'О' => 'O', 'о' => 'o', 'П' => 'P', 'п' => 'p', 'Р' => 'R',
                'р' => 'r', 'С' => 'S', 'с' => 's', 'Т' => 'T', 'т' => 't', 'У' => 'U', 'у' => 'u',
                'Ф' => 'F', 'ф' => 'f', 'Х' => 'H', 'х' => 'h', 'Ц' => 'C', 'ц' => 'c', 'Ч' => 'Ch',
                'ч' => 'ch', 'Ш' => 'Sh', 'ш' => 'sh', 'Щ' => 'Sht', 'щ' => 'sht', 'Ъ' => 'a',
                'ъ' => 'a', 'Ы' => 'Y', 'ы' => 'y', 'Ь' => '', 'ь' => '', 'Э' => 'Je', 'э' => 'je',
                'Ю' => 'Ju', 'ю' => 'ju', 'Я' => 'Ja', 'я' => 'ja'
            );
        } elseif ($loc == 'ge' || $loc == 'ka_GE') {//Georgian locale добавлено 1.2.5
            $ret = array( // Author: Vladimir Pkhakadze
                'ა' => 'a', 'ბ' => 'b', 'გ' => 'g', 'დ' => 'd', 'ე' => 'e', 'ვ' => 'v',
                'ზ' => 'z', 'თ' => 'th', 'ი' => 'i', 'კ' => 'k', 'ლ' => 'l', 'მ' => 'm',
                'ნ' => 'n', 'ო' => 'o', 'პ' => 'p','ჟ' => 'zh','რ' => 'r','ს' => 's',
                'ტ' => 't','უ' => 'u','ფ' => 'ph','ქ' => 'q','ღ' => 'gh','ყ' => 'qh',
                'შ' => 'sh','ჩ' => 'ch','ც' => 'ts','ძ' => 'dz','წ' => 'ts','ჭ' => 'tch',
                'ხ' => 'kh','ჯ' => 'j','ჰ' => 'h'
            ); 
        };
        //Глобальная локализация 
        // добалено 160306, Обновлено 160415/
        $ret = $ret + array(
            'А' => 'A', 'а' => 'a', 'Б' => 'B', 'б' => 'b', 'В' => 'V', 'в' => 'v', 'Г' => 'G',
            'г' => 'g', 'Д' => 'D', 'д' => 'd', 'Е' => 'E', 'е' => 'e', 'Ё' => 'Jo', 'ё' => 'jo',
            'Ж' => 'Zh', 'ж' => 'zh', 'З' => 'Z', 'з' => 'z', 'И' => 'I', 'и' => 'i', 'Й' => 'J',
            'й' => 'j', 'К' => 'K', 'к' => 'k', 'Л' => 'L', 'л' => 'l', 'М' => 'M', 'м' => 'm',
            'Н' => 'N', 'н' => 'n', 'О' => 'O', 'о' => 'o', 'П' => 'P', 'п' => 'p', 'Р' => 'R',
            'р' => 'r', 'С' => 'S', 'с' => 's', 'Т' => 'T', 'т' => 't', 'У' => 'U', 'у' => 'u',
            'Ф' => 'F', 'ф' => 'f', 'Х' => 'H', 'х' => 'h', 'Ц' => 'C', 'ц' => 'c', 'Ч' => 'Ch',
            'ч' => 'ch', 'Ш' => 'Sh', 'ш' => 'sh', 'Щ' => 'Shh', 'щ' => 'shh', 'Ъ' => '',
            'ъ' => '', 'Ы' => 'Y', 'ы' => 'y', 'Ь' => '', 'ь' => '', 'Э' => 'Je', 'э' => 'je',
            'Ю' => 'Ju', 'ю' => 'ju', 'Я' => 'Ja', 'я' => 'ja', 'Ґ' => 'G', 'ґ' => 'g', 'Є' => 'Ie',
            'є' => 'ie', 'І' => 'I', 'і' => 'i', 'Ї' => 'I', 'ї' => 'i', 'ა' => 'a', 'ბ' => 'b', 
            'გ' => 'g', 'დ' => 'd', 'ე' => 'e', 'ვ' => 'v', 'ზ' => 'z', 'თ' => 'th', 'ი' => 'i', 
            'კ' => 'k', 'ლ' => 'l', 'მ' => 'm', 'ნ' => 'n', 'ო' => 'o', 'პ' => 'p','ჟ' => 'zh',
            'რ' => 'r','ს' => 's', 'ტ' => 't','უ' => 'u','ფ' => 'ph','ქ' => 'q','ღ' => 'gh',
            'ყ' => 'qh', 'შ' => 'sh','ჩ' => 'ch','ც' => 'ts','ძ' => 'dz','წ' => 'ts','ჭ' => 'tch',
            'ხ' => 'kh','ჯ' => 'j','ჰ' => 'h'
        );

        //Кстомные правила транслитерации
        // добавлено 170510
        $ret = wp_translitera::get_custom_rules_for_transliterate() + $ret;
        return $ret;
    }
    
    //Преобразуем кастомные правила в оба регистра
    // добавлено в 170510 
    // Возвращает - МАССИВ пользовтаельских правил с заглавными буквами
    protected static function get_custom_rules_for_transliterate() {//wp=>3.2 php=>5.2.4
        $rules = json_decode(wp_translitera::getset('custom_rules', json_encode(array())),TRUE);
        $tr_rules = array();
        foreach ($rules as $key => $value) {
            $tr_rules[$key] = $value;
            if (strlen($value) > 0) {
                $tr_rules[strtoupper($key)] = strtoupper($value{0}).substr($value, 1);
            } else {
                $tr_rules[strtoupper($key)] = $value;
            }
        }
        return $tr_rules;
    }

    //Проставляем галочки в чебоксах
    // добавлено 160128
    // принимает СТРОКА имя настройки
    // возвращает СТРОКА "флаг" для чебокса
    protected static function getchebox($name){//wp=>3.2 php=>5.2.4
        $value = wp_translitera::getset($name);
        return (empty($value))?'':' checked';
    }
    
    //Получаем элементы для темплейта
    //  добавлено p1.2
    //  принимает:  - name СТРОКА с именем переменной
    //              - type СТРОКА тип элемента формы
    //              - label СТРОКА заголовок элемента формы
    //              - class СТРОКА с названием стиля оформления элемента
    //              - params СТРОКА с параметрами оформления элемента
    //              - value СТРОКА значение переменной
    //  Возвращает СТРОКА элемент формы
    protected static function get_template_object($name,$type,$label='',$class='',$params='',$value=''){//wp=>3.2 php=>5.2.4
        $ret = "<div class='inputblock $class'>";
        if ($type == 'checkbox') {
            $ret .= "<input type='checkbox' name='$name' value='1' ".wp_translitera::getchebox($name)."><label>$label</label>";
        }
        if ($type == 'text') {
            $ret .= "<label>$label</label></br><input type='text' $params name='$name' value='$value'>";
        }
        if ($type == 'textarea') {
            $ret .= "<label>$label</label></br><textarea name='$name' $params>$value</textarea>";
        }
        if ($type == 'submit') {
            $ret .= "<input type='submit' value='$label' name='$name'>";
        }
        echo $ret.'</div>';
    }

    //Форма админки
    // добавлено 160119
    // возвращает HTML форму настроек плагина 
    protected static function GetForm() {//wp=>3.2 php=>5.2.4
        $noparsevar = wp_translitera::getset('fileext',array());
        $extforform = '';
        foreach ($noparsevar as $value) {
            $extforform = $extforform.$value.',';
        }
        if (!empty($extforform)){
            $extforform = substr($extforform, 0, -1);
        }
        $customrulesinjson = wp_translitera::getset('custom_rules', json_encode(array()));
        $customrulesarray = json_decode($customrulesinjson, TRUE);
        $customrulesstring = '';        
        foreach ($customrulesarray as $key => $value) {
            $customrulesstring .=$key.'='.$value.PHP_EOL;
        }
        include_once __DIR__.'/admintmpl.php';                
    }
    
    //Транслитерация в БД
    // добавлено 160119
    // принимает:   - table СТРОКА с именем таблицы БД с которой проводим операции
    //              - id СТРОКА с именем индекса таблицы
    //              - name СТРОКА с именем столбца значения которого необходимо транслитерировать
    protected static function do_transliterate($table,$id,$name) {//wp=>3.2 php=>5.2.4
        global $wpdb;
        $rez = $wpdb->get_results("SELECT {$id}, {$name} FROM {$table} WHERE 1",ARRAY_A);
        foreach ($rez as $value) {
            $tmp_name = wp_translitera::transliterate(urldecode($value[$name]));
            if ($tmp_name != $value[$name]) {
                $wpdb->update($table,array($name=>$tmp_name),array($id=>$value[$id]));
            }
        }
    }
    
    //Получаем настройки
    // добавлено 160128
    // возвращает МАССИВ настроек плагина
    protected static function getoptions() {//wp=>3.2 php=>5.2.4
        if (is_multisite() && wp_translitera::useglobalconfig()) {
            $set = get_site_option('wp_translitera');
        } else {
            $set = get_option('wp_translitera');
        }
        if (gettype($set) != 'array') {
            $set = array();
        }
        return $set;
    }
    
    //Получаем настройку использования глобальных настроек
    //добавлено p1.2
    //возвращает БУЛЕВО
    protected static function useglobalconfig() {//wp=>3.2 php=>5.2.4
        $set = get_option('wp_translitera');
        if (gettype($set) != 'array') {
            $set = array();
        }
        $name = 'use_global_mu_settings';
        return (array_key_exists($name,$set))?$set[$name]:FALSE;
    }

    ////Получаем значение настройки
    // добалено 160128
    // принимает:   - name СТРОКА с названием настройки плагина
    //              - def MIXED Значение которое возвращается в случае отсутсвия настройки введно p1.0.1
    //              - local BOOLEAN получить значение локальных настроек или глобальных
    // возвращает: MIXED значение настройки плагина или NULL если не установлено
    protected static function getset($name,$def=NULL) {//wp=>3.2 php=>5.2.4
        $set = wp_translitera::getoptions();
        return (array_key_exists($name,$set))?$set[$name]:$def;
    }
    
    //Записываем опцию
    // добавлено 170212
    // принимает: МАССИВ с настройками плагина
    protected static function updateoption($set) {//wp=>3.2 php=>5.2.4
        if (is_multisite() && wp_translitera::useglobalconfig()) {
            update_site_option('wp_translitera',$set);
        } else {
            update_option('wp_translitera',$set);
        }
    }

    //Записываем настройку
    // добавлено 160128
    // принимает:   - name СТРОКА с названием настройки
    //              - value MIXED значение настроки
    protected static function updset($name,$value) {//wp=>3.2 php=>5.2.4
        $set = wp_translitera::getoptions();
        $set[$name] = $value;
        wp_translitera::updateoption($set);
    }
    
    //Записываем настройки переданные в массиве не изменяя остальных
    // добавлено 160212
    // принимает: МАССИВ со значениями настроек
    protected static function updsets($sets) {//wp=>3.2 php=>5.2.4
        if (gettype($sets) != 'array') {
            $sets = array();
        }
        $set = $sets + wp_translitera::getoptions();
        wp_translitera::updateoption($set);
    }
    
    //Выводим сообщение об обновлении 
    // добавлено в 170510
    // принимает БУЛЕВО необходимость выводить сообщение, с версии p1.0 - значение по умолчанию TRUE
    // возвращает БУЛЕВО необходимость выводить сообщение
    protected static function updnotice($need_notice=TRUE) {//wp=>3.2 php=>5.2.4
        if ($need_notice) {
            add_action('admin_notices',array('wp_translitera','notice_admin_plugin_updated'));
        }
        return FALSE;
    }
    
    //удаляем настройку
    // добавлено p1.2.5
    // принимает Строку с индентификатором настройки
    protected static function delset($name) {
        $sets = wp_translitera::getoptions();
        unset($sets[$name]);
        wp_translitera::updateoption($sets);
    }

    //Выполняем обновление
    //добавлено 170212
    //Принимает:
    // from - текущая версия плагина
    // for - параметр принимающий актуальную версию плагина (добавлен p1.0)
    protected static function update_bd($from,$for) {//wp=>3.2 php=>5.2.4
        if (empty($from)) {
            $from = 160819;
        }
        
        if (version_compare($from, 160819,'<=')) {
            if (wp_translitera::getset('fileext') == NULL) {
                wp_translitera::updset('fileext', array());
            }
            $from = 161011;
        }
        if (version_compare($from, 161011,'<=')) {
            if (is_multisite()) {
                $set = wp_translitera::getoptions();
                global $wpdb;

                $blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
                $original_blog_id = get_current_blog_id();

                foreach ( $blog_ids as $blog_id )   {
                    switch_to_blog( $blog_id );
                    update_site_option('wp_translitera',$set);
                }

        	switch_to_blog( $original_blog_id );
            }
            $from = 170212;
        }
        if (version_compare($from, 170212,'<=')) {
            if (file_exists(__DIR__.'/unistall.php')) {
                unlink(__DIR__.'/unistall.php');
            }
            $from = 170510;
        }
        if (version_compare($from, 'p1.2.1','<=')) {
            if (file_exists(__DIR__.'/tables.php')) {
                unlink(__DIR__.'/tables.php');
            }
            if (file_exists(__DIR__.'/worker.php')) {
                unlink(__DIR__.'/worker.php');
            }
            $from = 'p1.2.2';
        }
        if (version_compare($from, 'p1.2.4','<=')) {
            wp_translitera::delset('init_in_front');
            $from = 'p1.2.5';
        }
        if ($for !=$from) {
            wp_translitera::updnotice();
            $from = $for;
            wp_translitera::updset('version', $from);
        }
    }

    //Вызываемые дочерние функции
    
    //Уведомление о необходимости проверить настройки
    //добавлено 170212
    static function notice_admin_plugin_updated() {//wp=>3.2 php=>5.2.4
        echo '<div class="updated" style="padding-top: 15px; padding-bottom:15px">'.__('Plugin WP Translitera has been updated,','wp-translitera').' <a href="options-general.php?page=wp-translitera%2Fwp-translitera">'.__('update settings','wp-translitera').'</a></div>';
    }
    
    //Модуль формы админки -выводит форму
    // добавлено 160119
    public static function main_settings() {//wp=>3.2 php=>5.2.4
        global $wpdb;
        
        //инициализация языка
        
        //удаляем глобальный язык, он не нужен Добавлено в p1.2.5
        $filename = WP_LANG_DIR . '/plugins/wp-translitera-'.get_locale().'.mo';
        if (file_exists($filename)) {
            unlink($filename);
        }
        
        load_plugin_textdomain('wp-translitera', false, __DIR__.'/languages');
        
        // обработка существующих объектов
        $act = filter_input(INPUT_POST, 'transliterate');
        if (!empty($act)) {
            $r1 = filter_input(INPUT_POST, 'r1');
            $r2 = filter_input(INPUT_POST, 'r2');
            if (!empty($r1)) {
                wp_translitera::do_transliterate($wpdb->posts, 'ID', 'post_name');
            }
            if (!empty($r2)) {
                wp_translitera::do_transliterate($wpdb->terms, 'term_id', 'slug');
            }
            //wpforo добавлено p1.0.3
            $f1 = filter_input(INPUT_POST, 'f1');
            $f2 = filter_input(INPUT_POST, 'f2');
            if (!empty($f1) || !empty($f2)) {
                $blogprefix = $wpdb->get_blog_prefix();
                if (!empty($f1)) {
                    wp_translitera::do_transliterate($blogprefix . 'wpforo_forums', 'forumid', 'slug');
                }
                if (!empty($f2)) {
                    wp_translitera::do_transliterate($blogprefix . 'wpforo_topics', 'topicid', 'slug');
                }
                wpforo_clean_cache();
            }
            //---
        }

        //Настройки
        $setupd = filter_input(INPUT_POST, 'apply');
        $sets = array();
        if (!empty($setupd)) {
            $sets['tranliterate_uploads_file'] = filter_input(INPUT_POST, 'tranliterate_uploads_file');
            $sets['tranliterate_404'] = filter_input(INPUT_POST, 'tranliterate_404');
            $sets['fileext'] = explode(',', filter_input(INPUT_POST, 'typefiles'));
            $sets['use_force_transliterations'] = filter_input(INPUT_POST, 'use_force_transliterations');
            $sets['disable_in_front'] = filter_input(INPUT_POST, 'disable_in_front');
            $sets['lowercase_filename'] = filter_input(INPUT_POST, 'lowercase_filename'); //добавлено p1.1
            $sets['use_global_mu_settings'] = filter_input(INPUT_POST, 'use_global_mu_settings'); //добавлено p1.2
            $rulesstring = filter_input(INPUT_POST, 'customrules');
            $rulesrawarray = explode(PHP_EOL, $rulesstring);
            $rulesarray = array();
            foreach ($rulesrawarray as $value) {
                $value = trim($value);
                if (empty($value) || $value == '=') {
                    continue;
                }
                $tmp = explode('=', $value);
                $rulesarray[$tmp[0]] = $tmp[1];
            }
            $sets['custom_rules'] = json_encode($rulesarray);
            wp_translitera::updsets($sets);
        }
        wp_translitera::GetForm();
    }
    
    //Проверяем активен ли wpforo
    // добавлено p1.0.3
    // Возвращает БУЛЕВО
    public static function wpforoactive() {//wp=>3.2 php=>5.2.4
        $activeplugins = get_option('active_plugins');
        if (gettype($activeplugins) != 'array') {
            $activeplugins = array();
        }
        return in_array("wpforo/wpforo.php", $activeplugins);
    }

    //Вызываемые функции
    
    //Процедура преобразования символов
    // добавлено в 150712
    // принимает : title - СТРОКА которую необходимо транслитерировать
    // возвращает : СТРОКА
    public static function transliterate($title,$UseSpecSimbol=TRUE) {//wp=>3.2 php=>5.2.4
        //Отбрасывает файлы с расширениями для который отключена трнаслитерация 
        // добавлено 161011
        $type = substr(filter_input(INPUT_POST, 'name'),-3);
        if (!empty($type)) {
            if (in_array($type, wp_translitera::getset('fileext',array()))) {
                return $title;
            }
        }
        $title = strtr($title, wp_translitera::createlocale());
        
        if ($UseSpecSimbol) { // add p1.2.3 игнор спецсимволов для корректной работы 404
            //add in p1.2.1 удаление непечатных и лишних символов
            $title = preg_replace("/[^A-Za-z0-9'_\-\.]/", '-', $title);
            $title = preg_replace('/\-+/', '-', $title);
            $title = preg_replace('/^-+/', '', $title);
            $title = preg_replace('/-+$/', '', $title);
        }
        return $title;
    }
    
    //Процедура преобразования символов форсированный режим
    // добавлен 161114
    // принимает:   - title СТРОКА стандартной транслитерации
    //              - raw_title СТРОКА для форсированной транслитерации
    // возвращает: СТРОКА транслитерированная строка
    public static function transliterate_force($title, $raw_title) {//wp=>3.2 php=>5.2.4
        return sanitize_title_with_dashes(wp_translitera::transliterate($raw_title));
    }
    
    //Добавляем раздел в админку
    // добавлено 150119    
    public static function add_menu(){//wp=>3.2 php=>5.2.4
        add_options_page('WP Translitera', 'Translitera', 'activate_plugins', __FILE__, array('wp_translitera','main_settings'));
    }
        
    //Попытка транслитерировать урл
    // добавлено 160707
    public static function init404(){//wp=>3.2 php=>5.2.4
        //wpforo suport добавлено p1.0.3
        $this404 = is_404();
        if (wp_translitera::wpforoactive()) {
            global $wpforo;
            if ($this404 || $wpforo->current_object['is_404']) {
                $this404 = TRUE;
            }
        }
        //---
        
        if ($this404){
            if (wp_translitera::getset('tranliterate_404')){
                $thisurl = $_SERVER['REQUEST_URI']; 
                $trurl = wp_translitera::transliterate(urldecode($thisurl),FALSE);
                if ($thisurl != $trurl) {
                    wp_redirect($trurl,301);
                }
            }
        }
    }
    
    //Обработка файлов загружаемых из форм
    // добавлено 160521
    // принимает    - value СТРОКА имя файла для транслитерации
    //              - filename_raw СТРОКА имя файла RAW
    // возвращает СТРОКА траслитерированное имя файла
    public static function rename_uploads_additional($value, $filename_raw) {//wp=>3.2 php=>5.2.4
        if (wp_translitera::getset('tranliterate_uploads_file')){
            $value = wp_translitera::transliterate($value);
        }
        //Переводим наименования файлов в нижний регистр добавлено p1.1
        if (wp_translitera::getset('lowercase_filename')) {
            $value = strtolower($value);
        }
        //
        return $value; 
    }
    
    //провека необходимости обновить БД
    //Добавлено 170212
    static function needupdate() {//wp=>3.2 php=>5.2.4
        $from = wp_translitera::getset('version');
        $plugindata = get_plugin_data(__FILE__) ;
        if ($from != $plugindata['Version']) {
            wp_translitera::update_bd($from,$plugindata['Version']);
        }
    }
    
    //инициализация метода транслитерации
    // Добавлено 170212
    static function prepare_transliterate() {//wp=>3.2 php=>5.2.4
        if (wp_translitera::getset('use_force_transliterations')) {
            add_filter('sanitize_title', array('wp_translitera','transliterate_force'), 25, 2);
        } else {
            add_filter('sanitize_title', array('wp_translitera','transliterate'), 0);
        }
    }
    
    //Добавляем ссылку на страницу настроек
    // Добавлено 170212
    static function add_plugin_settings_link($links) {//wp=>3.2 php=>5.2.4
        $addlink['settings'] = '<a href="options-general.php?page=wp-translitera%2Fwp-translitera">'.__('Settings','wp-translitera').'</a>';
        $links = $addlink + $links;
        return $links;
    }

    //Инициализация ядра
    // добавлено 161114
    static function init() {//wp=>3.2 php=>5.2.4
        //Проверка необходимости обновить БД
        add_action('admin_init', array('wp_translitera', 'needupdate'));
        
        //Добавляем админ меню и ссылку настроек
        add_action('admin_menu', array('wp_translitera', 'add_menu'));
        $plugin_file = plugin_basename(__FILE__);
        add_filter("plugin_action_links_$plugin_file",array('wp_translitera','add_plugin_settings_link'));
                
        //Инициализировать только для админки или везде
        if (!wp_translitera::getset('disable_in_front')) {
            wp_translitera::prepare_transliterate();
        } else {
            add_action('admin_init',array('wp_translitera', 'prepare_transliterate'));
        }
    }
    
    //Установка плагина
    // добавлено p1.0
    static function install() {//wp=>3.2 php=>5.2.4
        $plugindata = get_plugin_data(__FILE__) ;
        wp_translitera::updset('version', $plugindata['Version']);
    }
}

//wp=>3.2 php=>5.2.4
//Инициализация ядра
add_action('init', array('wp_translitera', 'init'));
//Редирект 404
add_action('wp',array('wp_translitera','init404'),(wp_translitera::wpforoactive())?11:10);
//Переименовываение загружаемых файлов
add_filter('sanitize_file_name',array('wp_translitera', 'rename_uploads_additional'),10,2);
//Установка плагина
register_activation_hook(__FILE__, array('wp_translitera','install'));