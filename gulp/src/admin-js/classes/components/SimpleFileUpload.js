module.exports = (function(){

	var Class = require('cakephp-admin-plugin/core/Class');

	var SimpleFileUpload = Class.extend({
		init: function(input, options) {
			this.input = input;
			this.options = this.parseOptions(options);

			this.$valueElement = $(input);
			this._value = null;
			this.progressValue = 0;
			this.fieldId = this.$valueElement.attr('id');
			this.fieldName = this.$valueElement.attr('name');

			this.initializeHtml();
			this.storeHtmlElementsAsProperties();
			this.initializeEvents();
			this.$progressElement.hide();
			this.updateVisibility();

			this.setValue(this.$valueElement.val());
		},

		parseOptions: function(options) {
			if((!this.options || !this.options.fileType) && !options.fileType){
				options.fileType = 'file';
			}
			if((!this.options || !this.options.httpRoot) && !options.httpRoot){
				options.httpRoot = '/';
			}
			return options;
		},

		updateOptions: function(newOptions) {
			$.extend(this.options, this.parseOptions(newOptions));
		},

		generateUrl: function() {
			var url = '';
			if(this.options.uploadUrl) {
				url += this.options.uploadUrl;
			}
			return url;
		},

		initializeHtml: function() {
			this.$valueElement.wrap('<div>');
			this.$element = this.$valueElement.parent();

			var template = require('./simplefileupload.handlebars');
			this.$element.append(template({}));
		},

		storeHtmlElementsAsProperties: function() {
			this.$selectButton = this.$element.find('.btn.fileinput-select');
			this.$changeButton = this.$element.find('.btn.fileinput-change');
			this.$removeButton = this.$element.find('.btn.fileinput-remove');
			this.$progressElement = this.$element.find('.progress');
			this.$errorElement = this.$element.find('.text-error');
		},

		initializeEvents: function() {
			this.$selectButton.on('click', $.proxy(this.onBrowseClick, this));
			this.$changeButton.on('click', $.proxy(this.onBrowseClick, this));
			this.$removeButton.on('click', $.proxy(this.onRemoveClick, this));
			this.$valueElement.on('change', $.proxy(this.onValueChange, this));
		},

		initFileUploadComponent: function() {
			$("#jq" + this.options.fieldId).remove();
			$('body').append('<input id="jq' + this.options.fieldId + '" type="file" name="files[]" style="position: absolute; top: -100px;" class="fileupload">');
			this.$hiddenFileElement = $("#jq" + this.options.fieldId);
			this.$hiddenFileElement.fileupload(this.getUploadOptions()).on(
			'fileuploadadd', $.proxy(function(e, data){ this.onAdd(this, data); }, this)
			).on(
			'fileuploadsubmit', $.proxy(function(e, data){ this.onSubmit(this, data); }, this)
			).on(
			'fileuploaddone', $.proxy(function(e, data){ this.onUploadFinished(this, data); }, this)
			).on(
			'fileuploadprogressall', $.proxy(function(e, data){ this.onUploadProgress(this, data); }, this)
			).on(
			'fileuploadfail', $.proxy(function(e, data){ this.onUploadFail(this, data); }, this)
			);
		},

		getUploadOptions: function() {
			var uploadOptions = {
				dataType: 'json',
				url: this.generateUrl(),
				maxChunkSize: 10000000 // 10 MB
			};
			if(this.options.acceptFileTypes) {
				uploadOptions.acceptFileTypes = this.options.acceptFileTypes;
			}
			return uploadOptions;
		},

		onAdd: function(e, data) {
		},

		onSubmit: function(e, data) {
			$(this).trigger('fileuploadsubmit');
			this.setProgress(0);
			this.$selectButton.hide();
			this.$changeButton.hide();
			this.$removeButton.hide();
			this.$progressElement.show();
			this.$errorElement.hide();
		},

		onUploadFinished: function(e, data) {
			this.updateVisibility();
			if(data.result.files[0].error) {
				this.displayError(data.result.files[0].error);
			} else {
				this.setValue(data.result.files[0].name);
			}
			this.$progressElement.hide();
			$(this).trigger('fileuploaddone');
		},

		onUploadFail: function(e, data) {
			this.updateVisibility();
			this.displayError('file upload failed');
			this.$progressElement.hide();
		},

		onUploadProgress: function(e, data) {
			this.setProgress(data.loaded / data.total);
		},

		onBrowseClick: function() {
			this.initFileUploadComponent();
			this.$hiddenFileElement.trigger('click');
		},

		onRemoveClick: function() {
			this.setValue('');
		},

		displayError: function(error) {
			this.$errorElement.html(error).show();
		},

		updateVisibility: function() {
			if(this.$valueElement.val() !== '') {
				this.$selectButton.hide();
				this.$changeButton.show();
				this.$removeButton.show();
			} else {
				this.$selectButton.show();
				this.$changeButton.hide();
				this.$removeButton.hide();
			}
			this.$errorElement.hide();
		},

		onValueChange: function(event) {
			this.setValue(this.$valueElement.val());
		},

		setValue: function(value) {
			if(value !== this._value) {
				this._value = value;
				this.$valueElement.val(this._value);
				this.updateVisibility();
			}
		},

		getValue: function(value) {
			return this._value;
		},

		setProgress: function(progress) {
			this.progressValue = progress;
			this.$progressElement.find('.bar').css('width', parseInt(this.progressValue * 100, 10) + '%');
		}
	});

return SimpleFileUpload;

})();
