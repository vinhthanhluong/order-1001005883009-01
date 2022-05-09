/**
 * @license Copyright (c) 2003-2019, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For complete reference see:
	// https://ckeditor.com/docs/ckeditor4/latest/api/CKEDITOR_config.html

	// The toolbar groups arrangement, optimized for two toolbar rows.
	config.toolbar = [
		{ name: 'group1',   items: [ 'Format', 'FontSize' ] },
		{ name: 'group2',   items: ['TextColor','BGColor','-','Bold','Italic','Underline',
											'Strike','-','Link','Unlink','-','RemoveFormat'] },
		'/',
		{ name: 'group3',   items: ['NumberedList','BulletedList','-','JustifyLeft','JustifyCenter',
											'JustifyRight','JustifyBlock','-','Table','Image',
											'HorizontalRule','Nbsp'] },
		{ name: 'group4',   items: ['Cut','Copy','Paste','PasteText','-','Undo','Redo'] },
		{ name: 'group5',   items: ['Source','-','Maximize'] }
	];
	
	config.extraPlugins = 'wordcount';
	config.skin = 'kama';
	config.format_tags = 'p;h3;h4;h5;div';
	config.removeDialogTabs = 'image:advanced;link:advanced';
	config.coreStyles_bold = {
		element : 'span',
		attributes : { 'style' : 'font-weight: bold;' }
	};
	config.allowedContent = true;
	config.templates_replaceContent = 0;
	config.wordcount = {
		showCharCount: true,
		showWordCount: false
	};
};
