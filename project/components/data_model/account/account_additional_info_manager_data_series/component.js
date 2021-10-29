if (typeof componentClasses['data_model_account_account_additional_info_manager_data_series'] === "undefined") {
    class AccountAdditionalInfoManagerDataSeries extends DivbloxDomEntityDataListComponent {
        constructor(inputs, supportsNative, requiresNative) {
            super(inputs, supportsNative, requiresNative);
            // Sub component config start
            this.subComponentDefinitions = [];
            // Sub component config end
            this.includedAttributes =
                {"Type": "Subtle", "Label": "Header", "Value": "Normal"};
            this.includedRelationships =
                [];
            this.constrainedByEntities = ['Account',];
            this.initDataListVariables("AdditionalAccountInformation");
        }
    }

    componentClasses['data_model_account_account_additional_info_manager_data_series'] = AccountAdditionalInfoManagerDataSeries;
}