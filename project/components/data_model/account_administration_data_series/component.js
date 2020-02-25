if (typeof component_classes['data_model_account_administration_data_series'] === "undefined") {
	class data_model_account_administration_data_series extends DivbloxDomEntityDataTableComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
			this.sub_component_definitions = [];
			// Sub component config end
			this.included_attribute_array = ['FullName','EmailAddress','Username',];
			this.included_relationship_array = ['UserRole',];
			this.constrain_by_array = [];
			this.initDataTableVariables("Account");
		}
	}
	component_classes['data_model_account_administration_data_series'] = data_model_account_administration_data_series;
}