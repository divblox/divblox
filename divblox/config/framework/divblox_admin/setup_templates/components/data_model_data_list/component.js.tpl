if (typeof componentClasses['[component_full_name]'] === "undefined") {
	class [component_class_name] extends DivbloxDomEntityDataListComponent {
		constructor(inputs, supportsNative, requiresNative) {
			super(inputs, supportsNative, requiresNative);
			// Sub component config start
			this.subComponentDefinitions = [];
			// Sub component config end
			this.includedAttributes =
				[Included-Attributes-Object];
			this.includedRelationships =
				[Included-Relationships-Object];
			this.constrainedByEntities = [ConstrainBy-Array];
			this.initDataListVariables("[EntityName]");
		}
	}

	componentClasses['[component_full_name]'] = [component_class_name];
}