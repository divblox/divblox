if (typeof(on_system_default_image_upload_ready) === "undefined") {
	function on_system_default_image_upload_ready(load_arguments) {
		// This is required for any component to be registered to the DOM as a divblox component
		this.dom_component_obj = new DivbloxDOMComponent(load_arguments,false);
		this.handleComponentError = function(ErrorMessage) {
			this.dom_component_obj.handleComponentError(this,ErrorMessage);
		}.bind(this);
		this.handleComponentSuccess = function() {
			this.dom_component_obj.handleComponentSuccess(this);
		}.bind(this);
		this.reset = function(inputs) {
			dxLog("Reset for default_image_upload not implemented");
		}.bind(this);
		this.on_component_loaded = function() {
			this.dom_component_obj.on_component_loaded(this);
			let this_component = this;
			let uid = this_component.getUid();
			dxGetScript(getRootPath()+"project/assets/js/jquery_fileuploader/jquery.fileuploader.min.js", function( data, textStatus, jqxhr ) {
				this_component.initFileUploader();
			},function() {},false);
		}.bind(this);
		this.subComponentLoadedCallBack = function(component) {
			// Implement additional required functionality for sub components after load here
			// dxLog("Sub component loaded: "+JSON.stringify(component));
		}.bind(this);
		this.getSubComponents = function() {
			return this.dom_component_obj.getSubComponents(this);
		}.bind(this);
		this.getUid = function() {
			return this.dom_component_obj.getUid();
		}.bind(this);
		// Component specific code below
		// Empty array means ANY user role has access. NB! This is merely for UX purposes.
		// Do not rely on this as a security measure. User role security MUST be managed on the server's side
		this.allowedAccessArray = [];
		this.eventTriggered = function(event_name,parameters_obj) {
			// Handle specific events here. This is useful if the component needs to update because one of its
			// sub-components did something
			switch(event_name) {
				case '[event_name]':
				default:
					dxLog("Event triggered: "+event_name+": "+JSON.stringify(parameters_obj));
			}
			// Let's pass the event to all sub components
			this.dom_component_obj.propagateEventTriggered(event_name,parameters_obj);
		}.bind(this);
		// Sub component config start
		this.sub_components = {};
		// Sub component config end
		// Custom functions and declarations to be added below
		this.file_upload_array = [];
		this.saveEditedImage = function(item) {
			let uid = this.getUid();
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
						pageEventTriggered("ImageEdited",data_obj);
					}, null, true);
				},function(data_obj) {});
			}
		}.bind(this);
		this.resetFileUploader = function() {
			let uid = this.getUid();
			let file_uploader_instance = $.fileuploader.getInstance('#'+uid+'_file_uploader');
			file_uploader_instance.reset();
		}.bind(this);
		this.initFileUploader = function() {
			let uid = this.getUid();
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
					data: {f:"handleFilePost"},
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
							} else {
								pageEventTriggered("ImageUploaded",item);
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
					feedback: 'Drag and drop images here',
					feedback2: 'Drag and drop images here',
					drop: 'Drag and drop images here',
					or: 'or',
					button: 'Browse images',
				},
				enableApi: true
			});
		}.bind(this);
	}
}