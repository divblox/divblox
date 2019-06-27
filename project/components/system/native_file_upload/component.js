if (typeof component_classes['system_native_file_upload'] === "undefined") {
	class system_native_file_upload extends DivbloxDomBaseComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
			this.sub_component_definitions = [];
			// Sub component config end
			this.file_upload_array = [];
		}
		loadPrerequisites(success_callback,fail_callback) {
			dxGetScript(getRootPath()+"project/assets/js/jquery_fileuploader/jquery.fileuploader.min.js", function( data, textStatus, jqxhr ) {
				this.initFileUploader();
				success_callback();
			}.bind(this));
		}
		initFileUploader() {
			let uid = this.uid;
			let this_component = this;
			$('#'+uid+'_file_uploader').fileuploader({
				changeInput: '<div class="fileuploader-input">' +
					'<div class="fileuploader-input-inner">' +
					'<div class="fileuploader-main-icon"></div>' +
					'<h3 class="fileuploader-input-caption"><span>${captions.feedback}</span></h3>' +
					'<p>${captions.or}</p>' +
					'<div class="fileuploader-input-button"><span>${captions.button}</span></div>' +
					'</div>' +
					'</div>',
				theme: 'dragdrop',
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
							pageEventTriggered("FileUploaded",item);
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
					onComplete: function() {
						dx_has_uploads_waiting = false;
					},
				},
				onRemove: function(item) {
					dxRequestInternal(getComponentControllerPath(this_component),{
						f:"handleRemoveFile",
						file: item.name
					},function(data_obj) {},function(data_obj) {});
				},
				captions: {
					feedback: 'Drag and drop files here',
					feedback2: 'Drag and drop files here',
					drop: 'Drag and drop files here',
					or: 'or',
					button: 'Browse files',
				},
				enableApi: true
			});
		}
	}
	component_classes['system_native_file_upload'] = system_native_file_upload_blank;
}