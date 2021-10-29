if (typeof componentClasses['[component_full_name]'] === "undefined") {
	class [component_class_name] extends DivbloxDomEntityInstanceComponent {
		constructor(inputs, supportsNative, requiresNative) {
			super(inputs, supportsNative, requiresNative);
			// Sub component config start
			this.subComponentDefinitions = [];
			// Sub component config end
			this.includedAttributes = [Included-Attribute-Array];
			this.includedRelationships = [Included-Relationship-Array];
			this.constrainedByEntities = [ConstrainBy-Array];
			this.dataValidations = [Data-Validation-Array];
			this.customValidations = [Custom-Validation-Array];
			this.requiredValidations = [Required-Validation-Array];
			this.initCrudVariables("[EntityName]");
		}
	}

	componentClasses['[component_full_name]'] = [component_class_name];
}