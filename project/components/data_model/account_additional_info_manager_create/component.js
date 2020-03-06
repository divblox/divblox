if (typeof component_classes['data_model_account_additional_info_manager_create'] === "undefined") {
	class data_model_account_additional_info_manager_create extends DivbloxDomEntityInstanceComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
			this.sub_component_definitions = [];
			// Sub component config end
			this.included_attribute_array = ['Type','Label','Value',];
			this.included_relationship_array = [];
			this.constrain_by_array = ['Account',];
			this.data_validation_array = [];
			this.custom_validation_array = [];
			this.required_validation_array = ['Type','Value',];
			this.initCrudVariables("AdditionalAccountInformation");
		}
	}
	component_classes['data_model_account_additional_info_manager_create'] = data_model_account_additional_info_manager_create;
}