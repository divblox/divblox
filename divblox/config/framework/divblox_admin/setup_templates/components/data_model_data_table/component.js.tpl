if (typeof componentClasses['[component_full_name]'] === "undefined") {
	class [component_class_name] extends DivbloxDomEntityDataTableComponent {
		constructor(inputs, supportsNative, requiresNative) {
			super(inputs, supportsNative, requiresNative);
			// Sub component config start
			this.subComponentDefinitions = [];
			// Sub component config end
			this.includedAttributes = [Included-Attribute-Array];
			this.includedRelationships = [Included-Relationship-Array];
			this.constrainedByEntities = [ConstrainBy-Array];
			this.initDataTableVariables("[EntityName]");
		}
	}

	componentClasses['[component_full_name]'] = [component_class_name];
}