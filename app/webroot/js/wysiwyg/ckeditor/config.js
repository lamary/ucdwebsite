/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
//	config.extraPlugins = 'video';
	config.toolbar = [
	                  { name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
	                  { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote' ] },
	                  { name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Scayt' ] },
	                  { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
	                  { name: 'insert', items: ['Image','Table', 'HorizontalRule','Smiley', 'SpecialChar' ] },
	                  { name: 'document', groups: [ 'mode', 'document', 'doctools' ]},
	                  { name: 'others', items: [ '-' ] },
	                  '/',
	                  { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Strike', '-', 'RemoveFormat' ] },
	                  { name: 'paragraph', groups: [ 'align', 'bidi' ], items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
	                  { name: 'styles', items: [ 'Format','Font','FontSize' ] },
	                  { name: 'colors', items : [ 'TextColor','BGColor' ] },
	                  { name: 'tools', items: [ 'Maximize','ShowBlocks' ] }
	                  ];


	config.basicEntities = false;
	config.entities_greek = false; 
	config.entities_latin = false; 
	config.entities_additional = ''; 
//	config.enterMode = CKEDITOR.ENTER_BR,
	config.language = 'en';
	config.filebrowserBrowseUrl = '/js/wysiwyg/kcfinder/browse.php?type=files';
	config.filebrowserImageBrowseUrl = '/js/wysiwyg/kcfinder/browse.php?type=images';
	config.filebrowserFlashBrowseUrl = '/js/wysiwyg/kcfinder/browse.php?type=flash';
	config.filebrowserUploadUrl = '/js/wysiwyg/kcfinder/upload.php?type=files';
	config.filebrowserImageUploadUrl = '/js/wysiwyg/kcfinder/upload.php?type=images';
	config.filebrowserFlashUploadUrl = '/js/wysiwyg/kcfinder/upload.php?type=flash';
};


