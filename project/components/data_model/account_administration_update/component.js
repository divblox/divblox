if (typeof(on_data_model_account_administration_update_ready) === "undefined") {
	function on_data_model_account_administration_update_ready(load_arguments) {
		// This is required for any component to be registered to the DOM as a divblox component
		this.dom_component_obj = new DivbloxDOMComponent(load_arguments);
		this.handleComponentError = function(ErrorMessage) {
			this.dom_component_obj.handleComponentError(this,ErrorMessage);
		}.bind(this);
		this.handleComponentSuccess = function() {
			this.dom_component_obj.handleComponentSuccess(this);
		}.bind(this);
		this.reset = function(inputs) {
			let this_component = this;
			if (!this_component.dom_component_obj.getReadyState()) {
				this_component.handleComponentSuccess(this,inputs);
			}
			if (typeof inputs !== "undefined") {
				this_component.setAccountId(inputs);
				this_component.loadAccount();
			}
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
		this.data_validation_array = [];
		this.custom_validation_array = [];
		this.required_validation_array = ['EmailAddress','Username'].concat(this.data_validation_array).concat(this.custom_validation_array);
		this.user_role_list = {};
		getComponentElementById(this,"btnSave").on("click", function() {
			let uid = getUidFromComponentElementId($(this).attr("id"),"btnSave");
			getRegisteredComponent(uid).saveAccount();
		});
		getComponentElementById(this,"btnDelete").on("click", function() {
			let uid = getUidFromComponentElementId($(this).attr("id"),"btnDelete");
			let this_component = getRegisteredComponent(uid);
			showAlert("Are you sure?","warning",["Cancel","Delete"],false,0,this_component.deleteAccount,this_component.doNothing);
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
                    "ProfilePicturePath":"#"+uid+"_ProfilePicturePath",
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
                    "AccessBlocked":"#"+uid+"_AccessBlocked",
                    "BlockedReason":"#"+uid+"_BlockedReason",
                    "UserRole":"#"+uid+"_UserRole",
                    };
				this_component.user_role_list = data_obj.UserRoleList;
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
            getComponentElementById(this_component,"ProfilePicturePath").val(getDataModelAttributeValue(AccountObj.ProfilePicturePath));
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
            getComponentElementById(this_component,"AccessBlocked").attr("checked",getDataModelAttributeValue(AccountObj.AccessBlocked));
            getComponentElementById(this_component,"BlockedReason").val(getDataModelAttributeValue(AccountObj.BlockedReason));
            
			getComponentElementById(this_component,"UserRole").html('<option value="">-Please Select-</option>');
            let object_keys_user_role_list = Object.keys(this_component.user_role_list);
            if (object_keys_user_role_list.length > 0) {
                this_component.user_role_list.forEach(function (UserRoleItem) {
                    let SelectedStr = "";
                    if (UserRoleItem['Id'] == AccountObj.UserRole) {
                        SelectedStr = "selected";
                    }
	                if (UserRoleItem['Id'] == "DATASET TOO LARGE") {
		                dxLog("Data set too large for UserRole. Consider using another option to link the object");
	                } else {
		                getComponentElementById(this_component,"UserRole").append('<option value="'+UserRoleItem['Id']+'" '+SelectedStr+'>'+UserRoleItem['Role']+'</option>');
	                }
                });
            } else {
	            dxLog("user_role_list is empty");
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
		this.deleteAccount = function() {
			let this_component = this;
			let uid = this_component.dom_component_obj.uid;
			dxRequestInternal(getComponentControllerPath(this),{f:"deleteObjectData",Id:this_component.dom_component_obj.arguments["account_id"]}, function(data_obj) {
				showAlert("Deleted!");
				this_component.loadAccount();
				pageEventTriggered("account_deleted");
				}, function (data_obj) {
				showAlert("Error deleting account: "+data_obj.Message,"error","OK",false);
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
