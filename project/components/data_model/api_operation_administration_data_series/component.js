if (typeof component_classes['data_model_api_operation_administration_data_series'] === "undefined") {
	class data_model_api_operation_administration_data_series extends DivbloxDomEntityDataTableComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
			this.sub_component_definitions = [];
			// Sub component config end
			this.included_attribute_array = ['OperationName','CrudEntityName',];
			this.included_relationship_array = [];
			this.constrain_by_array = [];
			this.initDataTableVariables("ApiOperation");
		}
	}
	component_classes['data_model_api_operation_administration_data_series'] = data_model_api_operation_administration_data_series;
}