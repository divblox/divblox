if (typeof component_classes['data_model_account_administration_create'] === "undefined") {
	class data_model_account_administration_create extends DivbloxDomBaseComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
			this.sub_component_definitions = [];
			// Sub component config end
			this.data_validation_array = [];
			this.custom_validation_array = [];
			this.required_validation_array = [].concat(this.data_validation_array).concat(this.custom_validation_array);
			this.user_role_list = {};
        
		}
		reset(inputs) {
			this.loadAccount();
			this.resetSubComponents();
		}
		registerDomEvents() {
			getComponentElementById(this,"btnSave").on("click", function() {
				this.saveAccount();
			}.bind(this));
		}
		loadAccount() {
			dxRequestInternal(getComponentControllerPath(this),{f:"getObjectData"}, function(data_obj) {
				this.component_obj = {
					"FirstName":"",
                    "MiddleNames":"",
                    "LastName":"",
                    "EmailAddress":"",
                    "Username":"",
                    "Password":"",
                    "MaidenName":"",
                    "ProfilePicturePath":"",
                    "MainContactNumber":"",
                    "Title":"",
                    "DateOfBirth":"",
                    "PhysicalAddressLineOne":"",
                    "PhysicalAddressLineTwo":"",
                    "PhysicalAddressPostalCode":"",
                    "PhysicalAddressCountry":"",
                    "PostalAddressLineOne":"",
                    "PostalAddressLineTwo":"",
                    "PostalAddressPostalCode":"",
                    "PostalAddressCountry":"",
                    "IdentificationNumber":"",
                    "Nickname":"",
                    "Status":"",
                    "Gender":"",
                    "AccessBlocked":"",
                    "BlockedReason":"",
                    "UserRole":"",
                    };
				this.element_mapping = {
					"FirstName":"#"+this.uid+"_FirstName",
                    "MiddleNames":"#"+this.uid+"_MiddleNames",
                    "LastName":"#"+this.uid+"_LastName",
                    "EmailAddress":"#"+this.uid+"_EmailAddress",
                    "Username":"#"+this.uid+"_Username",
                    "Password":"#"+this.uid+"_Password",
                    "MaidenName":"#"+this.uid+"_MaidenName",
                    "ProfilePicturePath":"#"+this.uid+"_ProfilePicturePath",
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
                    "AccessBlocked":"#"+this.uid+"_AccessBlocked",
                    "BlockedReason":"#"+this.uid+"_BlockedReason",
                    "UserRole":"#"+this.uid+"_UserRole",
                    };
				this.user_role_list = data_obj.UserRoleList;
                
				this.setValues();
			}.bind(this), function(data_obj) {
				this.handleComponentError(data_obj.Message);
			}.bind(this));
		}
		setValues() {
			getComponentElementById(this,"FirstName").val("");
            getComponentElementById(this,"MiddleNames").val("");
            getComponentElementById(this,"LastName").val("");
            getComponentElementById(this,"EmailAddress").val("");
            getComponentElementById(this,"Username").val("");
            getComponentElementById(this,"Password").val("");
            getComponentElementById(this,"MaidenName").val("");
            getComponentElementById(this,"ProfilePicturePath").val("");
            getComponentElementById(this,"MainContactNumber").val("");
            getComponentElementById(this,"Title").val("");
            getComponentElementById(this,"DateOfBirth").val("");
            getComponentElementById(this,"PhysicalAddressLineOne").val("");
            getComponentElementById(this,"PhysicalAddressLineTwo").val("");
            getComponentElementById(this,"PhysicalAddressPostalCode").val("");
            getComponentElementById(this,"PhysicalAddressCountry").val("");
            getComponentElementById(this,"PostalAddressLineOne").val("");
            getComponentElementById(this,"PostalAddressLineTwo").val("");
            getComponentElementById(this,"PostalAddressPostalCode").val("");
            getComponentElementById(this,"PostalAddressCountry").val("");
            getComponentElementById(this,"IdentificationNumber").val("");
            getComponentElementById(this,"Nickname").val("");
            getComponentElementById(this,"Status").val("");
            getComponentElementById(this,"Gender").val("");
            getComponentElementById(this,"AccessBlocked").attr("checked",true);
            getComponentElementById(this,"BlockedReason").val("");
            
			getComponentElementById(this,"UserRole").html('<option value="">-Please Select-</option>');
            let object_keys_user_role_list = Object.keys(this.user_role_list);
            if (object_keys_user_role_list.length > 0) {
                this.user_role_list.forEach(function (UserRoleItem) {
                    if (UserRoleItem['Id'] == "DATASET TOO LARGE") {
                        dxLog("Data set too large for UserRole. Consider using another option to link the object");
                    } else {
                        getComponentElementById(this,"UserRole").append('<option value="'+UserRoleItem['Id']+'">'+UserRoleItem['Role']+'</option>');
                    }
                }.bind(this));
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
			dxRequestInternal(
				getComponentControllerPath(this),
				{f:"saveObjectData",
					ObjectData:JSON.stringify(current_component_obj)},
				function(data_obj) {
					showAlert("Created!");
					pageEventTriggered("account_created",{"account_id":data_obj.Id});
					this.loadAccount();
					this.resetValidation();
				}.bind(this),
				function(data_obj) {
					showAlert("Error saving account: "+data_obj.Message,"error","OK",false);
				}.bind(this));
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
			switch (attribute) {
				
				default:
					break;
			}
		}
		resetValidation() {
			this.required_validation_array.forEach(function(item) {
				toggleValidationState(this,item,"",true,true);
			}.bind(this));
		}
	}
	component_classes['data_model_account_administration_create'] = data_model_account_administration_create;
}