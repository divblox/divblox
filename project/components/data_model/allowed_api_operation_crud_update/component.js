if (typeof component_classes['data_model_allowed_api_operation_crud_update'] === "undefined") {
	class data_model_allowed_api_operation_crud_update extends DivbloxDomBaseComponent {
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
			if (typeof inputs !== "undefined") {
				this.setAllowedApiOperationId(inputs);
				this.loadAllowedApiOperation();
			}
			super.reset(inputs);
		}
		registerDomEvents() {
			getComponentElementById(this,"btnSave").on("click", function() {
				this.saveAllowedApiOperation();
			}.bind(this));
			getComponentElementById(this,"btnDelete").on("click", function() {
				showAlert("Are you sure?","warning",["Cancel","Delete"],false,0,this.deleteAllowedApiOperation.bind(this),this.doNothing);
			}.bind(this));
		}
		setAllowedApiOperationId(allowed_api_operation_id) {
			this.arguments["allowed_api_operation_id"] = allowed_api_operation_id;
		}
		getAllowedApiOperationId() {
			return this.arguments["allowed_api_operation_id"];
		}
		loadAllowedApiOperation() {
			dxRequestInternal(getComponentControllerPath(this),{f:"getObjectData",Id:this.getAllowedApiOperationId()}, function(data_obj) {
				this.removeLoadingState();
				this.component_obj = data_obj.Object;
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
			let AllowedApiOperationObj = this.component_obj;
			getComponentElementById(this,"IsActive").prop("checked",getDataModelAttributeValue(AllowedApiOperationObj.IsActive));
            
			getComponentElementById(this,"ApiOperation").html('<option value="">-Please Select-</option>');
            let object_keys_api_operation_list = Object.keys(this.api_operation_list);
            if (object_keys_api_operation_list.length > 0) {
                this.api_operation_list.forEach(function (ApiOperationItem) {
                    let SelectedStr = "";
                    if (ApiOperationItem['Id'] == AllowedApiOperationObj.ApiOperation) {
                        SelectedStr = "selected";
                    }
                    if (this.api_operation_list[0]['Id'] == "DATASET TOO LARGE") {
                        dxLog("Data set too large for ApiOperation. Consider using another option to link the object");
                    } else {
                        getComponentElementById(this,"ApiOperation").append('<option value="'+ApiOperationItem['Id']+'" '+SelectedStr+'>'+ApiOperationItem['OperationName']+'</option>');
                    }
                }.bind(this));
            } else {
                dxLog("ApiOperation list is empty");
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
					Id:this.arguments["allowed_api_operation_id"]}, function(data_obj) {
					showAlert("Updated!");
					pageEventTriggered("allowed_api_operation_updated");
					this.resetValidation();
				}.bind(this), function(data_obj) {
					showAlert("Error saving allowed_api_operation: "+data_obj.Message,"error","OK",false);
				}.bind(this));
		}
		deleteAllowedApiOperation() {
			dxRequestInternal(getComponentControllerPath(this),{f:"deleteObjectData",Id:this.arguments["allowed_api_operation_id"]}, function(data_obj) {
				showAlert("Deleted!");
				this.loadAllowedApiOperation();
				pageEventTriggered("allowed_api_operation_deleted");
			}.bind(this), function (data_obj) {
				showAlert("Error deleting allowed_api_operation: "+data_obj.Message,"error","OK",false);
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
		doNothing() {
			// Just a helper function to reference on cancel of confirmation
		}
	}
	component_classes['data_model_allowed_api_operation_crud_update'] = data_model_allowed_api_operation_crud_update;
}