if (typeof componentClasses['[component_full_name]'] === "undefined") {
	class [component_class_name] extends DivbloxDomBaseComponent {
		constructor(inputs, supportsNative, requiresNative) {
			super(inputs, supportsNative, requiresNative);
			// Sub component config start
			this.subComponentDefinitions = [];
			// Sub component config end
		}
	}

	componentClasses['[component_full_name]'] = [component_class_name];
}