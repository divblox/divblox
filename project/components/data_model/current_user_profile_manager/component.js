if (typeof(on_data_model_current_user_profile_manager_ready) === "undefined") {
	function on_data_model_current_user_profile_manager_ready(load_arguments) {
		// This is required for any component to be registered to the DOM as a divblox component
		this.dom_component_obj = new DivbloxDOMComponent(load_arguments);
		this.handleComponentError = function(ErrorMessage) {
			this.dom_component_obj.handleComponentError(this,ErrorMessage);
		}.bind(this);
		this.handleComponentSuccess = function() {
			this.dom_component_obj.handleComponentSuccess(this);
		}.bind(this);
		this.reset = function(inputs) {
			this.loadAccount();
		}.bind(this);
		this.on_component_loaded = function() {
			let this_component = this;
			dxRequestInternal(getRootPath()+'project/assets/php/global_request_handler.php',{f:"getCurrentAccountId"},
				function(data_obj) {
					if (typeof data_obj.CurrentAccountId === "undefined") {
						loadPageComponent("login");
						return;
					} else if (data_obj.CurrentAccountId < 1) {
						loadPageComponent("login");
						return;
					} else {
						this_component.setAccountId(data_obj.CurrentAccountId);
						this_component.dom_component_obj.on_component_loaded(this_component);
					}
				},
				function(data_obj) {
					loadPageComponent("login");
				});
			
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
			let this_component = this;
			switch(event_name) {
				case 'ProfilePictureChanged':
					setTimeout(function() {
						getComponentElementById(this_component,"ProfilePictureRender").attr("src",current_user_profile_picture_path);
						getComponentElementById(this_component,"ProfilePictureModal").modal('hide');
					},1000);
					break;
				default:
					dxLog("Event triggered: "+event_name+": "+JSON.stringify(parameters_obj));
			}
			// Let's pass the event to all sub components
			this.dom_component_obj.propagateEventTriggered(event_name,parameters_obj);
		}.bind(this);
		// Sub component config start
		this.sub_components =
			{"0":{"component_load_path":"system/profile_picture_uploader","parent_element":"jY5DR","loading_arguments":{"url_parameters":{"component":"data_model/current_user_profile_manager"},
			"component_name":"system_profile_picture_uploader","component_load_name":"system/profile_picture_uploader","parent_element":"#main_page_jY5DR","parent_uid":"main_page","component_path":"http://localhost/divblox_local/project/components/system/profile_picture_uploader","dom_index":1,"uid":"system_profile_picture_uploader_1"}},
			"1":{"component_load_path":"data_model/account_additional_info_manager","parent_element":"DOCwu","loading_arguments":{}}};
		// Sub component config end
		// Custom functions and declarations to be added below
		this.data_validation_array = [];
		this.custom_validation_array = [];
		this.required_validation_array = ['EmailAddress','Username',].concat(this.data_validation_array).concat(this.custom_validation_array);
		getComponentElementById(this,"btnSave").on("click", function() {
			let uid = getUidFromComponentElementId($(this).attr("id"),"btnSave");
			getRegisteredComponent(uid).saveAccount();
		});
		getComponentElementById(this,"btnLogout").on("click", function() {
			let uid = getUidFromComponentElementId($(this).attr("id"),"btnLogout");
			logout();
		});
		this.setAccountId = function(account_id) {
			let this_component = this;
			this_component.dom_component_obj.arguments["account_id"] = account_id;
		}.bind(this);
		this.getAccountId = function() {
			let this_component = this;
			return this_component.dom_component_obj.arguments["account_id"];
		}.bind(this);
		this.loadAccount = function() {
			let this_component = this;
			let uid = this_component.dom_component_obj.uid;
			dxRequestInternal(getComponentControllerPath(this),{f:"getObjectData",Id:this_component.getAccountId()}, function(data_obj) {
				this_component.dom_component_obj.component_obj = data_obj.Object;
				this_component.dom_component_obj.element_mapping = {
					"FirstName":"#"+uid+"_FirstName",
                    "MiddleNames":"#"+uid+"_MiddleNames",
                    "LastName":"#"+uid+"_LastName",
                    "EmailAddress":"#"+uid+"_EmailAddress",
                    "Username":"#"+uid+"_Username",
                    "Password":"#"+uid+"_Password",
                    "MaidenName":"#"+uid+"_MaidenName",
                    "MainContactNumber":"#"+uid+"_MainContactNumber",
                    "Title":"#"+uid+"_Title",
                    "DateOfBirth":"#"+uid+"_DateOfBirth",
                    "PhysicalAddressLineOne":"#"+uid+"_PhysicalAddressLineOne",
                    "PhysicalAddressLineTwo":"#"+uid+"_PhysicalAddressLineTwo",
                    "PhysicalAddressPostalCode":"#"+uid+"_PhysicalAddressPostalCode",
                    "PhysicalAddressCountry":"#"+uid+"_PhysicalAddressCountry",
                    "PostalAddressLineOne":"#"+uid+"_PostalAddressLineOne",
                    "PostalAddressLineTwo":"#"+uid+"_PostalAddressLineTwo",
                    "PostalAddressPostalCode":"#"+uid+"_PostalAddressPostalCode",
                    "PostalAddressCountry":"#"+uid+"_PostalAddressCountry",
                    "IdentificationNumber":"#"+uid+"_IdentificationNumber",
                    "Nickname":"#"+uid+"_Nickname",
                    "Status":"#"+uid+"_Status",
                    "Gender":"#"+uid+"_Gender",
                    };
				
				this_component.setValues();
			}, function(data_obj) {
				this_component.handleComponentError(data_obj.Message);
			});
		}.bind(this);
		this.setValues = function() {
			let this_component = this;
			let uid = this_component.dom_component_obj.uid;
			let AccountObj = this_component.dom_component_obj.component_obj;
			getComponentElementById(this_component,"FirstName").val(getDataModelAttributeValue(AccountObj.FirstName));
            getComponentElementById(this_component,"MiddleNames").val(getDataModelAttributeValue(AccountObj.MiddleNames));
            getComponentElementById(this_component,"LastName").val(getDataModelAttributeValue(AccountObj.LastName));
            getComponentElementById(this_component,"EmailAddress").val(getDataModelAttributeValue(AccountObj.EmailAddress));
            getComponentElementById(this_component,"Username").val(getDataModelAttributeValue(AccountObj.Username));
            getComponentElementById(this_component,"Password").val(getDataModelAttributeValue(AccountObj.Password));
            getComponentElementById(this_component,"MaidenName").val(getDataModelAttributeValue(AccountObj.MaidenName));
            getComponentElementById(this_component,"MainContactNumber").val(getDataModelAttributeValue(AccountObj.MainContactNumber));
            getComponentElementById(this_component,"Title").val(getDataModelAttributeValue(AccountObj.Title));
            getComponentElementById(this_component,"DateOfBirth").val(getDataModelAttributeValue(AccountObj.DateOfBirth));
            getComponentElementById(this_component,"PhysicalAddressLineOne").val(getDataModelAttributeValue(AccountObj.PhysicalAddressLineOne));
            getComponentElementById(this_component,"PhysicalAddressLineTwo").val(getDataModelAttributeValue(AccountObj.PhysicalAddressLineTwo));
            getComponentElementById(this_component,"PhysicalAddressPostalCode").val(getDataModelAttributeValue(AccountObj.PhysicalAddressPostalCode));
            getComponentElementById(this_component,"PhysicalAddressCountry").val(getDataModelAttributeValue(AccountObj.PhysicalAddressCountry));
            getComponentElementById(this_component,"PostalAddressLineOne").val(getDataModelAttributeValue(AccountObj.PostalAddressLineOne));
            getComponentElementById(this_component,"PostalAddressLineTwo").val(getDataModelAttributeValue(AccountObj.PostalAddressLineTwo));
            getComponentElementById(this_component,"PostalAddressPostalCode").val(getDataModelAttributeValue(AccountObj.PostalAddressPostalCode));
            getComponentElementById(this_component,"PostalAddressCountry").val(getDataModelAttributeValue(AccountObj.PostalAddressCountry));
            getComponentElementById(this_component,"IdentificationNumber").val(getDataModelAttributeValue(AccountObj.IdentificationNumber));
            getComponentElementById(this_component,"Nickname").val(getDataModelAttributeValue(AccountObj.Nickname));
            getComponentElementById(this_component,"Status").val(getDataModelAttributeValue(AccountObj.Status));
            getComponentElementById(this_component,"Gender").val(getDataModelAttributeValue(AccountObj.Gender));
            let profile_picture_path = getDataModelAttributeValue(AccountObj.ProfilePicturePath);
            if (profile_picture_path.length > 1) {
	            getComponentElementById(this_component,"ProfilePictureRender").attr("src",getServerRootPath()+profile_picture_path);
            }
		}.bind(this);
		this.updateValues = function() {
			let this_component = this;
			let keys = Object.keys(this_component.dom_component_obj.element_mapping);
			keys.forEach(function(item) {
				if ($(this_component.dom_component_obj.element_mapping[item]).attr("type") == "checkbox") {
					this_component.dom_component_obj.component_obj[item] = $(this_component.dom_component_obj.element_mapping[item]).is(':checked') ? 1: 0;
				} else {
					this_component.dom_component_obj.component_obj[item] = $(this_component.dom_component_obj.element_mapping[item]).val();
				}
			});
			return this_component.dom_component_obj.component_obj;
		}.bind(this);
		this.saveAccount = function() {
			let this_component = this;
			let current_component_obj = this_component.updateValues();
			this_component.resetValidation();
			if (!this_component.validateAccount()) {
				return;
			}
			dxRequestInternal(getComponentControllerPath(this),{f:"saveObjectData",ObjectData:JSON.stringify(current_component_obj),Id:this_component.dom_component_obj.arguments["account_id"]}, function(data_obj) {
				showAlert("Updated!");
				pageEventTriggered("account_updated");
				this_component.resetValidation();
			}, function(data_obj) {
				showAlert("Error saving account: "+data_obj.Message,"error","OK",false);
			});
		}.bind(this);
		this.validateAccount = function() {
			let this_component = this;
			let validation_succeeded = true;
			this_component.required_validation_array.forEach(function(item) {
				if (getComponentElementById(this_component,item).attr("type") !== "checkbox") {
					if (getComponentElementById(this_component,item).val() == "") {
						validation_succeeded = false;
						toggleValidationState(this_component,item,"",false);
					} else {
						toggleValidationState(this_component,item,"",true);
					}
				}
			});
			this_component.data_validation_array.forEach(function(item) {
				if (!getComponentElementById(this_component,item).hasClass("is-invalid")) {
					if (getComponentElementById(this_component,item).hasClass("validate-number")) {
						if (isNaN(getComponentElementById(this_component,item).val())) {
							validation_succeeded = false;
							toggleValidationState(this_component,item,"",false);
						} else {
							toggleValidationState(this_component,item,"",true);
						}
					}
				}
			});
			this_component.custom_validation_array.forEach(function(item) {
				if (checkValidationState(this_component,item)) {
					validation_succeeded &= this_component.doCustomValidation(item);
				}
			});
			return validation_succeeded;
		}.bind(this);
		this.doCustomValidation = function(attribute) {
			let this_component = this;
			switch (attribute) {
				
				default:
					break;
			}
		}.bind(this);
		this.resetValidation = function() {
			let this_component = this;
			this_component.required_validation_array.forEach(function(item) {
				toggleValidationState(this_component,item,"",true,true);
			});
		}.bind(this);
		this.doNothing = function() {
			//Just a helper function to reference on cancel of confirmation
		};
	}
}
