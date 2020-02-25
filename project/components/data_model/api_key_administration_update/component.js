if (typeof component_classes['data_model_api_key_administration_update'] === "undefined") {
	class data_model_api_key_administration_update extends DivbloxDomEntityInstanceComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
            this.sub_component_definitions =
                [{"component_load_path":"data_model/allowed_api_operation_crud","parent_element":"DW5Iu","arguments":{}}];
            // Sub component config end
			this.included_attribute_array = ['ApiKey','ValidFromDate','ValidToDate','Notes','CallingEntityInformation',];
			this.included_relationship_array = [];
			this.constrain_by_array = [];
			this.data_validation_array = [];
			this.custom_validation_array = [];
			this.required_validation_array = ['ValidFromDate',];
			this.initCrudVariables("ApiKey");
		}
		reset(inputs,propagate) {
			if (typeof inputs !== "undefined") {
				this.setEntityId(inputs);
			}
			super.reset(inputs,propagate);
		}
		setValues() {
			super.setValues();
			getComponentElementById(this,'ApiKey').prop("disabled",true);
		}
	}
	component_classes['data_model_api_key_administration_update'] = data_model_api_key_administration_update;
}