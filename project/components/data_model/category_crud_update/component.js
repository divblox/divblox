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
				showAlert("Are you sure? Deleting this category will delete all of it's subcategories.",
					"warning",
					["Cancel", "Delete"],
					false,
					0,
					this.deleteCategoryAndSubCategories.bind(this),
					this.doNothing);
			}.bind(this));
		}

		deleteCategoryAndSubCategories() {
			dxRequestInternal(
				getComponentControllerPath(this),
				{f: "deleteCategoryAndSubCategories"},
				function() {
					loadPageComponent("admin");
				}, function() {
					// Failure function
				},
				false
			)
		}
	}
	component_classes['data_model_category_crud_update'] = data_model_category_crud_update;
}