if (typeof(on_system_account_registration_ready) === "undefined") {
	function on_system_account_registration_ready(load_arguments) {
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
		this.custom_validation_array = ['EmailAddress'];
		this.required_validation_array = ['FirstName','LastName','Password'].concat(this.data_validation_array).concat(this.custom_validation_array);
		
		getComponentElementById(this,"btnSave").on("click", function() {
			this.saveAccount();
		}.bind(this));
		this.loadAccount = function() {
			let this_component = this;
			let uid = this_component.getUid();
			dxRequestInternal(getComponentControllerPath(this),{f:"getObjectData"}, function(data_obj) {
				this_component.dom_component_obj.component_obj = {
					"FirstName":"",
                    "LastName":"",
                    "EmailAddress":"",
                    "Password":"",
                    };
				this_component.dom_component_obj.element_mapping = {
					"FirstName":"#"+uid+"_FirstName",
                    "LastName":"#"+uid+"_LastName",
                    "EmailAddress":"#"+uid+"_EmailAddress",
                    "Password":"#"+uid+"_Password",
                    };
				
				this_component.setValues();
			}, function(data_obj) {
				this_component.handleComponentError(data_obj.Message);
			});
		}.bind(this);
		this.setValues = function() {
			let this_component = this;
			let uid = this_component.getUid();
			getComponentElementById(this_component,"FirstName").val("");
            getComponentElementById(this_component,"LastName").val("");
            getComponentElementById(this_component,"EmailAddress").val("");
            getComponentElementById(this_component,"Password").val("");
            
			
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
			dxRequestInternal(getComponentControllerPath(this),{f:"saveObjectData",ObjectData:JSON.stringify(current_component_obj)}, function(data_obj) {
				pageEventTriggered("account_registered",{"account_id":data_obj.Id});
				this_component.loadAccount();
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
			if (getComponentElementById(this_component,'Password').val() != getComponentElementById(this_component,'PasswordConfirm').val()) {
				toggleValidationState(this_component,'PasswordConfirm',"Passwords do not match",false);
				validation_succeeded = false;
			} else {
				toggleValidationState(this_component,'PasswordConfirm',"",true);
			}
			return validation_succeeded;
		}.bind(this);
		this.doCustomValidation = function(attribute) {
			let this_component = this;
			switch (attribute) {
				case 'EmailAddress': let valid = validateEmail(getComponentElementById(this_component,'EmailAddress').val());
					toggleValidationState(this_component,'EmailAddress',"Please" +
					" provide a" +
					" valid email address",valid);
					return valid;
					break;
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
	}
}
