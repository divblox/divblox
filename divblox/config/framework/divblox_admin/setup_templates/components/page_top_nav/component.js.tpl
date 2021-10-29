if (typeof componentClasses['[component_full_name]'] === "undefined") {
	class [component_class_name] extends DivbloxDomBaseComponent {
		constructor(inputs, supportsNative, requiresNative) {
			super(inputs, supportsNative, requiresNative);
			// Sub component config start
			this.subComponentDefinitions = [{"component_load_path":"[nav_component_name]","parent_element":"TopNavAnchor","arguments":{}}];
			// Sub component config end
		}

		reset(inputs,propagate) {
			setActivePage("[component_name]","[page_title]");
			super.reset(inputs,propagate);
		}
	}

	componentClasses['[component_full_name]'] = [component_class_name];
}