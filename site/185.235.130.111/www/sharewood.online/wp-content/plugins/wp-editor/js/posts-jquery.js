editor_status = '';
tags = {};
(function($){
	$(window).resize(function() {
		$('.CodeMirror-scroll').height($('.CodeMirror-wrap').height() - $('#wp-editor-quicktags').height() - 3);
	});
	$(window).load(function() {
		setupPostEditor();
	});

	$(document).ready(function(){
		$('#content').attrchange({
			trackValues: true,
			/* enables tracking old and new values */
			callback: function(event) { //callback handler on DOM changes
				if(event.attributeName == 'style') {
					if(event.oldValue !== event.newValue) {
						$('.CodeMirror').css('top', $('#content').css('margin-top'));
						$('.CodeMirror').css('margin-bottom', $('#content').css('margin-top'));
					}
				}
			}
		});
		QTags.addButton( 'fullscreen', 'fullscreen', wp_editor_fullscreen );
		function wp_editor_fullscreen() {
			if (wp_editor.getOption("fullScreen")) {
				wp_editor.setOption("fullScreen", false);
				$('#ed_toolbar').removeClass('fullscreen');
				$(window).resize();
			}
			else {
				$('#ed_toolbar').addClass('fullscreen');
				wp_editor.setOption("fullScreen", true);
			}
			wp_editor.focus();
		}
		/* // remove until we can figure out a way to save via ajax
		QTags.addButton( 'save', 'save', wp_editor_save );
		function wp_editor_save() {
			wp_editor.save();
			$('#wp_mce_fullscreen').val($('#content').val());
			window.wp.editor.fullscreen.save();
			changeReset();
		}*/

		$('body').on('click', '#wp-link-submit', function() {
			wp_editor.toTextArea();
			wpLink.update();
			var element = document.getElementById('content');
			var cursor = window.get_content_cursor(element, element.selectionStart);
			window.postCodeMirror('content');
			wp_editor.setCursor(cursor.line, cursor.ch);
		});

		$('#content-tmce').click(function() {
			if(editor_status !== 'tmce') {
				var scrollPosition = wp_editor.getScrollInfo();
				document.cookie="scrollPositionX=" + scrollPosition.x;
				document.cookie="scrollPositionY=" + scrollPosition.y;
				wp_editor.toTextArea();
				id = $(this).attr( 'data-wp-editor-id' );
				switchEditors.go(id, 'tmce');
				editor_status = 'tmce';
				return false;
			}
		});
		$('#content-html').click(function() {
			if(editor_status !== 'html') {
				id = $(this).data( 'wp-editor-id' );
				switchEditors.go(id, 'html');
				setTimeout(function() {
					window.postCodeMirror('content');
					wp_editor.scrollTo(getCookie('scrollPositionX'), getCookie('scrollPositionY'));
				}, 0);
				editor_status = 'html';
				return false;
			}
			else {
				var scrollPosition = wp_editor.getScrollInfo();
				wp_editor.toTextArea();
				window.postCodeMirror('content');
				wp_editor.scrollTo(scrollPosition.x, scrollPosition.y);
				document.cookie="scrollPositionX=" + scrollPosition.x;
				document.cookie="scrollPositionY=" + scrollPosition.y;
				return false;
			}
		})
		$('#post').submit(function(e) {
			changeReset();
			if(editor_status == 'html') {
				var scrollPosition = wp_editor.getScrollInfo();
				document.cookie="scrollPositionX=" + scrollPosition.x;
				document.cookie="scrollPositionY=" + scrollPosition.y;
				wp_editor.save();
			}
		})
	})
	function getCookie(key, sub_key) {
		currentcookie = document.cookie;
		if(currentcookie.length > 0) {
			firstidx = currentcookie.indexOf(key + "=");
			if(firstidx != -1) {
				firstidx = firstidx + key.length + 1;
				lastidx = currentcookie.indexOf(";",firstidx);
				if(lastidx == -1) {
					lastidx = currentcookie.length;
				}
				if(sub_key) {
					var result = {};
					unescape(currentcookie.substring(firstidx, lastidx)).split("&").forEach(function(part) {
						var item = part.split("=");
						result[item[0]] = decodeURIComponent(item[1]);
					});
					return result[sub_key];
				}
				return unescape(currentcookie.substring(firstidx, lastidx));
			}
		}
		return "";
	}
	function setupPostEditor() {
		editor_status = getCookie('wp-settings-1', 'editor');
		if(editor_status == 'html') {
			window.postCodeMirror('content');
			//wp_editor.scrollTo(getCookie('scrollPositionX'), getCookie('scrollPositionY'));
		}
	}
	window.wp_editor_qt = function( element, start = true ) {
		if ( start ) {
			wp_editor.save();
			var fromCursor = wp_editor.getCursor('from');
			var toCursor = wp_editor.getCursor('to');
			window.set_content_cursor(element, fromCursor, toCursor);
		}
		else {
			var toCursorNew = window.get_content_cursor(element, 'to');
			wp_editor.setValue(element.value);
			wp_editor.setCursor(toCursorNew.line, toCursorNew.ch);
			wp_editor.refresh();
			wp_editor.focus();
		}
	}
	window.get_content_cursor = function( element, pos = 'from' ) {
		if ( document.selection ) { // IE
			var sel = document.selection.createRange();
			var selLength = document.selection.createRange().text.length;
			if(pos == 'from') {
				sel.moveStart('character', -element.value.length);
				var caret = sel.text.length - selLength;
			}
			else if(pos == 'to') {
				sel.moveStart('character', -element.value.length);
				var caret = sel.text.length;
			}
		} else if ( element.selectionStart || element.selectionStart === 0 ) { // FF, WebKit, Opera
			if(pos == 'from') {
				var caret = element.selectionStart;
			}
			else if(pos == 'to') {
				var caret = element.selectionEnd;
			}
		}

		var lines = element.value.substr(0, caret).split("\n");
		var newLength = 0, line = 0, lineArray = [];
		$.each(lines, function(key, value) {
			newLength = newLength + value.length + 1;
			lineArray[line] = newLength;
			if(caret > value.length) {
				caret -= value.length + 1
			}
			else {
				return false;
			}
			line++;
		});
		return {"line": line, "ch": caret};
	}
	window.set_content_cursor = function( element, fromCursor, toCursor = {} ) {

		if ( document.selection ) { // IE
			var sel = document.selection.createRange();
			var selLength = document.selection.createRange().text.length;
			sel.moveStart('character', -element.value.length);
			var startPos = sel.text.length - selLength, endPos = sel.text.length;
		} else if ( element.selectionStart || element.selectionStart === 0 ) { // FF, WebKit, Opera
			var startPos = element.selectionStart, endPos = element.selectionEnd;
		}
		var startLines = element.value.substr(0, start).split("\n");
		var endLines = element.value.substr(0, end).split("\n");
		var startNewLength = 0, startLine = 1, startLineArray = [];
		var endNewLength = 0, endLine = 1, endLineArray = [];
		
		if ( $.isEmptyObject( toCursor ) ) {
			toCursor = fromCursor;
		}

		startLineArray[0] = 0;
		$.each(startLines, function(key, value) {
			startNewLength = startNewLength + value.length + 1;
			startLineArray[startLine] = startNewLength;
			startLine++;
		});

		endLineArray[0] = 0;
		$.each(endLines, function(key, value) {
			endNewLength = endNewLength + value.length + 1;
			endLineArray[endLine] = endNewLength;
			endLine++;
		});
		
		var start = startLineArray[fromCursor.line] + fromCursor.ch, end = endLineArray[toCursor.line] + toCursor.ch;

		if(element.setSelectionRange) {
			$(element).show();
			element.focus();
			element.setSelectionRange(start, end);
			$(element).hide();
		}
		else if(element.createTextRange) {
			var range = element.createTextRange();
			range.collapse(true);
			range.moveEnd('character', end);
			range.moveStart('character', start);
			range.select();
		}
	};
	window.postCodeMirror = function(element) {
		var activeLine = WPEPosts.activeLine;
		wp_editor = CodeMirror.fromTextArea(document.getElementById(element), {
			mode: 'wp_shortcodes',
			theme: WPEPosts.theme,
			lineNumbers: WPEPosts.lineNumbers,
			lineWrapping: WPEPosts.lineWrapping,
			indentWithTabs: WPEPosts.indentWithTabs,
			indentUnit: WPEPosts.indentUnit,
			tabSize: WPEPosts.tabSize,
			onCursorActivity: function() {
				if(activeLine) {
					wp_editor.addLineClass(hlLine, null, null);
					hlLine = wp_editor.addLineClass(wp_editor.getCursor().line, null, activeLine);
				}
			},
			onChange: function() {
				changeTrue();
			},
			onKeyEvent: function(editor, event) {
				if(typeof(wpWordCount) != 'undefined') {
					wp_editor.save();
					last = 0, co = $('#content');
					$(document).triggerHandler('wpcountwords', [ co.val() ]);
					
					co.keyup(function(e) {
						var k = event.keyCode || event.charCode;
						
						if(k == last) {
							return true;
						}
						if(13 == k || 8 == last || 46 == last) {
							$(document).triggerHandler('wpcountwords', [ co.val() ]);
						}
						last = k;
						return true;
					});
				}
				
			},
			extraKeys: {
				"F11": function(cm) {
					if (!cm.getOption("fullScreen")) {
						$('#ed_toolbar').addClass('fullscreen');
					}
					else {
						$('#ed_toolbar').removeClass('fullscreen');
						$(window).resize();
					}
					cm.setOption("fullScreen", !cm.getOption("fullScreen"));
				},
				"Esc": function(cm) {
					if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
					$('#ed_toolbar').removeClass('fullscreen');
					$(window).resize();
				}
			}
		});
		$('.CodeMirror').css('font-size', WPEPosts.fontSize);
		$('.CodeMirror').css('top', $('#content').css('margin-top'));
		$('.CodeMirror').css('margin-bottom', $('#content').css('margin-top'));
		if(activeLine) {
			var hlLine = wp_editor.addLineClass(0, activeLine);
		}
		if(WPEPosts.editorHeight) {
			$('.CodeMirror-scroll, .CodeMirror, .CodeMirror-gutter').height(WPEPosts.editorHeight + 'px');
			var scrollDivHeight = $('.CodeMirror-scroll div:first-child').height();
			var editorDivHeight = $('.CodeMirror').height();
			if(scrollDivHeight > editorDivHeight) {
				$('.CodeMirror-gutter').height(scrollDivHeight);
			}
		}

	}
})(jQuery);