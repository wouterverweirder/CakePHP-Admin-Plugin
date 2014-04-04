module.exports = (function(){

	var SimpleFileUpload = require('cakephp-admin-plugin/components/SimpleFileUpload');

	var ImageUpload = SimpleFileUpload.extend({
		parseOptions: function(options) {
			options = this._super(options);
			if(!options.previewWidth){
				options.previewWidth = 185;
			}
			if(!options.previewHeight){
				options.previewHeight = 185;
			}
			if(!options.previewText){
				if(options.requiredWidth && options.requiredHeight) {
					options.previewText = options.requiredWidth + 'x' + options.requiredHeight;
				} else {
					options.previewText = 'no image';
				}
			}
			if(options.requiredWidth && options.requiredHeight) {
				this.imageSizeErrorMessage = 'needs to be ' + options.requiredWidth + 'x' + options.requiredHeight;
			}
			return options;
		},
		updateOptions: function(newOptions) {
			this._super(newOptions);
			//update html
			var $noPreviewThumbnailHolder = this.$element.find('.fileupload-no-preview.thumbnail');
			var $previewThumbnailHolder = this.$element.find('.fileupload-preview.thumbnail');
			$noPreviewThumbnailHolder.css({
				width: this.options.previewWidth + "px",
				height: this.options.previewHeight + "px"
			});
			$previewThumbnailHolder.css({
				width: this.options.previewWidth + "px",
				height: this.options.previewHeight + "px"
			});
			$noPreviewThumbnailHolder.find('img').attr('src', 'http://www.placehold.it/' + this.options.previewWidth + 'x' + this.options.previewHeight + '/EFEFEF/AAAAAA&text=' + encodeURIComponent(this.options.previewText));
		},
		generateUrl: function() {
			var url = this._super();
			if(this.options.requiredWidth){
				url += '&required_width=' + this.options.requiredWidth;
			}
			if(this.options.requiredHeight){
				url += '&required_height=' + this.options.requiredHeight;
			}
			return url;
		},
		storeHtmlElementsAsProperties: function() {
			this._super();
			this.$previewElement = this.$element.find('.fileupload-preview');
			this.$noPreviewElement = this.$element.find('.fileupload-no-preview');
		},
		initializeHtml: function() {
			this._super();
			var previewHtml = '<div class="fileupload-no-preview thumbnail" style="width: ' + this.options.previewWidth + 'px; height: ' + this.options.previewHeight + 'px; margin-bottom: 5px;"><img src="http://www.placehold.it/' + this.options.previewWidth + 'x' + this.options.previewHeight + '/EFEFEF/AAAAAA&amp;text=' + encodeURIComponent(this.options.previewText) + '" /></div>';
			previewHtml += '<div class="fileupload-preview fileupload-exists thumbnail" style="width: ' + this.options.previewWidth + 'px; height: ' + this.options.previewHeight + 'px; margin-bottom: 5px; background-size: contain; background-position: center center; background-repeat: no-repeat;"></div>';
			this.$element.find('.progress').before(previewHtml);
		},
		displayError: function(error) {
			if(error.indexOf('width') > -1 || error.indexOf('height') > -1) {
				this.$errorElement.html(this.imageSizeErrorMessage).show();
			} else {
				this.$errorElement.html(error).show();
			}
		},
		updateVisibility: function() {
			this._super();
			this.$valueElement.hide();
			if(this.$valueElement.val() !== '') {
				this.$noPreviewElement.hide();
				this.$previewElement.show();
			} else {
				this.$noPreviewElement.show();
				this.$previewElement.hide();
			}
		},

		setValue: function(value) {
			if(value !== this._value) {
				this._super(value);
				if(this._value && this._value.length > 0) {
					this.$previewElement.css('background-image', 'url(\'' + this.options.httpRoot + 'files/images/' + this._value + '\')');
				} else {
					this.$previewElement.css('background-image', false);
				}
			}
		}
	});

	return ImageUpload;

})();
