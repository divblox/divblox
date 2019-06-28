if (typeof component_classes['pages_my_profile'] === "undefined") {
	class pages_my_profile extends DivbloxDomBaseComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
			this.sub_component_definitions = [
				{"component_load_path":"navigation/side_navbar","parent_element":"s8gjT","arguments":{"uid":"navigation_side_navbar_1"}},
				{"component_load_path":"data_model/current_user_profile_manager","parent_element":"crXWF","arguments":{}}];
			// Sub component config end
		}
		reset(inputs) {
			setActivePage("profile","My Profile");
			super.reset(inputs);
		}
	}
	component_classes['pages_my_profile'] = pages_my_profile;
}