if (typeof componentClasses['data_model_api_key_api_key_administration_update'] === "undefined") {
    class ApiKeyAdministrationUpdate extends DivbloxDomEntityInstanceComponent {
        constructor(inputs, supportsNative, requiresNative) {
            super(inputs, supportsNative, requiresNative);
            // Sub component config start
            this.subComponentDefinitions =
                [{
                    "component_load_path": "data_model/allowed_api_operation/allowed_api_operation_crud",
                    "parent_element": "DW5Iu",
                    "arguments": {}
                }];
            // Sub component config end
            this.includedAttributes = ['ApiKey', 'ValidFromDate', 'ValidToDate', 'Notes', 'CallingEntityInformation',];
            this.includedRelationships = [];
            this.constrainedByEntities = [];
            this.dataValidations = [];
            this.customValidations = [];
            this.requiredValidations = ['ValidFromDate',];
            this.initCrudVariables("ApiKey");
        }

        reset(inputs, propagate) {
            if (typeof inputs !== "undefined") {
                this.setEntityId(inputs);
            }
            super.reset(inputs, propagate);
        }

        setValues() {
            super.setValues();
            getComponentElementById(this, 'ApiKey').prop("disabled", true);
        }
    }

    componentClasses['data_model_api_key_api_key_administration_update'] = ApiKeyAdministrationUpdate;
}