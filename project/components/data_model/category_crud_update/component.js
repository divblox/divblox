if (typeof component_classes['data_model_category_crud_update'] === "undefined") {
	class data_model_category_crud_update extends DivbloxDomEntityInstanceComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
			this.sub_component_definitions = [];
			// Sub component config end
			this.included_attribute_array = ['CategoryLabel',];
			this.included_relationship_array = [];
			this.constrain_by_array = [];
			this.data_validation_array = [];
			this.custom_validation_array = [];
			this.required_validation_array = ['CategoryLabel',];
			this.initCrudVariables("Category");
		}
		reset(inputs,propagate) {
			this.setEntityId(this.getEntityId());
			super.reset(inputs,propagate);
		}

		getEntityId() {
			// return this.getLoadArgument("entity_id");
			return getGlobalConstrainById("Category");
		}

		initCustomFunctions() {
			super.initCustomFunctions();
			getComponentElementById(this, "btnDelete").on("click", function () {
				showAlert("Are you sure you want to delete this category?",
					"warning",
					["Cancel", "Delete"],
					false,
					0,
					this.deleteEntity.bind(this),
					this.doNothing);
			}.bind(this));
		}

		deleteEntity() {
			dxRequestInternal(
				getComponentControllerPath(this),
				{
					f: "deleteObjectData",
					Id: this.getLoadArgument("entity_id")
				},
				function (data_obj) {
					this.loadEntity();
					pageEventTriggered(this.lowercase_entity_name + "_deleted");
					setGlobalConstrainById('Category', data_obj.CategoryParentId);
					loadPageComponent('admin');
				}.bind(this),
				function (data_obj) {
					showAlert("Error deleting " + this.lowercase_entity_name + ": " + data_obj.Message, "error", "OK", false);
				}.bind(this));
		}

		saveEntity() {
			let current_component_obj = this.updateValues();
			this.resetValidation();
			if (!this.validateEntity()) {
				return;
			}
			let parameters_obj = {
				f: "saveObjectData",
				ObjectData: JSON.stringify(current_component_obj),
				Id: this.getLoadArgument("entity_id")
			};
			if (this.constrain_by_array.length > 0) {
				this.constrain_by_array.forEach(function (relationship) {
					parameters_obj['Constraining' + relationship + 'Id'] = getGlobalConstrainById(relationship);
				})
			}
			dxRequestInternal(
				getComponentControllerPath(this),
				parameters_obj,
				function (data_obj) {
					if (this.getLoadArgument("entity_id") != null) {
						setGlobalConstrainById(this.entity_name, data_obj.Id);
						pageEventTriggered(this.lowercase_entity_name + "_updated", {"id": data_obj.Id});
						loadPageComponent("category_update");
					} else {
						setGlobalConstrainById(this.entity_name, data_obj.Id);
						pageEventTriggered(this.lowercase_entity_name + "_created", {"id": data_obj.Id});
					}
					this.loadEntity();
					this.resetValidation();
				}.bind(this),
				function (data_obj) {
					showAlert("Error saving " + this.lowercase_entity_name + ": " + data_obj.Message, "error", "OK", false);
				}.bind(this));
		}
	}
	component_classes['data_model_category_crud_update'] = data_model_category_crud_update;
}