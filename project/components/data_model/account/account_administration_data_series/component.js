if (typeof componentClasses['data_model_account_account_administration_data_series'] === "undefined") {
    class AccountAdministrationDataSeries extends DivbloxDomEntityDataTableComponent {
        constructor(inputs, supportsNative, requiresNative) {
            super(inputs, supportsNative, requiresNative);
            // Sub component config start
            this.subComponentDefinitions = [];
            // Sub component config end
            this.includedAttributes = ['FullName', 'EmailAddress', 'Username',];
            this.includedRelationships = ['UserRole',];
            this.constrainedByEntities = [];
            this.initDataTableVariables("Account");
        }
    }

    componentClasses['data_model_account_account_administration_data_series'] = AccountAdministrationDataSeries;
}