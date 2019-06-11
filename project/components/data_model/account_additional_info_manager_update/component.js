if (typeof(on_data_model_account_additional_info_manager_update_ready) === "undefined") {
	function on_data_model_account_additional_info_manager_update_ready(load_arguments) {
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
				this_component.setAdditionalAccountInformationId(inputs);
				this_component.loadAdditionalAccountInformation();
			}
		}.bind(this);
		this.on_component_loaded = function() {
			this.dom_component_obj.on_component_loaded(this,false);
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
		this.required_validation_array = [].concat(this.data_validation_array).concat(this.custom_validation_array);
		getComponentElementById(this,"btnSave").on("click", function() {
			let uid = getUidFromComponentElementId($(this).attr("id"),"btnSave");
			getRegisteredComponent(uid).saveAdditionalAccountInformation();
		});
		getComponentElementById(this,"btnDelete").on("click", function() {
			let uid = getUidFromComponentElementId($(this).attr("id"),"btnDelete");
			let this_component = getRegisteredComponent(uid);
			showAlert("Are you sure?","warning",["Cancel","Delete"],false,0,this_component.deleteAdditionalAccountInformation,this_component.doNothing);
		});
		this.setAdditionalAccountInformationId = function(additional_account_information_id) {
			let this_component = this;
			this_component.dom_component_obj.arguments["additional_account_information_id"] = additional_account_information_id;
		}.bind(this);
		this.getAdditionalAccountInformationId = function() {
			let this_component = this;
			return this_component.dom_component_obj.arguments["additional_account_information_id"];
		}.bind(this);
		this.loadAdditionalAccountInformation = function() {
			let this_component = this;
			let uid = this_component.dom_component_obj.uid;
			dxRequestInternal(getComponentControllerPath(this),{f:"getObjectData",Id:this_component.getAdditionalAccountInformationId()}, function(data_obj) {
				this_component.dom_component_obj.component_obj = data_obj.Object;
				this_component.dom_component_obj.element_mapping = {
					"Type":"#"+uid+"_Type",
                    "Label":"#"+uid+"_Label",
                    "Value":"#"+uid+"_Value",
                    };
				
				this_component.setValues();
			}, function(data_obj) {
				this_component.handleComponentError(data_obj.Message);
			});
		}.bind(this);
		this.setValues = function() {
			let this_component = this;
			let uid = this_component.dom_component_obj.uid;
			let AdditionalAccountInformationObj = this_component.dom_component_obj.component_obj;
			getComponentElementById(this_component,"Type").val(getDataModelAttributeValue(AdditionalAccountInformationObj.Type));
            getComponentElementById(this_component,"Label").val(getDataModelAttributeValue(AdditionalAccountInformationObj.Label));
            getComponentElementById(this_component,"Value").val(getDataModelAttributeValue(AdditionalAccountInformationObj.Value));
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
		this.saveAdditionalAccountInformation = function() {
			let this_component = this;
			let current_component_obj = this_component.updateValues();
			this_component.resetValidation();
			if (!this_component.validateAdditionalAccountInformation()) {
				return;
			}
			dxRequestInternal(getComponentControllerPath(this),{f:"saveObjectData",ObjectData:JSON.stringify(current_component_obj),Id:this_component.dom_component_obj.arguments["additional_account_information_id"]}, function(data_obj) {
				showAlert("Updated!");
				pageEventTriggered("additional_account_information_updated");
				this_component.resetValidation();
			}, function(data_obj) {
				showAlert("Error saving additional_account_information: "+data_obj.Message,"error","OK",false);
			});
		}.bind(this);
		this.deleteAdditionalAccountInformation = function() {
			let this_component = this;
			let uid = this_component.dom_component_obj.uid;
			dxRequestInternal(getComponentControllerPath(this),{f:"deleteObjectData",Id:this_component.dom_component_obj.arguments["additional_account_information_id"]}, function(data_obj) {
				showAlert("Deleted!");
				this_component.loadAdditionalAccountInformation();
				pageEventTriggered("additional_account_information_deleted");
				}, function (data_obj) {
				showAlert("Error deleting additional_account_information: "+data_obj.Message,"error","OK",false);
			});
		}.bind(this);
		this.validateAdditionalAccountInformation = function() {
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
			// Just a helper function to reference on cancel of confirmation
		};
	}
}
