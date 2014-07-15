/*
Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function(config) {
   config.filebrowserBrowseUrl = '../../tools/ckeditor/kcfinder/browse.php?type=files';
   config.filebrowserImageBrowseUrl = '../../tools/ckeditor/kcfinder/browse.php?type=images';
   config.filebrowserFlashBrowseUrl = '../../tools/ckeditor/kcfinder/browse.php?type=flash';
   config.filebrowserUploadUrl = '../../tools/ckeditor/kcfinder/upload.php?type=files';
   config.filebrowserImageUploadUrl = '../../tools/ckeditor/kcfinder/upload.php?type=images';
   config.filebrowserFlashUploadUrl = '../../tools/ckeditor/kcfinder/upload.php?type=flash';
   config.enterMode = CKEDITOR.ENTER_BR;
   config.shiftEnterMode = CKEDITOR.ENTER_P;
};
