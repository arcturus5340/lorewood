editor = new Object();
var changed = false;
function changeTrue() {
	changed = true;
}

function changeReset() {
	changed = false;
}

function hasChanged() {
	return changed;
}

function checkChanged() {
	if(hasChanged()) {
		if(confirm('You have unsaved changes on this page. Are you sure you want to load a new document?')) {
			changed = false;
		}
		else {
			changed = true;
		}
	}
	return changed;
}
function checkExtension(extension) {
	var ext = false;
	var exts = [
		'gif',
		'png',
		'jpg',
		'jpeg'
	];
	if(jQuery.inArray(extension, exts) >= 0) {
		ext = true;
	}
	return ext;
}

(function($){
	var c = function(){
		
	};
	$.extend(c.prototype, {
		name:'folders',initialize:function(element, handler){
			function ajaxFolders(handler){
				if(!checkChanged()) {
					handler.preventDefault();
					var newElement = $(this).closest('li', element);
					newElement.length || (newElement = element);
					if(newElement.hasClass(ajaxObject.options.open) && !newElement.hasClass('file')) {
						newElement.removeClass(ajaxObject.options.open).children('ul').slideUp();
					}
					else if(!newElement.hasClass(ajaxObject.options.open) && newElement.hasClass('file')) {

						if(checkExtension(newElement.data('extension'))) {
							return true;
						}
						else {
							var type = $('#content-type').val();
							runAjaxRequest(newElement, ajaxObject, 'file', type);
						}
					}
					else {
						var type = $('#content-type').val();
						runAjaxRequest(newElement, ajaxObject, null, type);
					}
				}
				else {
					return false;
				}
			}
			function runAjaxRequest(newElement, ajaxObject, contentType, type) {
				if(contentType === 'file'){
					var contents = 1;
				}
				else {
					var contents = 0;
				}
				if(newElement.data('encoded') === 1) {
					var path = newElement.data('path');
				}
				else {
					var path = encodeURI(newElement.data('path'));
				}
				var data = {
					action: 'ajax_folders',
					dir: path,
					contents: contents,
					type: type
				}

				if ( type == 'theme' ) {
					data.wp_editor_ajax_nonce_ajax_folders_themes = WPE.wp_editor_ajax_nonce_ajax_folders_themes;
				}
				else if ( type == 'plugin' ) {
					data.wp_editor_ajax_nonce_ajax_folders_plugins = WPE.wp_editor_ajax_nonce_ajax_folders_plugins;
				}
				
				newElement.addClass(ajaxObject.options.loading),
				$.post(
					ajaxObject.options.url, data, function(result){
						if(contentType === 'file'){
							newElement.removeClass(ajaxObject.options.loading);
							if(result.content == null) {
								$('#save-result').html('<div id="save-message" class="WPEditorAjaxError"></div>');
								$('#save-message').append('<h3>Warning</h3><p>An error occured while trying to open this file</p>');
								$('#save-result').fadeIn(1000).delay(3000).fadeOut(300);
							}
							else {
								var notWritable = '';
								if(!result.writable) {
									$('p.submit').hide();
									$('div.writable-error').show();
									notWritable = ' <span class="not-writable">(not writable)</span>';
									$('.writable_status').html('Browsing');
								}
								else {
									$('p.submit').show();
									$('div.writable-error').hide();
									$('.writable_status').html('Editing');
								}
								wp_editor.toTextArea();
								$('#new-content').val(result.content);
								$('#file').val(result.file);
								$('#path').val(result.path);
								$('#file_path').val(result.path);
								$('#extension').val(result.extension);
								$('.current_file').html(result.file + notWritable);
								runCodeMirror(result.extension);
							}
						}
						else {
							newElement.removeClass(ajaxObject.options.loading).addClass(ajaxObject.options.open);
							result.length && (
								newElement.children().remove('ul'),
								newElement.append('<ul>').children('ul').hide(),
								$.each(result, function(index, value){
									if(checkExtension(value.extension)) {
										newElement.children('ul').append(
											$('<li><a href="' + value.url + '" class="nivo-lightbox ' + value.filetype + '">' + value.name + ' <span class="tiny">' + value.filesize + '</span></a></li>').addClass(
												value.extension + ' ' + value.filetype
											).data({
												'path': value.path,
												'content': value.content,
												'filesize': value.filesize,
												'file': value.file,
												'extension': value.extension,
												'url': value.url,
												'writable': value.writable
											}
										));
										$('a.nivo-lightbox').nivoLightbox();
									}
									else {
										var writable = '';
										var writableClass = '';
										if(!value.writable) {
											writable = '<span class="writable">&times;</span>';
											writableClass = ' not-writable';
										}
										newElement.children('ul').append(
											$('<li><a href="#" class="' + value.filetype + writableClass + '">' + writable + ' ' + value.name + ' <span class="tiny">' + value.filesize + '</span></a></li>').addClass(
												value.extension + ' ' + value.filetype
											).data({
												'path': value.path,
												'content': value.content,
												'filesize': value.filesize,
												'file': value.file,
												'extension': value.extension,
												'url': value.url,
												'writable': value.writable
											}
										))
									}
								}),
								newElement.find('ul a').bind('click', ajaxFolders),
								newElement.children('ul').slideDown()
							)
						}
				}, 'json')
			}
			var ajaxObject = this;
			this.options = $.extend({
				url: '',
				path: '',
				url: '',
				encoded: '',
				content: '',
				filesize: '',
				file: '',
				extension: '',
				writable: '',
				open: 'opened',
				loading: 'loading'
			}, handler);
			element.data({
				'path': this.options.path, 
				'encoded': this.options.encoded, 
				'content': this.options.content,
				'filesize': this.options.filesize,
				'file': this.options.file,
				'extension': this.options.extension,
				'url': this.options.url,
				'writable': this.options.writable
			}).bind('retrieve:finder', ajaxFolders).trigger('retrieve:finder')
		}
	});
	$.fn[c.prototype.name] = function(){
		var b = arguments
		var g = b[0] ? b[0] : null;
		return this.each(function(){
			var f = $(this);
			if(c.prototype[g] && f.data(c.prototype.name) && g != 'initialize'){
				f.data(c.prototype.name)[g].apply(
					f.data(c.prototype.name),
					Array.prototype.slice.call(b, 1)
				);
			}
			else if(!g || $.isPlainObject(g)){
				var e = new c;
				c.prototype.initialize && e.initialize.apply(e, $.merge([f], b));
				f.data(c.prototype.name, e)
			}
			else {
				$.error('Method ' + g + ' does not exist on jQuery.' + c.name)
			}
		})
	}
})(jQuery);
(function($){
	$(document).ready(function(){
		
		$('#new-content').change(function() {
			changeTrue();
		});
		
		$('#save-result').click(function() {
			$(this).hide();
		});
		$('.ajax-settings-form').submit(function() {
			var data = getFormData($(this).attr('id'));
			$.ajax({
					type: "POST",
					url: ajaxurl,
					data: data,
					dataType: 'json',
					success: function(result) {
						$('#save-result').html("<div id='save-message' class='" + result[0] + "'></div>");
						$('#save-message').append(result[1]);
						$('#save-result').fadeIn(1000).delay(3000).fadeOut(300);
					}
			});
			return false;
		});
		
		$('.ajax-editor-update').submit(function() {
			wp_editor.save();

			var data = {
				action: 'save_files',
				real_file: $('#path').val(),
				new_content: $('#new-content').val(),
				file: $('#file').val(),
				plugin: $('#plugin-dirname').val(),
				extension: $('#extension').val(),
				_success: $('#_success').val()
			}

			if ( $('#theme-name').length ) {
				data.wp_editor_ajax_nonce_save_files_themes = WPE.wp_editor_ajax_nonce_save_files_themes;
			}
			else if ( $('#plugin-dirname').length ) {
				data.wp_editor_ajax_nonce_save_files_plugins = WPE.wp_editor_ajax_nonce_save_files_plugins;
			}

			$.ajax({
					type: "POST",
					url: ajaxurl,
					data: data,
					dataType: 'json',
					success: function(result) {

						$('#save-result').html("<div id='save-message' class='" + result[0] + "'></div>");
						$('#save-message').append(result[1]);
						$('#save-result').fadeIn(1000).delay(3000).fadeOut(300);
						changeReset();

					}
			});
			return false;
		});
		$(window).bind('beforeunload', function() {
			if(hasChanged()) {
				return 'Leaving this page will undo all changes you have made.';
			}
		});
	});
})(jQuery);
function enableThemeAjaxBrowser(path) {
	$jq = jQuery.noConflict();
	var c;
	var url = ajaxurl;
	$jq('#theme-folders').folders({
		url: url,
		path: path,
		encoded: 1
	}).delegate('a','click',function() {
		$jq('#theme-folders li').removeClass('selected');
		c = $jq(this).parent().addClass('selected').data('path')
	});
}
function enablePluginAjaxBrowser(path) {
	$jq = jQuery.noConflict();
	var c;
	var url = ajaxurl;
	$jq('#plugin-folders').folders({
		url: url,
		path: path,
		encoded: 1
	}).delegate('a','click',function() {
		$jq('#plugin-folders li').removeClass('selected');
		c = $jq(this).parent().addClass('selected').data('path')
	});
}
function getFormData(formId) {
	$jq = jQuery.noConflict();
	var theForm = $jq('#' + formId);
	var str = '';
	$jq('input:not([type=checkbox], :radio), input[type=checkbox]:checked, input:radio:checked, select, textarea', theForm).each(
		function() {
			var name = $jq(this).attr('name');
			var val = encodeURIComponent($jq(this).val());
			str += name + '=' + val + '&';
		}
	);
	return str.substring(0, str.length-1);
}
function settingsTabs(tab) {
	$jq = jQuery.noConflict();
	$jq('#settings-' + tab).show();
	$jq('#settings-loading').hide();
	$jq('#settings-' + tab + '-tab a').addClass('active');
	$jq('div.settings-tabs ul li a').click(function(){
		var thisClass = $jq(this).attr('id').replace('settings-link-','');
		$jq('div.settings-body').hide();
		$jq('#settings-' + thisClass).fadeIn(300);
		$jq('div.settings-tabs ul li a').removeClass('active');
		$jq('#settings-link-' + thisClass).addClass('active');
	});
}