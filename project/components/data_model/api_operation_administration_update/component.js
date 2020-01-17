if (typeof component_classes['data_model_api_operation_administration_update'] === "undefined") {
	class data_model_api_operation_administration_update extends DivbloxDomBaseComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
			this.sub_component_definitions = [];
			// Sub component config end
			this.data_validation_array = [];
			this.custom_validation_array = [];
			this.required_validation_array = ['OperationName',].concat(this.data_validation_array).concat(this.custom_validation_array);
			
		}
		reset(inputs) {
			this.setLoadingState();
			if (typeof inputs !== "undefined") {
				this.setApiOperationId(inputs);
				this.loadApiOperation();
			}
			super.reset(inputs);
		}
		registerDomEvents() {
			getComponentElementById(this,"btnSave").on("click", function() {
				this.saveApiOperation();
			}.bind(this));
			getComponentElementById(this,"btnDelete").on("click", function() {
				showAlert("Are you sure?","warning",["Cancel","Delete"],false,0,this.deleteApiOperation.bind(this),this.doNothing);
			}.bind(this));
		}
		setApiOperationId(api_operation_id) {
			this.arguments["api_operation_id"] = api_operation_id;
		}
		getApiOperationId() {
			return this.arguments["api_operation_id"];
		}
		loadApiOperation() {
			dxRequestInternal(getComponentControllerPath(this),{f:"getObjectData",Id:this.getApiOperationId()}, function(data_obj) {
				this.removeLoadingState();
				this.component_obj = data_obj.Object;
				this.element_mapping = {
					"OperationName":"#"+this.uid+"_OperationName",
                    };
				
				this.setValues();
			}.bind(this), function(data_obj) {
				this.handleComponentError(data_obj.Message);
			}.bind(this));
		}
		setValues() {
			let ApiOperationObj = this.component_obj;
			getComponentElementById(this,"OperationName").val(getDataModelAttributeValue(ApiOperationObj.OperationName));
            
			
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
		saveApiOperation() {
			let current_component_obj = this.updateValues();
			this.resetValidation();
			if (!this.validateApiOperation()) {
				return;
			}
			dxRequestInternal(
				getComponentControllerPath(this),
				{f:"saveObjectData",
					ObjectData:JSON.stringify(current_component_obj),
					Id:this.arguments["api_operation_id"]}, function(data_obj) {
					showAlert("Updated!");
					pageEventTriggered("api_operation_updated");
					this.resetValidation();
				}.bind(this), function(data_obj) {
					showAlert("Error saving api_operation: "+data_obj.Message,"error","OK",false);
				}.bind(this));
		}
		deleteApiOperation() {
			dxRequestInternal(getComponentControllerPath(this),{f:"deleteObjectData",Id:this.arguments["api_operation_id"]}, function(data_obj) {
				showAlert("Deleted!");
				this.loadApiOperation();
				pageEventTriggered("api_operation_deleted");
			}.bind(this), function (data_obj) {
				showAlert("Error deleting api_operation: "+data_obj.Message,"error","OK",false);
			}.bind(this));
		}
		validateApiOperation() {
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
	component_classes['data_model_api_operation_administration_update'] = data_model_api_operation_administration_update;
}