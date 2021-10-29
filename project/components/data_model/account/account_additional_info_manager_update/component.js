if (typeof componentClasses['data_model_account_account_additional_info_manager_update'] === "undefined") {
    class AccountAdditionalInfoManagerUpdate extends DivbloxDomEntityInstanceComponent {
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

        reset(inputs, propagate) {
            if (typeof inputs !== "undefined") {
                this.setEntityId(inputs);
            }
            super.reset(inputs, propagate);
        }
    }

    componentClasses['data_model_account_account_additional_info_manager_update'] = AccountAdditionalInfoManagerUpdate;
}