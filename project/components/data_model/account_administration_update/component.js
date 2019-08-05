if (typeof component_classes['data_model_account_administration_update'] === "undefined") {
	class data_model_account_administration_update extends DivbloxDomBaseComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
			this.sub_component_definitions = [];
			// Sub component config end
			this.data_validation_array = [];
			this.custom_validation_array = [];
			this.required_validation_array = ['FirstName','EmailAddress','Username',].concat(this.data_validation_array).concat(this.custom_validation_array);
			this.user_role_list = {};
        
		}
		reset(inputs) {
			this.setLoadingState();
			if (typeof inputs !== "undefined") {
				this.setAccountId(inputs);
				this.loadAccount();
			}
			super.reset(inputs);
		}
		registerDomEvents() {
			getComponentElementById(this,"btnSave").on("click", function() {
				this.saveAccount();
			}.bind(this));
			getComponentElementById(this,"btnDelete").on("click", function() {
				showAlert("Are you sure?","warning",["Cancel","Delete"],false,0,this.deleteAccount.bind(this),this.doNothing);
			}.bind(this));
		}
		setAccountId(account_id) {
			this.arguments["account_id"] = account_id;
		}
		getAccountId() {
			return this.arguments["account_id"];
		}
		loadAccount() {
			dxRequestInternal(getComponentControllerPath(this),{f:"getObjectData",Id:this.getAccountId()}, function(data_obj) {
				this.removeLoadingState();
				this.component_obj = data_obj.Object;
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
			let AccountObj = this.component_obj;
			getComponentElementById(this,"FirstName").val(getDataModelAttributeValue(AccountObj.FirstName));
            getComponentElementById(this,"MiddleNames").val(getDataModelAttributeValue(AccountObj.MiddleNames));
            getComponentElementById(this,"LastName").val(getDataModelAttributeValue(AccountObj.LastName));
            getComponentElementById(this,"EmailAddress").val(getDataModelAttributeValue(AccountObj.EmailAddress));
            getComponentElementById(this,"Username").val(getDataModelAttributeValue(AccountObj.Username));
            getComponentElementById(this,"Password").val(getDataModelAttributeValue(AccountObj.Password));
            getComponentElementById(this,"MaidenName").val(getDataModelAttributeValue(AccountObj.MaidenName));
            getComponentElementById(this,"ProfilePicturePath").val(getDataModelAttributeValue(AccountObj.ProfilePicturePath));
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
            getComponentElementById(this,"AccessBlocked").attr("checked",getDataModelAttributeValue(AccountObj.AccessBlocked));
            getComponentElementById(this,"BlockedReason").val(getDataModelAttributeValue(AccountObj.BlockedReason));
            
			getComponentElementById(this,"UserRole").html('<option value="">-Please Select-</option>');
            let object_keys_user_role_list = Object.keys(this.user_role_list);
            if (object_keys_user_role_list.length > 0) {
                this.user_role_list.forEach(function (UserRoleItem) {
                    let SelectedStr = "";
                    if (UserRoleItem['Id'] == AccountObj.UserRole) {
                        SelectedStr = "selected";
                    }
                    if (this.user_role_list[0]['Id'] == "DATASET TOO LARGE") {
                        dxLog("Data set too large for UserRole. Consider using another option to link the object");
                    } else {
                        getComponentElementById(this,"UserRole").append('<option value="'+UserRoleItem['Id']+'" '+SelectedStr+'>'+UserRoleItem['Role']+'</option>');
                    }
                }.bind(this));
            } else {
                dxLog("UserRole list is empty");
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
					ObjectData:JSON.stringify(current_component_obj),
					Id:this.arguments["account_id"]}, function(data_obj) {
					showAlert("Updated!");
					pageEventTriggered("account_updated");
					this.resetValidation();
				}.bind(this), function(data_obj) {
					showAlert("Error saving account: "+data_obj.Message,"error","OK",false);
				}.bind(this));
		}
		deleteAccount() {
			dxRequestInternal(getComponentControllerPath(this),{f:"deleteObjectData",Id:this.arguments["account_id"]}, function(data_obj) {
				showAlert("Deleted!");
				this.loadAccount();
				pageEventTriggered("account_deleted");
			}.bind(this), function (data_obj) {
				showAlert("Error deleting account: "+data_obj.Message,"error","OK",false);
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
		doNothing() {
			// Just a helper function to reference on cancel of confirmation
		}
	}
	component_classes['data_model_account_administration_update'] = data_model_account_administration_update;
}