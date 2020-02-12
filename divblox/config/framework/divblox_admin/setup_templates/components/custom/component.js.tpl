if (typeof component_classes['[component_full_name]'] === "undefined") {
	class [component_full_name] extends DivbloxDomBaseComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
			this.sub_component_definitions = [];
			// Sub component config end
		}
	}
	component_classes['[component_full_name]'] = [component_full_name];
}