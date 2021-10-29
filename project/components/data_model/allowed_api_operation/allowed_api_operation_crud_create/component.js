if (typeof componentClasses['data_model_allowed_api_operation_allowed_api_operation_crud_create'] === "undefined") {
    class AllowedApiOperationCrudCreate extends DivbloxDomEntityInstanceComponent {
        constructor(inputs, supportsNative, requiresNative) {
            super(inputs, supportsNative, requiresNative);
            // Sub component config start
            this.subComponentDefinitions = [];
            // Sub component config end
            this.includedAttributes = ['IsActive',];
            this.includedRelationships = ['ApiOperation',];
            this.constrainedByEntities = ['ApiKey',];
            this.dataValidations = [];
            this.customValidations = [];
            this.requiredValidations = ['ApiOperation',];
            this.initCrudVariables("AllowedApiOperation");
        }
    }

    componentClasses['data_model_allowed_api_operation_allowed_api_operation_crud_create'] = AllowedApiOperationCrudCreate;
}