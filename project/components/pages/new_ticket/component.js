if (typeof component_classes['pages_new_ticket'] === "undefined") {
	class pages_new_ticket extends DivbloxDomBaseComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
            this.sub_component_definitions =
                [{"component_load_path":"navigation/side_navbar","parent_element":"SideNavAnchor","arguments":{"uid":"navigation_side_navbar_1"}},
            {"component_load_path":"data_model/ticket_crud_create","parent_element":"icZfo","arguments":{}}];
            // Sub component config end
		}
		reset(inputs,propagate) {
			setActivePage("new_ticket","New Ticket");
			super.reset(inputs,propagate);
		}
	}
	component_classes['pages_new_ticket'] = pages_new_ticket;
}