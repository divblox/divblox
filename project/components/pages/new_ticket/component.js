if (typeof component_classes['pages_new_ticket'] === "undefined") {
	class pages_new_ticket extends DivbloxDomBaseComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
            this.sub_component_definitions = 
                [{"component_load_path":"navigation/side_navbar","parent_element":"s8gjT","arguments":{"uid":"navigation_side_navbar_1"}},
            {"component_load_path":"data_model/ticket_crud_create","parent_element":"89IYc","arguments":{}}];
            // Sub component config end
		}
		reset(inputs) {
			setActivePage("new_ticket","New Ticket");
			super.reset(inputs);
		}

		eventTriggered(event_name, parameters_obj) {
			switch (event_name) {
				case 'ticket_created':
					loadPageComponent("ticket_update");
					break;
				default:
					dxLog("Event triggered: " + event_name + ": " + JSON.stringify(parameters_obj));
			}
			// Let's pass the event to all sub components
			this.propagateEventTriggered(event_name, parameters_obj);
		}
	}
	component_classes['pages_new_ticket'] = pages_new_ticket;
}