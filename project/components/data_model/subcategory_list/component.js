if (typeof component_classes['data_model_subcategory_list'] === "undefined") {
	class data_model_subcategory_list extends DivbloxDomEntityDataListComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
            this.sub_component_definitions = 
                [{"component_load_path":"data_model/category_crud_create","parent_element":"4XscR","arguments":{}}];
            // Sub component config end
			this.included_attributes_object =
				{"CategoryLabel":"Normal","TicketCount":"Normal"};
			this.included_relationships_object =
				[];
			this.constrain_by_array = ["Category"];
			this.initDataListVariables("Category");
		}

		// Overrride on_clicked function to do itslef and reload the current page ( with new ConstrainById)
		on_item_clicked(id) {
			super.on_item_clicked(id);
			loadPageComponent("category_update");
		}
	    initCustomFunctions() {
            
            // IfZRa_modal Related functionality
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            getComponentElementById(this,"IfZRa_btn-close").on("click", function() {
                // Your custom code here
            }.bind(this));
            
            // Modal functions
            // Show the modal using javascript
            //getComponentElementById(this,"IfZRa_modal").modal("show");
            // Hide the modal using javascript
            //getComponentElementById(this,"IfZRa_modal").modal("hide");
            // Toggle the modal using javascript
            //getComponentElementById(this,"IfZRa_modal").modal("toggle");
            
            // Modal events
            getComponentElementById(this,"IfZRa_modal").on("show.bs.modal", function(e) {
                // Your custom code here
            }.bind(this));
            getComponentElementById(this,"IfZRa_modal").on("shown.bs.modal", function(e) {
                // Your custom code here
            }.bind(this));
            getComponentElementById(this,"IfZRa_modal").on("hide.bs.modal", function(e) {
               // Your custom code here
            }.bind(this));
            getComponentElementById(this,"IfZRa_modal").on("hidden.bs.modal", function(e) {
                // Your custom code here
            }.bind(this));
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            
        }

		eventTriggered(event_name,parameters_obj) {
			switch(event_name) {
				case 'category_created':
					getComponentElementById(this,"IfZRa_modal").modal("hide");
					this.reset();
					break;
				default:
					dxLog("Event triggered: "+event_name+": "+JSON.stringify(parameters_obj));
			}
			// Let's pass the event to all sub components
			this.propagateEventTriggered(event_name,parameters_obj);
		}
   	}
	component_classes['data_model_subcategory_list'] = data_model_subcategory_list;
}