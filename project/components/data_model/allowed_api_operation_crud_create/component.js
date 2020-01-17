if (typeof component_classes['data_model_allowed_api_operation_crud_create'] === "undefined") {
	class data_model_allowed_api_operation_crud_create extends DivbloxDomBaseComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
			this.sub_component_definitions = [];
			// Sub component config end
			this.data_validation_array = [];
			this.custom_validation_array = [];
			this.required_validation_array = ['ApiOperation',].concat(this.data_validation_array).concat(this.custom_validation_array);
			this.api_operation_list = {};
        
		}
		reset(inputs) {
			this.setLoadingState();
			this.loadAllowedApiOperation();
			super.reset(inputs);
		}
		registerDomEvents() {
			getComponentElementById(this,"btnSave").on("click", function() {
				this.saveAllowedApiOperation();
			}.bind(this));
		}
		loadAllowedApiOperation() {
			dxRequestInternal(getComponentControllerPath(this),{f:"getObjectData"}, function(data_obj) {
				this.removeLoadingState();
				this.component_obj = {
					"IsActive":"",
                    "ApiOperation":"",
                    };
				this.element_mapping = {
					"IsActive":"#"+this.uid+"_IsActive",
                    "ApiOperation":"#"+this.uid+"_ApiOperation",
                    };
				this.api_operation_list = data_obj.ApiOperationList;
                
				this.setValues();
			}.bind(this), function(data_obj) {
				this.handleComponentError(data_obj.Message);
			}.bind(this));
		}
		setValues() {
			getComponentElementById(this,"IsActive").prop("checked",true);
            
			getComponentElementById(this,"ApiOperation").html('<option value="">-Please Select-</option>');
            let object_keys_api_operation_list = Object.keys(this.api_operation_list);
            if (object_keys_api_operation_list.length > 0) {
                this.api_operation_list.forEach(function (ApiOperationItem) {
                    if (ApiOperationItem['Id'] == "DATASET TOO LARGE") {
                        dxLog("Data set too large for ApiOperation. Consider using another option to link the object");
                    } else {
                        getComponentElementById(this,"ApiOperation").append('<option value="'+ApiOperationItem['Id']+'">'+ApiOperationItem['OperationName']+'</option>');
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
		saveAllowedApiOperation() {
			let current_component_obj = this.updateValues();
			this.resetValidation();
			if (!this.validateAllowedApiOperation()) {
				return;
			}
			dxRequestInternal(
				getComponentControllerPath(this),
				{f:"saveObjectData",
					ObjectData:JSON.stringify(current_component_obj),
                ConstrainingApiKeyId:getGlobalConstrainById('ApiKey')},
				function(data_obj) {
					showAlert("Created!");
					pageEventTriggered("allowed_api_operation_created",{"allowed_api_operation_id":data_obj.Id});
					this.loadAllowedApiOperation();
					this.resetValidation();
				}.bind(this),
				function(data_obj) {
					showAlert("Error saving allowed_api_operation: "+data_obj.Message,"error","OK",false);
				}.bind(this));
		}
		validateAllowedApiOperation() {
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
	component_classes['data_model_allowed_api_operation_crud_create'] = data_model_allowed_api_operation_crud_create;
}