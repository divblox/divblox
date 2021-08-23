if (typeof component_classes['[component_full_name]'] === "undefined") {
	class [component_class_name] extends DivbloxDomEntityDataTableComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
			this.sub_component_definitions = [];
			// Sub component config end
			this.included_attribute_array = [Included-Attribute-Array];
			this.included_relationship_array = [Included-Relationship-Array];
			this.constrain_by_array = [ConstrainBy-Array];
			this.initDataTableVariables("[EntityName]");
		}
	}
	component_classes['[component_full_name]'] = [component_class_name];
}