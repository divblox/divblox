if (typeof(on_pages_my_profile_ready) === "undefined") {
	function on_pages_my_profile_ready(load_arguments) {
		// This is required for any component to be registered to the DOM as a divblox component
		this.dom_component_obj = new DivbloxDOMComponent(load_arguments);
		this.handleComponentError = function(ErrorMessage) {
			this.dom_component_obj.handleComponentError(this,ErrorMessage);
		}.bind(this);
		this.handleComponentSuccess = function() {
			this.dom_component_obj.handleComponentSuccess(this);
		}.bind(this);
		this.reset = function(inputs) {
			dxLog("Reset for profile page not implemented");
		}.bind(this);
		this.on_component_loaded = function() {
			this.dom_component_obj.on_component_loaded(this);
			setActivePage("profile","My Profile");
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
				case '[event_name]':
				default:
					dxLog("Event triggered: "+event_name+": "+JSON.stringify(parameters_obj));
			}
			// Let's pass the event to all sub components
			this.dom_component_obj.propagateEventTriggered(event_name,parameters_obj);
		}.bind(this);
		// Sub component config start
		this.sub_components =
			{"0":{"component_load_path":"navigation/side_navbar","parent_element":"s8gjT","loading_arguments":{"url_parameters":{"component":"pages/my_profile"},
			"component_name":"navigation_side_navbar","component_load_name":"navigation/side_navbar","parent_element":"#main_page_s8gjT","parent_uid":"main_page","component_path":"http://localhost/divblox_local/project/components/navigation/side_navbar","dom_index":1,"uid":"navigation_side_navbar_1"}},
			"1":{"component_load_path":"data_model/current_user_profile_manager","parent_element":"crXWF","loading_arguments":{}}};
		// Sub component config end
		// Custom functions and declarations to be added below
	}
}
