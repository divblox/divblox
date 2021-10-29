if (typeof componentClasses['data_model_api_key_api_key_administration_create'] === "undefined") {
    class ApiKeyAdministrationCreate extends DivbloxDomEntityInstanceComponent {
        constructor(inputs, supportsNative, requiresNative) {
            super(inputs, supportsNative, requiresNative);
            // Sub component config start
            this.subComponentDefinitions = [];
            // Sub component config end
            this.includedAttributes = ['ApiKey', 'ValidFromDate', 'ValidToDate', 'Notes', 'CallingEntityInformation',];
            this.includedRelationships = [];
            this.constrainedByEntities = [];
            this.dataValidations = [];
            this.customValidations = [];
            this.requiredValidations = ['ValidFromDate',];
            this.initCrudVariables("ApiKey");
        }

        setValues() {
            super.setValues();
            getComponentElementById(this, 'ApiKey').prop("disabled", true);
        }
    }

    componentClasses['data_model_api_key_api_key_administration_create'] = ApiKeyAdministrationCreate;
}