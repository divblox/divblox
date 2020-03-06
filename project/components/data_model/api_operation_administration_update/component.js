if (typeof component_classes['data_model_api_operation_administration_update'] === "undefined") {
	class data_model_api_operation_administration_update extends DivbloxDomEntityInstanceComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
			this.sub_component_definitions = [];
			// Sub component config end
			this.included_attribute_array = ['OperationName','CrudEntityName',];
			this.included_relationship_array = [];
			this.constrain_by_array = [];
			this.data_validation_array = [];
			this.custom_validation_array = [];
			this.required_validation_array = ['OperationName','CrudEntityName',];
			this.initCrudVariables("ApiOperation");
		}
		reset(inputs,propagate) {
			if (typeof inputs !== "undefined") {
				this.setEntityId(inputs);
			}
			super.reset(inputs,propagate);
		}
	}
	component_classes['data_model_api_operation_administration_update'] = data_model_api_operation_administration_update;
}