if (typeof(on_data_model_account_additional_info_manager_ready) === "undefined") {
	function on_data_model_account_additional_info_manager_ready(load_arguments) {
		// This is required for any component to be registered to the DOM as a divblox component
		this.dom_component_obj = new DivbloxDOMComponent(load_arguments);
		this.handleComponentError = function(ErrorMessage) {
			this.dom_component_obj.handleComponentError(this,ErrorMessage);
		}.bind(this);
		this.handleComponentSuccess = function() {
			this.dom_component_obj.handleComponentSuccess(this);
		}.bind(this);
		this.reset = function(inputs) {
			this.toggleSubView('data_series_wrapper');
		}.bind(this);
		this.on_component_loaded = function() {
			this.dom_component_obj.on_component_loaded(this);
		}.bind(this);
		this.subComponentLoadedCallBack = function(component) {
			// Implement additional required functionality for sub components after load here
			// dxLog("Sub component loaded: "+JSON.stringify(component));
		}.bind(this);
		this.getSubComponents = function() {
			return this.dom_component_obj.getSubComponents(this);
		}.bind(this);
		this.getUid = function() {
			return this.dom_component_obj.getUid();
		}.bind(this);
		// Component specific code below
		// Empty array means ANY user role has access. NB! This is merely for UX purposes.
		// Do not rely on this as a security measure. User role security MUST be managed on the server's side
		this.allowedAccessArray = [];
		this.eventTriggered = function(event_name,parameters_obj) {
			// Handle specific events here. This is useful if the component needs to update because one of its
			// sub-components did something
			switch(event_name) {
				case 'account_additional_info_manager_create_clicked':this.toggleSubView("data_create_wrapper");
					getRegisteredComponent("account_additional_info_manager_create_component").reset();
					break;
				case 'additional_account_information_clicked':this.toggleSubView("data_update_wrapper");
					getRegisteredComponent("account_additional_info_manager_update_component").reset(parameters_obj.id);
					break;
				case 'additional_account_information_created':
				case 'additional_account_information_deleted':
				case 'additional_account_information_updated':
				case 'account_additional_info_manager_back_clicked':this.toggleSubView("data_series_wrapper");
					getRegisteredComponent("account_additional_info_manager_data_series_component").reset();
					break;
				default:
					dxLog("Event triggered: "+event_name+": "+JSON.stringify(parameters_obj));
			}
			// Let's pass the event to all sub components
			this.dom_component_obj.propagateEventTriggered(event_name,parameters_obj);
		}.bind(this);
		// Sub component config start
		this.sub_components =
			{"0":{"component_load_path":"data_model/account_additional_info_manager_data_series","parent_element":"data_series","loading_arguments":{"uid":"account_additional_info_manager_data_series_component"}},
				"1":{"component_load_path":"data_model/account_additional_info_manager_update","parent_element":"data_update","loading_arguments":{"uid":"account_additional_info_manager_update_component"}},
				"2":{"component_load_path":"data_model/account_additional_info_manager_create","parent_element":"data_create","loading_arguments":{"uid":"account_additional_info_manager_create_component"}}};
		// Sub component config end
		// Custom functions and declarations to be added below
		getComponentElementById(this,"button_create").on("click", function() {
			pageEventTriggered("account_additional_info_manager_create_clicked",{});
		});
		getComponentElementById(this,"button_create_back").on("click", function() {
			pageEventTriggered("account_additional_info_manager_back_clicked",{});
		});
		getComponentElementById(this,"button_update_back").on("click", function() {
			pageEventTriggered("account_additional_info_manager_back_clicked",{});
		});
		this.toggleSubView = function(view_element_id) {
			let this_component = this;
			let view_array = ["data_series_wrapper","data_update_wrapper","data_create_wrapper"];
			getComponentElementById(this_component,view_element_id).fadeIn("slow");
			view_array.forEach(function(item) {
				if (item !== view_element_id) {
					getComponentElementById(this_component,item).hide();
				}
			});
		}.bind(this);
	}
}
