if (typeof component_classes['data_model_allowed_api_operation_crud_create'] === "undefined") {
	class data_model_allowed_api_operation_crud_create extends DivbloxDomEntityInstanceComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
			this.sub_component_definitions = [];
			// Sub component config end
			this.included_attribute_array = ['IsActive',];
			this.included_relationship_array = ['ApiOperation',];
			this.constrain_by_array = ['ApiKey',];
			this.data_validation_array = [];
			this.custom_validation_array = [];
			this.required_validation_array = ['ApiOperation',];
			this.initCrudVariables("AllowedApiOperation");
		}
	}
	component_classes['data_model_allowed_api_operation_crud_create'] = data_model_allowed_api_operation_crud_create;
}