if (typeof componentClasses['data_model_api_operation_api_operation_administration_data_series'] === "undefined") {
    class ApiOperationAdministrationDataSeries extends DivbloxDomEntityDataTableComponent {
        constructor(inputs, supportsNative, requiresNative) {
            super(inputs, supportsNative, requiresNative);
            // Sub component config start
            this.subComponentDefinitions = [];
            // Sub component config end
            this.includedAttributes = ['OperationName', 'CrudEntityName',];
            this.includedRelationships = [];
            this.constrainedByEntities = [];
            this.initDataTableVariables("ApiOperation");
        }
    }

    componentClasses['data_model_api_operation_api_operation_administration_data_series'] = ApiOperationAdministrationDataSeries;
}