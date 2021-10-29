if (typeof componentClasses['data_model_allowed_api_operation_allowed_api_operation_crud_data_series'] === "undefined") {
    class AllowedApiOperationCrudDataSeries extends DivbloxDomEntityDataTableComponent {
        constructor(inputs, supportsNative, requiresNative) {
            super(inputs, supportsNative, requiresNative);
            // Sub component config start
            this.subComponentDefinitions = [];
            // Sub component config end
            this.includedAttributes = ['IsActive',];
            this.includedRelationships = ['ApiOperation',];
            this.constrainedByEntities = ['ApiKey',];
            this.initDataTableVariables("AllowedApiOperation");
        }
    }

    componentClasses['data_model_allowed_api_operation_allowed_api_operation_crud_data_series'] = AllowedApiOperationCrudDataSeries;
}