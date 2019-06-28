if (typeof component_classes['system_account_registration_this_too'] === "undefined") {
	class system_account_registration extends DivbloxDomBaseComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
			this.sub_component_definitions = [];
			// Sub component config end
			this.data_validation_array = [];
			this.custom_validation_array = ['EmailAddress'];
			this.required_validation_array = ['FirstName','LastName','Password'].concat(this.data_validation_array).concat(this.custom_validation_array);
		}
		reset(inputs) {
			this.loadAccount();
			super.reset(inputs);
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
					"LastName":"",
					"EmailAddress":"",
					"Password":"",
				};
				this.element_mapping = {
					"FirstName":"#"+this.uid+"_FirstName",
					"LastName":"#"+this.uid+"_LastName",
					"EmailAddress":"#"+this.uid+"_EmailAddress",
					"Password":"#"+this.uid+"_Password",
				};
				
				this.setValues();
			}.bind(this), function(data_obj) {
				this.handleComponentError(data_obj.Message);
			}.bind(this));
		}
		setValues() {
			getComponentElementById(this,"FirstName").val("");
			getComponentElementById(this,"LastName").val("");
			getComponentElementById(this,"EmailAddress").val("");
			getComponentElementById(this,"Password").val("");
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
			dxRequestInternal(getComponentControllerPath(this),{f:"saveObjectData",ObjectData:JSON.stringify(current_component_obj)}, function(data_obj) {
				pageEventTriggered("account_registered",{"account_id":data_obj.Id});
				this.loadAccount();
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
			if (getComponentElementById(this,'Password').val() != getComponentElementById(this,'PasswordConfirm').val()) {
				toggleValidationState(this,'PasswordConfirm',"Passwords do not match",false);
				validation_succeeded = false;
			} else {
				toggleValidationState(this,'PasswordConfirm',"",true);
			}
			return validation_succeeded;
		}
		doCustomValidation(attribute) {
			switch (attribute) {
				case 'EmailAddress': let valid = validateEmail(getComponentElementById(this,'EmailAddress').val());
					toggleValidationState(this,'EmailAddress',"Please" +
						" provide a" +
						" valid email address",valid);
					return valid;
					break;
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
	component_classes['system_account_registration'] = system_account_registration;
}