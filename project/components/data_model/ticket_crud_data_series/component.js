if (typeof component_classes['data_model_ticket_crud_data_series'] === "undefined") {
	class data_model_ticket_crud_data_series extends DivbloxDomEntityDataListComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
			this.sub_component_definitions = [];
			// Sub component config end
			this.included_attributes_object =
				{"TicketName":"Header","TicketDescription":"Normal","DueDate":"Subtle"};
			this.included_relationships_object =
				{"TicketStatus":"Footer"};
			this.constrain_by_array = [];
			this.initDataListVariables("Ticket");
		}
	}
	component_classes['data_model_ticket_crud_data_series'] = data_model_ticket_crud_data_series;
}