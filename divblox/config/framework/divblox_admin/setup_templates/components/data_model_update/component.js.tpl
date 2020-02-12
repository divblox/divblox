if (typeof component_classes['[component_full_name]'] === "undefined") {
	class [component_full_name] extends DivbloxDomBaseComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
			this.sub_component_definitions = [];
			// Sub component config end
			this.data_validation_array = [Data-Validation-Array];
			this.custom_validation_array = [Custom-Validation-Array];
			this.required_validation_array = [Required-Validation-Array].concat(this.data_validation_array).concat(this.custom_validation_array);
			[Relationships-Init]
		}
		reset(inputs) {
			this.setLoadingState();
			if (typeof inputs !== "undefined") {
				this.set[EntityName]Id(inputs);
				this.load[EntityName]();
			}
			super.reset(inputs);
		}
		registerDomEvents() {
			getComponentElementById(this,"btnSave").on("click", function() {
				this.save[EntityName]();
			}.bind(this));
			getComponentElementById(this,"btnDelete").on("click", function() {
				showAlert("Are you sure?","warning",["Cancel","Delete"],false,0,this.delete[EntityName].bind(this),this.doNothing);
			}.bind(this));
		}
		set[EntityName]Id([EntityName-Lowercase]_id) {
			this.arguments["[EntityName-Lowercase]_id"] = [EntityName-Lowercase]_id;
		}
		get[EntityName]Id() {
			return this.arguments["[EntityName-Lowercase]_id"];
		}
		load[EntityName]() {
			dxRequestInternal(getComponentControllerPath(this),{f:"getObjectData",Id:this.get[EntityName]Id()}, function(data_obj) {
				this.removeLoadingState();
				this.component_obj = data_obj.Object;
				this.element_mapping = {
					[Component-Object-Element-Mapping-Init]};
				[Relationships-Assign]
				this.setValues();
			}.bind(this), function(data_obj) {
				this.handleComponentError(data_obj.Message);
			}.bind(this));
		}
		setValues() {
			let [EntityName]Obj = this.component_obj;
			[Attributes-Set-Values]
			[Relationships-Set-Values]
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
		save[EntityName]() {
			let current_component_obj = this.updateValues();
			this.resetValidation();
			if (!this.validate[EntityName]()) {
				return;
			}
			dxRequestInternal(
				getComponentControllerPath(this),
				{f:"saveObjectData",
					ObjectData:JSON.stringify(current_component_obj),
					Id:this.arguments["[EntityName-Lowercase]_id"]}, function(data_obj) {
					showAlert("Updated!");
					pageEventTriggered("[EntityName-Lowercase]_updated");
					this.resetValidation();
				}.bind(this), function(data_obj) {
					showAlert("Error saving [EntityName-Lowercase]: "+data_obj.Message,"error","OK",false);
				}.bind(this));
		}
		delete[EntityName]() {
			dxRequestInternal(getComponentControllerPath(this),{f:"deleteObjectData",Id:this.arguments["[EntityName-Lowercase]_id"]}, function(data_obj) {
				showAlert("Deleted!");
				this.load[EntityName]();
				pageEventTriggered("[EntityName-Lowercase]_deleted");
			}.bind(this), function (data_obj) {
				showAlert("Error deleting [EntityName-Lowercase]: "+data_obj.Message,"error","OK",false);
			}.bind(this));
		}
		validate[EntityName]() {
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
				[Custom-Validation]
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
	component_classes['[component_full_name]'] = [component_full_name];
}