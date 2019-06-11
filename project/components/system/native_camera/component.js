if (typeof(on_system_native_camera_ready) === "undefined") {
	function on_system_native_camera_ready(load_arguments) {
		// This is required for any component to be registered to the DOM as a divblox component
		this.dom_component_obj = new DivbloxDOMComponent(load_arguments,true,true);
		this.handleComponentError = function(ErrorMessage) {
			this.dom_component_obj.handleComponentError(this,ErrorMessage);
		}.bind(this);
		this.handleComponentSuccess = function() {
			this.dom_component_obj.handleComponentSuccess(this);
		}.bind(this);
		this.reset = function(inputs) {
			this.initCameraOptions();
		}.bind(this);
		this.on_component_loaded = function() {
			this.dom_component_obj.on_component_loaded(this);
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
		// More info: https://cordova.apache.org/docs/en/latest/reference/cordova-plugin-camera/index.html
		this.upload_button_element_id = -1;
		this.last_uploaded_picture_path = '';
		this.display_picture_after_upload = true;
		this.camera_options = {quality:80};
	    this.cameraSuccess = function(imageData) {
		    this.getFileEntry(imageData);
	    }.bind(this);
		this.cameraError = function(message) {
			// JGL: You can uncomment the line above if you need to display a message to the user when something
			// fails natively
			//showAlert("Error processing picture: "+message,"error","OK",false);
			removeTriggerElementFromLoadingElementArray(this.upload_button_element_id);
		}.bind(this);
		this.updateCameraOption = function(option_to_modify,value) {
			this.camera_options[option_to_modify] = value;
		}.bind(this);
		this.initCameraOptions = function() {
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
		}.bind(this);
		this.getFileEntry = function(fileUri) {
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
		}.bind(this);
		this.updateUploadedPicture = function(img_url) {
			if (this.display_picture_after_upload) {
				getComponentElementById(this,'native_image').attr('src',img_url).show();
			} else {
				getComponentElementById(this,'native_image').hide();
			}
		}.bind(this);
		this.setDisplayPictureAfterUpload = function(display_picture) {
			if (typeof display_picture === "undefined") {
				display_picture = true;
			}
			this.display_picture_after_upload = display_picture;
		}.bind(this);
        // 2ATYF_button Related functionality
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        getComponentElementById(this,"2ATYF_btn").on("click", function() {
        	if (!isNative()) {
        		showAlert("Only allowed when native","error","OK",false);
	        }
            // Get the current component uid
            let uid = getUidFromComponentElementId($(this).attr("id"),"2ATYF_btn");
            // Get the current component from its uid in order to access its functions
        	let this_component = getRegisteredComponent(uid);
	        navigator.camera.getPicture(this_component.cameraSuccess, this_component.cameraError, this_component.camera_options);
	        this_component.upload_button_element_id = addTriggerElementToLoadingElementArray($(this),"Processing");
        });
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        
	}
}
