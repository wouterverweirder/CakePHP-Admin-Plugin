(function(){

	var SimpleFileUpload = require('cakephp-admin-plugin/components/SimpleFileUpload');
	var ImageUpload = require('cakephp-admin-plugin/components/ImageUpload');

	function init() {
		initFileUploads();
		initCKEditors();
		initCKFinders();
	}

	function initFileUploads() {
		$('[data-fileupload]').each(function(index, element){
			var config = $(element).data('fileupload-config') || {};
			if(config.fileType === 'image') {
				new ImageUpload(element, config);
			} else {
				new SimpleFileUpload(element, config);
			}
		});
	}

	function initCKEditors() {
		$('[data-ckeditor]').each(function(index, element){
			var config = $(element).data('ckeditor-config') || {};
			CKEDITOR.replace(element, config);
		});
	}

	function initCKFinders() {
		$('[data-ckfinder]').each(function(index, element){
			var config = $(element).data('ckfinder-config') || {};
			var urlToRemoveForRelativePath = '';
			if(config.httpRoot) {
				urlToRemoveForRelativePath += config.httpRoot;
			}
			if(config.relativeUploadFolder) {
				urlToRemoveForRelativePath += config.relativeUploadFolder;
			}
			var $btn = $('<input type="button" value="Browse Server" class="btn" />');
			$(element).after($btn);
			$btn.on('click', function(){
				var finder = new CKFinder();
				finder.resourceType = config.resourceType;
				finder.selectActionFunction = function(fileUrl, data){
					fileUrl = fileUrl.substr(urlToRemoveForRelativePath.length + 1);
					$(element).val(fileUrl);
					$(element).trigger('change');
				};
				finder.popup();
			});
		});
	}

	init();

})();
