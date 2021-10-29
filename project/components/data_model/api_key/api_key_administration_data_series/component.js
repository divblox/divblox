if (typeof componentClasses['data_model_api_key_api_key_administration_data_series'] === "undefined") {
    class ApiKeyAdministrationDataSeries extends DivbloxDomEntityDataTableComponent {
        constructor(inputs, supportsNative, requiresNative) {
            super(inputs, supportsNative, requiresNative);
            // Sub component config start
            this.subComponentDefinitions = [];
            // Sub component config end
            this.includedAttributes = ['ApiKey', 'ValidFromDate', 'ValidToDate', 'CallingEntityInformation',];
            this.includedRelationships = [];
            this.constrainedByEntities = [];
            this.initDataTableVariables("ApiKey");
        }
    }

    componentClasses['data_model_api_key_api_key_administration_data_series'] = ApiKeyAdministrationDataSeries;
}