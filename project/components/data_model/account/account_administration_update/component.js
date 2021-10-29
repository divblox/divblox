if (typeof componentClasses['data_model_account_account_administration_update'] === "undefined") {
    class AccountAdministrationUpdate extends DivbloxDomEntityInstanceComponent {
        constructor(inputs, supportsNative, requiresNative) {
            super(inputs, supportsNative, requiresNative);
            // Sub component config start
            this.subComponentDefinitions = [];
            // Sub component config end
            this.includedAttributes = ['FirstName', 'MiddleNames', 'LastName', 'EmailAddress', 'Username', 'Password', 'MaidenName', 'ProfilePicturePath', 'MainContactNumber', 'Title', 'DateOfBirth', 'PhysicalAddressLineOne', 'PhysicalAddressLineTwo', 'PhysicalAddressPostalCode', 'PhysicalAddressCountry', 'PostalAddressLineOne', 'PostalAddressLineTwo', 'PostalAddressPostalCode', 'PostalAddressCountry', 'IdentificationNumber', 'Nickname', 'Status', 'Gender', 'AccessBlocked', 'BlockedReason',];
            this.includedRelationships = ['UserRole',];
            this.constrainedByEntities = [];
            this.dataValidations = [];
            this.customValidations = [];
            this.requiredValidations = ['FirstName', 'EmailAddress', 'Username', 'UserRole',];
            this.initCrudVariables("Account");
        }

        reset(inputs, propagate) {
            if (typeof inputs !== "undefined") {
                this.setEntityId(inputs);
            }
            super.reset(inputs, propagate);
        }
    }

    componentClasses['data_model_account_account_administration_update'] = AccountAdministrationUpdate;
}