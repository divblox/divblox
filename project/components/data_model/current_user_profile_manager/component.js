if (typeof component_classes['data_model_current_user_profile_manager'] === "undefined") {
	class data_model_current_user_profile_manager extends DivbloxDomBaseComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
			this.sub_component_definitions = [
				{"component_load_path":"system/profile_picture_uploader","parent_element":"jY5DR","loading_arguments":{"uid":"system_profile_picture_uploader_1"}},
				{"component_load_path":"data_model/account_additional_info_manager","parent_element":"DOCwu","loading_arguments":{}}];
			// Sub component config end
			this.data_validation_array = [];
			this.custom_validation_array = [];
			this.required_validation_array = ['EmailAddress','Username',].concat(this.data_validation_array).concat(this.custom_validation_array);
		}
		reset(inputs,propagate) {
			getCurrentUserAttribute("Id",function(attribute) {
				if (attribute == null) {
					loadPageComponent("login");
				} else if (attribute < 1) {
					loadPageComponent("login");
				} else {
					this.setAccountId(attribute);
					this.loadAccount();
				}
			}.bind(this));
			super.reset(inputs,propagate);
		}
		eventTriggered(event_name,parameters_obj) {
			// Handle specific events here. This is useful if the component needs to update because one of its
			// sub-components did something
			switch(event_name) {
				default:
					dxLog("Event triggered: "+event_name+": "+JSON.stringify(parameters_obj));
			}
			// Let's pass the event to all sub components
			this.propagateEventTriggered(event_name,parameters_obj);
		}
		registerDomEvents() {
			getComponentElementById(this,"btnSave").on("click", function() {
				this.saveAccount();
			}.bind(this));
			getComponentElementById(this,"btnLogout").on("click", function() {
				logout();
			});
		}
		setAccountId(account_id) {
			this.arguments["account_id"] = account_id;
		}
		getAccountId() {
			return this.arguments["account_id"];
		}
		loadAccount() {
			dxRequestInternal(getComponentControllerPath(this),{f:"getObjectData",Id:this.getAccountId()}, function(data_obj) {
				this.component_obj = data_obj.Object;
				this.element_mapping = {
					"FirstName":"#"+this.uid+"_FirstName",
					"MiddleNames":"#"+this.uid+"_MiddleNames",
					"LastName":"#"+this.uid+"_LastName",
					"EmailAddress":"#"+this.uid+"_EmailAddress",
					"Username":"#"+this.uid+"_Username",
					"Password":"#"+this.uid+"_Password",
					"MaidenName":"#"+this.uid+"_MaidenName",
					"MainContactNumber":"#"+this.uid+"_MainContactNumber",
					"Title":"#"+this.uid+"_Title",
					"DateOfBirth":"#"+this.uid+"_DateOfBirth",
					"PhysicalAddressLineOne":"#"+this.uid+"_PhysicalAddressLineOne",
					"PhysicalAddressLineTwo":"#"+this.uid+"_PhysicalAddressLineTwo",
					"PhysicalAddressPostalCode":"#"+this.uid+"_PhysicalAddressPostalCode",
					"PhysicalAddressCountry":"#"+this.uid+"_PhysicalAddressCountry",
					"PostalAddressLineOne":"#"+this.uid+"_PostalAddressLineOne",
					"PostalAddressLineTwo":"#"+this.uid+"_PostalAddressLineTwo",
					"PostalAddressPostalCode":"#"+this.uid+"_PostalAddressPostalCode",
					"PostalAddressCountry":"#"+this.uid+"_PostalAddressCountry",
					"IdentificationNumber":"#"+this.uid+"_IdentificationNumber",
					"Nickname":"#"+this.uid+"_Nickname",
					"Status":"#"+this.uid+"_Status",
					"Gender":"#"+this.uid+"_Gender",
				};
				this.setValues();
			}.bind(this), function(data_obj) {
				this.handleComponentError(data_obj.Message);
			}.bind(this));
		}
		setValues() {
			let AccountObj = this.component_obj;
			getComponentElementById(this,"FirstName").val(getDataModelAttributeValue(AccountObj.FirstName));
			getComponentElementById(this,"MiddleNames").val(getDataModelAttributeValue(AccountObj.MiddleNames));
			getComponentElementById(this,"LastName").val(getDataModelAttributeValue(AccountObj.LastName));
			getComponentElementById(this,"EmailAddress").val(getDataModelAttributeValue(AccountObj.EmailAddress));
			getComponentElementById(this,"Username").val(getDataModelAttributeValue(AccountObj.Username));
			getComponentElementById(this,"Password").val(getDataModelAttributeValue(AccountObj.Password));
			getComponentElementById(this,"MaidenName").val(getDataModelAttributeValue(AccountObj.MaidenName));
			getComponentElementById(this,"MainContactNumber").val(getDataModelAttributeValue(AccountObj.MainContactNumber));
			getComponentElementById(this,"Title").val(getDataModelAttributeValue(AccountObj.Title));
			getComponentElementById(this,"DateOfBirth").val(getDataModelAttributeValue(AccountObj.DateOfBirth));
			getComponentElementById(this,"PhysicalAddressLineOne").val(getDataModelAttributeValue(AccountObj.PhysicalAddressLineOne));
			getComponentElementById(this,"PhysicalAddressLineTwo").val(getDataModelAttributeValue(AccountObj.PhysicalAddressLineTwo));
			getComponentElementById(this,"PhysicalAddressPostalCode").val(getDataModelAttributeValue(AccountObj.PhysicalAddressPostalCode));
			getComponentElementById(this,"PhysicalAddressCountry").val(getDataModelAttributeValue(AccountObj.PhysicalAddressCountry));
			getComponentElementById(this,"PostalAddressLineOne").val(getDataModelAttributeValue(AccountObj.PostalAddressLineOne));
			getComponentElementById(this,"PostalAddressLineTwo").val(getDataModelAttributeValue(AccountObj.PostalAddressLineTwo));
			getComponentElementById(this,"PostalAddressPostalCode").val(getDataModelAttributeValue(AccountObj.PostalAddressPostalCode));
			getComponentElementById(this,"PostalAddressCountry").val(getDataModelAttributeValue(AccountObj.PostalAddressCountry));
			getComponentElementById(this,"IdentificationNumber").val(getDataModelAttributeValue(AccountObj.IdentificationNumber));
			getComponentElementById(this,"Nickname").val(getDataModelAttributeValue(AccountObj.Nickname));
			getComponentElementById(this,"Status").val(getDataModelAttributeValue(AccountObj.Status));
			getComponentElementById(this,"Gender").val(getDataModelAttributeValue(AccountObj.Gender));
			let profile_picture_path = getDataModelAttributeValue(AccountObj.ProfilePicturePath);
			if (profile_picture_path.length > 1) {
				getComponentElementById(this,"ProfilePictureRender").attr("src",getServerRootPath()+profile_picture_path);
			}
		}
		updateValues() {
			let keys = Object.keys(this.element_mapping);
			keys.forEach(function(item) {
				if ($(this.element_mapping[item]).attr("type") == "checkbox") {
					this.component_obj[item] = $(this.element_mapping[item]).is(':checked') ? 1: 0;
				} else {
					this.component_obj[item] = $(this.element_mapping[item]).val();
				}
			}.bind(this));
			return this.component_obj;
		}
		saveAccount() {
			let current_component_obj = this.updateValues();
			this.resetValidation();
			if (!this.validateAccount()) {
				return;
			}
			dxRequestInternal(getComponentControllerPath(this),{f:"saveObjectData",ObjectData:JSON.stringify(current_component_obj),Id:this.arguments["account_id"]}, function(data_obj) {
				showAlert("Updated!");
				pageEventTriggered("account_updated");
				this.resetValidation();
			}.bind(this), function(data_obj) {
				showAlert("Error saving account: "+data_obj.Message,"error","OK",false);
			});
		}
		validateAccount() {
			let validation_succeeded = true;
			this.required_validation_array.forEach(function(item) {
				if (getComponentElementById(this,item).attr("type") !== "checkbox") {
					if (getComponentElementById(this,item).val() == "") {
						validation_succeeded = false;
						toggleValidationState(this,item,"",false);
					} else {
						toggleValidationState(this,item,"",true);
					}
				}
			}.bind(this));
			this.data_validation_array.forEach(function(item) {
				if (!getComponentElementById(this,item).hasClass("is-invalid")) {
					if (getComponentElementById(this,item).hasClass("validate-number")) {
						if (isNaN(getComponentElementById(this,item).val())) {
							validation_succeeded = false;
							toggleValidationState(this,item,"",false);
						} else {
							toggleValidationState(this,item,"",true);
						}
					}
				}
			}.bind(this));
			this.custom_validation_array.forEach(function(item) {
				if (checkValidationState(this,item)) {
					validation_succeeded &= this.doCustomValidation(item);
				}
			}.bind(this));
			return validation_succeeded;
		}
		doCustomValidation(attribute) {
			return true;
		}
		resetValidation() {
			this.required_validation_array.forEach(function(item) {
				toggleValidationState(this,item,"",true,true);
			}.bind(this));
		}
		doNothing() {
			//Just a helper function to reference on cancel of confirmation
		}
		initCustomFunctions() {
			super.initCustomFunctions();
			getComponentElementById(this,'ProfilePictureModal').on('hidden.bs.modal', function (e) {
				setTimeout(function() {
					loadCurrentUserProfilePicture(function(path) {
						getComponentElementById(this,"ProfilePictureRender").attr("src",path);
					}.bind(this));
				}.bind(this),1000);
			}.bind(this))
		}
	}
	component_classes['data_model_current_user_profile_manager'] = data_model_current_user_profile_manager;
}