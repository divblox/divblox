if (typeof component_classes['system_native_camera'] === "undefined") {
	class system_native_camera extends DivbloxDomBaseComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
			this.sub_component_definitions = [];
			// Sub component config end
			this.upload_button_element_id = -1;
			this.last_uploaded_picture_path = '';
			this.display_picture_after_upload = true;
			this.camera_options = {quality:80};
		}
		reset(inputs) {
			this.initCameraOptions();
			super.reset(inputs);
		}
		cameraSuccess(imageData) {
			this.getFileEntry(imageData);
		}
		cameraError(message) {
			// JGL: You can uncomment the line above if you need to display a message to the user when something
			// fails natively
			//showAlert("Error processing picture: "+message,"error","OK",false);
			removeTriggerElementFromLoadingElementArray(this.upload_button_element_id);
		}
		updateCameraOption(option_to_modify,value) {
			this.camera_options[option_to_modify] = value;
		}
		initCameraOptions() {
			if (typeof Camera !== "undefined") {
				this.camera_options = {quality:80,
					destinationType:Camera.DestinationType.FILE_URI,
					sourceType:Camera.PictureSourceType.CAMERA,
					allowEdit:false,// AllowEdit on android is unpredictable. Rather use an additional native cordova plugin
					// to edit images
					encodingType: Camera.EncodingType.JPEG,
					/*targetWidth:null,
					targetHeight:null,*/
					mediaType:Camera.MediaType.ALLMEDIA,
					correctOrientation:true,
					saveToPhotoAlbum:false,
					cameraDirection:Camera.Direction.BACK};
			}
		}
		getFileEntry(fileUri) {
			let this_component = this;
			window.resolveLocalFileSystemURL(fileUri, function success(fileEntry) {
				fileEntry.file(function (file) {
					let reader = new FileReader();
					reader.onloadend = function() {
						// Create a blob based on the FileReader "result", which we asked to be retrieved as an ArrayBuffer
						let blob = new Blob([new Uint8Array(this.result)], { type: "image/jpeg" });
						let form_data = new FormData();
						form_data.append("f","handleFilePost");
						form_data.append("AuthenticationToken",authentication_token);
						form_data.append("is_native","1"); //JGL: IMPORTANT! If we don't pass this, the authentication
						// token will eventually expire and we will be logged out
						form_data.append("file",blob,"image.jpg");
						$.ajax({
							url: getComponentControllerPath(this_component),
							type: "POST",
							data:  form_data,
							contentType: false,
							cache: false,
							processData:false,
							success: function(result) {
								let result_obj = JSON.parse(result);
								if (typeof result_obj.Result !== "undefined") {
									if (result_obj.Result === "Success") {
										if (typeof result_obj.ServerPath !== "undefined") {
											this_component.last_uploaded_picture_path = result_obj.ServerPath;
											this_component.updateUploadedPicture(result_obj.ServerPath);
										}
									} else {
										showAlert("Error processing picture: "+result_obj.Message,"error","OK",false);
									}
								} else {
									showAlert("Error processing picture: Unknown error","error","OK",false);
								}
								removeTriggerElementFromLoadingElementArray(this_component.upload_button_element_id);
							},
							error: function(jqXHR, textStatus, errorThrown) {
								showAlert("Error processing picture: "+errorThrown,"error","OK",false);
								removeTriggerElementFromLoadingElementArray(this_component.upload_button_element_id);
							}
						});
					};
					// Read the file as an ArrayBuffer
					reader.readAsArrayBuffer(file);
				}, function (err) { console.error('error getting fileentry file!' + err); });
			}, function () {
				// If don't get the FileEntry (which may happen when testing
				// on some emulators), copy to a new FileEntry.
				showAlert("Error processing picture: Cannot get FileEntry","error","OK",false);
				removeTriggerElementFromLoadingElementArray(this_component.upload_button_element_id);
			});
		}
		updateUploadedPicture(img_url) {
			if (this.display_picture_after_upload) {
				getComponentElementById(this,'native_image').attr('src',img_url).show();
			} else {
				getComponentElementById(this,'native_image').hide();
			}
		}
		setDisplayPictureAfterUpload(display_picture) {
			if (typeof display_picture === "undefined") {
				display_picture = true;
			}
			this.display_picture_after_upload = display_picture;
		}
		registerDomEvents() {
			// 2ATYF_button Related functionality
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			getComponentElementById(this,"2ATYF_btn").on("click", function() {
				if (!isNative()) {
					showAlert("Only allowed when native","error","OK",false);
				}
				navigator.camera.getPicture(this.cameraSuccess, this.cameraError, this.camera_options);
				this.upload_button_element_id = addTriggerElementToLoadingElementArray(this.uid+"_2ATYF_btn","Processing");
			}.bind(this));
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}
	}
	component_classes['system_native_camera'] = system_native_camera;
}