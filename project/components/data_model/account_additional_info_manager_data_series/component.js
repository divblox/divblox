if (typeof component_classes['data_model_account_additional_info_manager_data_series'] === "undefined") {
	class data_model_account_additional_info_manager_data_series extends DivbloxDomEntityDataListComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
			this.sub_component_definitions = [];
			// Sub component config end
			this.included_attributes_object =
				{"Type":"Subtle","Label":"Header","Value":"Normal"};
			this.included_relationships_object =
				[];
			this.constrain_by_array = ['Account',];
			this.initDataListVariables("AdditionalAccountInformation");
		}
	}
	component_classes['data_model_account_additional_info_manager_data_series'] = data_model_account_additional_info_manager_data_series;
}