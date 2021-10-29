if (typeof componentClasses['data_model_api_operation_api_operation_administration_update'] === "undefined") {
    class ApiOperationAdministrationUpdate extends DivbloxDomEntityInstanceComponent {
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

        reset(inputs, propagate) {
            if (typeof inputs !== "undefined") {
                this.setEntityId(inputs);
            }
            super.reset(inputs, propagate);
        }
    }

    componentClasses['data_model_api_operation_api_operation_administration_update'] = ApiOperationAdministrationUpdate;
}