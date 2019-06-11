=== Plugin Name ===
Contributors: YurchenkoEV
Donate link: http://yur4enko.com/
Tags: translate, rus-to-lat, cyr-to-lat, Seo, tag, record, page, transliterate, transliteration, bg, bulgarian
Requires at least: 3.2
Tested up to: 5.0
Stable tag: p1.2.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Плагин для транслитерации постоянных ссылок записей, стараниц, тегов, медиа и файлов
---
Plug-in for transliteration permanents links of records, pages, tag, media and files

== Description ==
Плагин для транслитерации постоянных ссылок записей, стараниц, тегов, медиа и файлов
---
Plug-in for transliteration permanents links of records, pages, tag, media and files

== Installation ==
Автоматичски из репозитория или распаковать в папку плагинов и активировать через панель управления
---
Automatical from repository or unzip in plugins and activate in ACP.

== Frequently Asked Questions ==

== Screenshots ==

== Changelog ==

= p1.2.5 =
* обновлены переводы
* добавлена грузинская траслитерация
* функция "включения" транслитерации заменена на "отключение" во фронт-энде
* ---
* translates updated
* added Georgian transliteration 
* the function of "turning on" transliteration is replaced by a "turning off" in the front end

= p1.2.4 =
* исправлена кнопка транслитерации существующих элементов
* ---
* Fixed transliteration of existing elements

= p1.2.3 =
* исправлена ошибка в работе кастомных правил транслитерации
* поправлен шаблон
* исправлена ошибка в режиме транслитерации 404 
* ---
* Fixed a bug in the work of custom transliteration rules
* fixed template
* Fixed bug in transliteration mode 404

= p1.2.2 = 
* исправлена ошибка при повторном вызове
* оптимизирован вызов локализации
* обновлен модуль обновления плагина
* ---
* Fixed bug with repeated call
* Optimized localization call
* updated plug-in update module

= p1.2.1 =
* исправлна мобильная версия и поравлен интерфейс
* исправлена работа с некоторыми кодировками
* исправлна работа с "лишними" и непечатными символами 
(https://wordpress.org/support/topic/%D0%BD%D0%B5%D0%BA%D0%BE%D1%80%D1%80%D0%B5%D0%BA%D1%82%D0%BD%D0%B0%D1%8F-%D1%82%D1%80%D0%B0%D0%BD%D1%81%D0%BB%D0%B8%D1%82%D0%B5%D1%80%D0%B0%D1%86%D0%B8%D1%8F-%D0%B7%D0%B0%D0%BF%D1%8F%D1%82%D0%BE%D0%B9/)
* исправлены пути для подключения таблиц транслитерации
* отказ от использования mbstring
* ---
* fixed mobile version and interface
* fixed work with some encodings
* fixed work with "excess" and unprinted characters
(https://wordpress.org/support/topic/%D0%BD%D0%B5%D0%BA%D0%BE%D1%80%D1%80%D0%B5%D0%BA%D1%82%D0%BD%D0%B0%D1%8F-%D1%82%D1%80%D0%B0%D0%BD%D1%81%D0%BB%D0%B8%D1%82%D0%B5%D1%80%D0%B0%D1%86%D0%B8%D1%8F-%D0%B7%D0%B0%D0%BF%D1%8F%D1%82%D0%BE%D0%B9/)
* fixed the path to connect transliteration tables
* renouncement to use mbstring

= p1.2 =
* таблицы транслитерации вынесены в отдельный файл
* обновлен интерфейс
* добавлена проверка активности модуля php mbstring
* обновлена и исправлена работа в режиме мультисайт
* исправлена совместимость "редиректа 404" с php-CGI
* ---
* transliteration tables moved in a separate file
* updated interface
* added check of the php mbstring module activity
* updated and corrected work in multisite mode
* compatibility of "redirect 404" with php-CGI is fixed

= p1.1.1 = 
* fix

= p1.1 =
* добавлена функция перевода в нижний регистр загружаемых файлов 
* шаблон админки и обработчик действий вынесены в отдельные файлы
* добавлена проверка кодировки исходных данных
* ---
* added function to lowercase the uploaded files
* the admin template and the action handler are rendered in separate files
* added source data encoding check

= p1.0.3 =
* добавлена полная совместимость с wpforo
* ---
* Added full compatibility with wpforo

= p1.0.2 =
* обновлена функция транслитерации (https://wordpress.org/support/topic/%D0%BF%D0%BB%D0%B0%D0%B3%D0%B8%D0%BD-%D0%BF%D0%B5%D1%80%D0%B5%D1%81%D1%82%D0%B0%D0%BB-%D1%82%D1%80%D0%B0%D0%BD%D1%81%D0%BB%D0%B8%D1%82%D0%B8%D1%80%D0%BE%D0%B2%D0%B0%D1%82%D1%8C-%D1%81%D1%82%D1%80/)
* ---
* Updated transliteration function (https://wordpress.org/support/topic/%D0%BF%D0%BB%D0%B0%D0%B3%D0%B8%D0%BD-%D0%BF%D0%B5%D1%80%D0%B5%D1%81%D1%82%D0%B0%D0%BB-%D1%82%D1%80%D0%B0%D0%BD%D1%81%D0%BB%D0%B8%D1%82%D0%B8%D1%80%D0%BE%D0%B2%D0%B0%D1%82%D1%8C-%D1%81%D1%82%D1%80/)

= p1.0.1 =
* исправлена ошибка обновления настроек приводящяя к неработоспособности плагина (ядро)
* ---
* Fixed a bug with updating the settings resulting in the inoperability of the plug-in (core)

= p1.0 =
* обновлен домен переводов (переводы)
* новая нумерация версий плагина (прочее)
* новый модуль обновления плагина (ядро)
* добавлены полные комментарии к коду (разработка)
* новый модуль форсированной транслитерации (ядро)
* добавлена обработка установки плагина (ядро)
* ---
* Updated translation domain (translations)
* New numbering of plug-in versions (other)
* A new module update of plug-in (core)
* Added full comments to the code (development)
* New module of forced transliteration (core)
* Added processing plugin installation (kernel)

= 17**** =
http://yur4enko.com/moi-proekty/wp-translitera/istorija-izmenenij-wp-translitera-17-versij

= 16**** =

http://yur4enko.com/moi-proekty/wp-translitera/istorija-izmenenij-wp-translitera-16-versij

= 15**** =
http://yur4enko.com/moi-proekty/wp-translitera/istorija-izmenenij-wp-translitera-15-versij

== Upgrade Notice ==
After update deactivate and activate plugin