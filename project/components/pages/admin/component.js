if (typeof component_classes['pages_admin'] === "undefined") {
	class pages_admin extends DivbloxDomBaseComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
            this.sub_component_definitions = 
                [{"component_load_path":"navigation/side_navbar","parent_element":"s8gjT","arguments":{"uid":"navigation_side_navbar_1"}},
            {"component_load_path":"data_model/category_crud","parent_element":"cwRx9","arguments":{"uid":"data_model_category_crud_1"}},
            {"component_load_path":"data_model/ticket_crud","parent_element":"DE6wF","arguments":{}}];
            // Sub component config end
		}
		reset(inputs) {
			setActivePage("admin","Admin");
			super.reset(inputs);
		}
	}
	component_classes['pages_admin'] = pages_admin;
}