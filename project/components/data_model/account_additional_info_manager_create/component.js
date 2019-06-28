if (typeof component_classes['data_model_account_additional_info_manager_create'] === "undefined") {
	class data_model_account_additional_info_manager_create extends DivbloxDomBaseComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
			this.sub_component_definitions = {};
			// Sub component config end
			this.data_validation_array = [];
			this.custom_validation_array = [];
			this.required_validation_array = [].concat(this.data_validation_array).concat(this.custom_validation_array);
		}
		registerDomEvents() {
			getComponentElementById(this,"btnSave").on("click", function() {
				this.saveAdditionalAccountInformation();
			}.bind(this));
		}
		reset(inputs) {
			this.loadAdditionalAccountInformation();
			super.reset(inputs);
		}
		loadAdditionalAccountInformation() {
			dxRequestInternal(getComponentControllerPath(this),{f:"getObjectData"}, function(data_obj) {
				this.component_obj = {
					"Type":"",
					"Label":"",
					"Value":"",
				};
				this.element_mapping = {
					"Type":"#"+this.uid+"_Type",
					"Label":"#"+this.uid+"_Label",
					"Value":"#"+this.uid+"_Value",
				};
				
				this.setValues();
			}.bind(this), function(data_obj) {
				this.handleComponentError(data_obj.Message);
			}.bind(this));
		}
		setValues() {
			getComponentElementById(this,"Type").val("");
			getComponentElementById(this,"Label").val("");
			getComponentElementById(this,"Value").val("");
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
		saveAdditionalAccountInformation() {
			this.resetValidation();
			if (!this.validateAdditionalAccountInformation()) {
				return;
			}
			let component_obj = this.updateValues();
			dxRequestInternal(getComponentControllerPath(this),{f:"saveObjectData",ObjectData:JSON.stringify(component_obj)}, function(data_obj) {
				showAlert("Created!");
				pageEventTriggered("additional_account_information_created",{"additional_account_information_id":data_obj.Id});
				this.loadAdditionalAccountInformation();
				this.resetValidation();
			}.bind(this), function(data_obj) {
				showAlert("Error saving additional_account_information: "+data_obj.Message,"error","OK",false);
			});
		}
		validateAdditionalAccountInformation() {
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
	}
	component_classes['data_model_account_additional_info_manager_create'] = data_model_account_additional_info_manager_create;
}