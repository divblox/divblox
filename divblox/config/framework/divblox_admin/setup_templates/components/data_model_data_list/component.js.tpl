if (typeof component_classes['[component_full_name]'] === "undefined") {
	class [component_full_name] extends DivbloxDomEntityDataListComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
			this.sub_component_definitions = [];
			// Sub component config end
			this.included_attributes_object =
				[Included-Attributes-Object];
			this.included_relationships_object =
				[Included-Relationships-Object];
			this.constrain_by_array = [ConstrainBy-Array];
			this.initDataListVariables("[EntityName]");
		}
	}
	component_classes['[component_full_name]'] = [component_full_name];
}