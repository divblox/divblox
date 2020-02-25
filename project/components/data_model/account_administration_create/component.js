if (typeof component_classes['data_model_account_administration_create'] === "undefined") {
	class data_model_account_administration_create extends DivbloxDomEntityInstanceComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
			this.sub_component_definitions = [];
			// Sub component config end
			this.included_attribute_array = ['FirstName','MiddleNames','LastName','EmailAddress','Username','Password','MaidenName','ProfilePicturePath','MainContactNumber','Title','DateOfBirth','PhysicalAddressLineOne','PhysicalAddressLineTwo','PhysicalAddressPostalCode','PhysicalAddressCountry','PostalAddressLineOne','PostalAddressLineTwo','PostalAddressPostalCode','PostalAddressCountry','IdentificationNumber','Nickname','Status','Gender','AccessBlocked','BlockedReason',];
			this.included_relationship_array = ['UserRole',];
			this.constrain_by_array = [];
			this.data_validation_array = [];
			this.custom_validation_array = [];
			this.required_validation_array = ['FirstName','EmailAddress','Username','UserRole',];
			this.initCrudVariables("Account");
		}
	}
	component_classes['data_model_account_administration_create'] = data_model_account_administration_create;
}