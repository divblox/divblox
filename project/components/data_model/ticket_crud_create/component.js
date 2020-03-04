if (typeof component_classes['data_model_ticket_crud_create'] === "undefined") {
	class data_model_ticket_crud_create extends DivbloxDomEntityInstanceComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
			this.sub_component_definitions = [];
			// Sub component config end
			this.included_attribute_array = ['TicketName','TicketDescription','DueDate','TicketUniqueId',];
			this.included_relationship_array = ['TicketStatus',];
			this.constrain_by_array = [];
			this.data_validation_array = [];
			this.custom_validation_array = [];
			this.required_validation_array = ['TicketName','TicketDescription','DueDate','TicketUniqueId','TicketStatus',];
			this.initCrudVariables("Ticket");
		}
	    initCustomFunctions() {
            
            // 1sACl_button Related functionality
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            getComponentElementById(this,"1sACl_btn").on("click", function() {
                // dxRequestInternal() is the global function used to communicate
                // from the component's JavaScript to its back-end php component
                dxRequestInternal(
                    // The first parameter tells the function where to send the request
                    // getComponentControllerPath(this) returns the path to current component's php script
                    getComponentControllerPath(this),
                    // Tell component.php which function to execute
                    { f: "getNewTaskUniqueId" },
                    function(data_obj) {
                        // Success function
                        getComponentElementById(this, "TicketUniqueId").val(data_obj.TaskId);
                    }.bind(this),
                    function(data_obj) {
                        // Fail function
                    }.bind(this)
                );
            }.bind(this));
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            
        }
   	}
	component_classes['data_model_ticket_crud_create'] = data_model_ticket_crud_create;
}