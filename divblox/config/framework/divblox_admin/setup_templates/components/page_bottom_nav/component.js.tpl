if (typeof component_classes['[component_full_name]'] === "undefined") {
	class [component_class_name] extends DivbloxDomBaseComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
			this.sub_component_definitions = [{"component_load_path":"[nav_component_name]","parent_element":"BottomNavAnchor","arguments":{}}];
			// Sub component config end
		}
		reset(inputs,propagate) {
			setActivePage("[component_name]","[page_title]");
			super.reset(inputs,propagate);
		}
	}
	component_classes['[component_full_name]'] = [component_class_name];
}