if (typeof component_classes['pages_ticket_update'] === "undefined") {
	class pages_ticket_update extends DivbloxDomBaseComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
            this.sub_component_definitions = 
                [{"component_load_path":"navigation/side_navbar","parent_element":"s8gjT","arguments":{"uid":"navigation_side_navbar_1"}},
            {"component_load_path":"data_model/ticket_crud_update","parent_element":"Q4Ov1","arguments":{"uid":"ticket_crud_update_1"}}];
            // Sub component config end
		}
		reset(inputs) {
			setActivePage("page_component_name","Page Title");
			super.reset(inputs);
			getRegisteredComponent("ticket_crud_update_1").reset(getGlobalConstrainById("Ticket"));
		}
		
	}
	component_classes['pages_ticket_update'] = pages_ticket_update;
}