if (typeof component_classes['data_model_category_crud_create'] === "undefined") {
	class data_model_category_crud_create extends DivbloxDomEntityInstanceComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
			this.sub_component_definitions = [];
			// Sub component config end
			this.included_attribute_array = ['CategoryLabel',];
			this.included_relationship_array = [];
			this.constrain_by_array = ["Category"];
			this.data_validation_array = [];
			this.custom_validation_array = [];
			this.required_validation_array = ['CategoryLabel',];
			this.initCrudVariables("Category");
		}

		saveEntity() {
			let current_component_obj = this.updateValues();
			this.resetValidation();
			if (!this.validateEntity()) {
				return;
			}
			let parameters_obj = {f:"saveObjectData",
				ObjectData:JSON.stringify(current_component_obj),
				Id:this.getLoadArgument("entity_id")};
			if (this.constrain_by_array.length > 0) {
				this.constrain_by_array.forEach(function(relationship) {
					parameters_obj['Constraining'+relationship+'Id'] = getGlobalConstrainById(relationship);
				})
			}
			dxRequestInternal(
				getComponentControllerPath(this),
				parameters_obj,
				function(data_obj) {
					if (this.getLoadArgument("entity_id") != null) {
						pageEventTriggered(this.lowercase_entity_name+"_updated",{"id":data_obj.Id});
					} else {
						pageEventTriggered(this.lowercase_entity_name+"_created",{"id":data_obj.Id});
					}
					this.loadEntity();
					this.resetValidation();
				}.bind(this),
				function(data_obj) {
					showAlert("Error saving "+this.lowercase_entity_name+": "+data_obj.Message,"error","OK",false);
				}.bind(this));
		}
	}


	component_classes['data_model_category_crud_create'] = data_model_category_crud_create;
}