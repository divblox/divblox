if (typeof component_classes['pages_system_account_management'] === "undefined") {
	class pages_system_account_management extends DivbloxDomBaseComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
            this.sub_component_definitions =
                [{"component_load_path":"navigation/side_navbar","parent_element":"SideNavAnchor","arguments":{"uid":"navigation_side_navbar_1"}},
            {"component_load_path":"data_model/account_administration","parent_element":"BD4wR","arguments":{}}];
            // Sub component config end
		}
		reset(inputs,propagate) {
			setActivePage("system_account_management","Account Management");
			super.reset(inputs,propagate);
		}
	}
	component_classes['pages_system_account_management'] = pages_system_account_management;
}