if (typeof componentClasses['data_model_api_operation_api_operation_administration_create'] === "undefined") {
    class ApiOperationAdministrationCreate extends DivbloxDomEntityInstanceComponent {
        constructor(inputs, supportsNative, requiresNative) {
            super(inputs, supportsNative, requiresNative);
            // Sub component config start
            this.subComponentDefinitions = [];
            // Sub component config end
            this.includedAttributes = ['OperationName', 'CrudEntityName',];
            this.includedRelationships = [];
            this.constrainedByEntities = [];
            this.dataValidations = [];
            this.customValidations = [];
            this.requiredValidations = ['OperationName', 'CrudEntityName',];
            this.initCrudVariables("ApiOperation");
        }
    }

    componentClasses['data_model_api_operation_api_operation_administration_create'] = ApiOperationAdministrationCreate;
}