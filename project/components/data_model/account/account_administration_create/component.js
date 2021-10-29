if (typeof componentClasses['data_model_account_account_administration_create'] === "undefined") {
    class AccountAdministrationCreate extends DivbloxDomEntityInstanceComponent {
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
    }

    componentClasses['data_model_account_account_administration_create'] = AccountAdministrationCreate;
}