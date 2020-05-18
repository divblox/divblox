if (typeof component_classes['navigation_generic_navbar'] === "undefined") {
	class navigation_generic_navbar extends DivbloxDomBaseComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
			this.sub_component_definitions = [];
			// Sub component config end
		}
	}
	component_classes['navigation_generic_navbar'] = navigation_generic_navbar;
}