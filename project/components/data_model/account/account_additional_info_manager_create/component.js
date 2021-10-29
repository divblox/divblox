if (typeof componentClasses['data_model_account_account_additional_info_manager_create'] === "undefined") {
    class AccountAdditionalInfoManagerCreate extends DivbloxDomEntityInstanceComponent {
        constructor(inputs, supportsNative, requiresNative) {
            super(inputs, supportsNative, requiresNative);
            // Sub component config start
            this.subComponentDefinitions = [];
            // Sub component config end
            this.includedAttributes = ['Type', 'Label', 'Value',];
            this.includedRelationships = [];
            this.constrainedByEntities = ['Account',];
            this.dataValidations = [];
            this.customValidations = [];
            this.requiredValidations = ['Type', 'Value',];
            this.initCrudVariables("AdditionalAccountInformation");
        }
    }

    componentClasses['data_model_account_account_additional_info_manager_create'] = AccountAdditionalInfoManagerCreate;
}