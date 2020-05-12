if (typeof component_classes['system_profile_picture_uploader'] === "undefined") {
	class system_profile_picture_uploader extends DivbloxDomBaseComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
			this.sub_component_definitions = [];
			// Sub component config end
			this.file_upload_array = [];
			this.prerequisite_array = ['project/assets/js/jquery_fileuploader/jquery.fileuploader.min.js'];
		}
        reset(inputs,propagate) {
	        this.initFileUploader();
            let api = $.fileuploader.getInstance('#'+this.getUid()+'_file_uploader');
            api.reset();
            super.reset(inputs,propagate);
        }
		saveEditedImage(item) {
			let uid = this.uid;
			let this_component = this;
			// if still uploading
			// pend and exit
			if (item.upload && item.upload.status == 'loading')
				return item.editor.isUploadPending = true;
			
			// if not preloaded or not uploaded
			if (!item.appended && !item.uploaded)
				return;
			
			// if no editor
			if (!item.editor || !item.reader.width)
				return;
			
			// if uploaded
			// resend upload
			if (item.upload && item.upload.resend) {
				item.upload.resend();
			}
			
			// if preloaded
			// send request
			if (item.appended) {
				// hide current thumbnail (this is only animation)
				item.imageIsUploading = true;
				item.image.addClass('fileuploader-loading').html('');
				item.html.find('.fileuploader-action-popup').hide();
				
				dxRequestInternal(getComponentControllerPath(this_component),{
					f:"handleResizeFile",
					_file: item.file, _editor: JSON.stringify(item.editor), fileuploader: 1
				},function(data_obj) {
					item.reader.read(function() {
						delete item.imageIsUploading;
						item.html.find('.fileuploader-action-popup').show();
						
						item.popup.html = item.popup.editor = item.editor.crop = item.editor.rotation = null;
						item.renderThumbnail();
					}, null, true);
					loadCurrentUserProfilePicture();
					pageEventTriggered("ProfilePictureChanged");
				},function(data_obj) {});
			}
		}
		resetFileUploader() {
			let file_uploader_instance = $.fileuploader.getInstance('#'+this.uid+'_file_uploader');
			file_uploader_instance.reset();
		}
		initFileUploader() {
			let uid = this.uid;
			let this_component = this;
			$('#'+uid+'_file_uploader').fileuploader({
				extensions: ['jpg', 'jpeg', 'png', 'gif'],
				changeInput: '<div class="fileuploader-input">' +
					'<div class="fileuploader-input-inner">' +
					'<div class="fileuploader-main-icon"></div>' +
					'<h3 class="fileuploader-input-caption"><span>${captions.feedback}</span></h3>' +
					'<p>${captions.or}</p>' +
					'<div class="fileuploader-input-button"><span>${captions.button}</span></div>' +
					'</div>' +
					'</div>',
				theme: 'dragdrop',
				thumbnails: {
					onImageLoaded: function(item) {
						if (!item.html.find('.fileuploader-action-edit').length)
							item.html.find('.fileuploader-action-remove').before('<a class="fileuploader-action fileuploader-action-popup fileuploader-action-edit" title="Edit"><i></i></a>');
						
						// hide current thumbnail (this is only animation)
						if (item.imageIsUploading) {
							item.image.addClass('fileuploader-loading').html('');
						}
					}
				},
				onSelect: function(item) {
					if (!navigator.onLine) {
						if (!item.html.find('.fileuploader-action-start').length)
							item.html.find('.fileuploader-action-remove').before('<a class="fileuploader-action fileuploader-action-start" title="Upload"><i></i></a>');
					} else {
						item.upload.send();
					}
				},
				upload: {
					url: getComponentControllerPath(this_component),
					data: {f:"handleFilePost",AuthenticationToken:getValueFromAppState('dxAuthenticationToken')},
					type: 'POST',
					enctype: 'multipart/form-data',
					start: false,
					synchron: true,
					beforeSend: function(item) {
						if (!navigator.onLine) {
							dx_has_uploads_waiting = true;
							return false;
						}
						// add editor to upload data
						// note! that php will automatically adjust _editorr to the file
						if (item.editor && (typeof item.editor.rotation != "undefined" || item.editor.crop)) {
							item.upload.data.fileuploader = 1;
							item.upload.data._editorr = JSON.stringify(item.editor);
						}
						
						item.html.find('.fileuploader-action-success').removeClass('fileuploader-action-success');
					},
					onSuccess: function(result, item) {
						var data = {};
						try {
							data = JSON.parse(result);
						} catch (e) {
							data.hasWarnings = true;
						}
						if (typeof data.Message !== "object") {
							if (data.Message == "ACCESS DENIED") {
								var progressBar = item.html.find('.progress-bar2');
								if(progressBar.length) {
									progressBar.find('span').html(0 + "%");
									progressBar.find('.fileuploader-progressbar .bar').width(0 + "%");
									item.html.find('.progress-bar2').fadeOut(400);
								}
								item.upload.status != 'cancelled' && item.html.find('.fileuploader-action-retry').length == 0 ? item.html.find('.column-actions').prepend(
									'<a class="fileuploader-action fileuploader-action-retry" title="Retry"><i></i></a>'
								) : null;
								showAlert("Error uploading file: "+data.ComponentFriendlyMessage,"error","OK",false);
								return;
							}
						}
						// if success
						if (data.isSuccess && data.files[0]) {
							item.name = data.files[0].name;
							item.html.find('.column-title > div:first-child').text(data.files[0].name).attr('title', data.files[0].name);
							
							if (item.editor && item.editor.isUploadPending) {
								delete item.editor.isUploadPending;
								
								this_component.saveEditedImage(item);
							}
						}
						// if warnings
						if (data.hasWarnings) {
							for (var warning in data.warnings) {
								alert(data.warnings);
							}
							
							item.html.removeClass('upload-successful').addClass('upload-failed');
							// go out from success function by calling onError function
							// in this case we have a animation there
							// you can also response in PHP with 404
							return this.onError ? this.onError(item) : null;
						}
						item.html.find('.fileuploader-action-remove').addClass('fileuploader-action-success');
						setTimeout(function() {
							item.html.find('.progress-bar2').fadeOut(400);
						}, 400);
					},
					onError: function(item) {
						var progressBar = item.html.find('.progress-bar2');
						if(progressBar.length) {
							progressBar.find('span').html(0 + "%");
							progressBar.find('.fileuploader-progressbar .bar').width(0 + "%");
							item.html.find('.progress-bar2').fadeOut(400);
						}
						item.upload.status != 'cancelled' && item.html.find('.fileuploader-action-retry').length == 0 ? item.html.find('.column-actions').prepend(
							'<a class="fileuploader-action fileuploader-action-retry" title="Retry"><i></i></a>'
						) : null;
					},
					onProgress: function(data, item) {
						var progressBar = item.html.find('.progress-bar2');
						if(progressBar.length > 0) {
							progressBar.show();
							progressBar.find('span').html(data.percentage + "%");
							progressBar.find('.fileuploader-progressbar .bar').width(data.percentage + "%");
						}
					},
					onComplete: function(result) {
						dx_has_uploads_waiting = false;
					},
				},
				onRemove: function(item) {
					dxRequestInternal(getComponentControllerPath(this_component),{
						f:"handleRemoveFile",
						file: item.name
					},function(data_obj) {},function(data_obj) {});
				},
				editor: {
					cropper: {
						showGrid: true,
					},
					onSave: function(dataURL, item) {
						this_component.saveEditedImage(item);
					}
				},
				captions: {
					feedback: 'Drag and drop profile picture here',
					feedback2: 'Drag and drop profile picture here',
					drop: 'Drag and drop profile picture here',
					or: 'or',
					button: 'Browse images',
				},
				enableApi: true
			});
		}
	}
	component_classes['system_profile_picture_uploader'] = system_profile_picture_uploader;
}