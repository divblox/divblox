if (typeof(on_pages_anonymous_landing_page_ready) === "undefined") {
	function on_pages_anonymous_landing_page_ready(load_arguments) {
		// This is required for any component to be registered to the DOM as a divblox component
		this.dom_component_obj = new DivbloxDOMComponent(load_arguments);
		this.handleComponentError = function(ErrorMessage) {
			this.dom_component_obj.handleComponentError(this,ErrorMessage);
		}.bind(this);
		this.handleComponentSuccess = function() {
			this.dom_component_obj.handleComponentSuccess(this);
		}.bind(this);
		this.reset = function(inputs) {
			dxLog("Reset for blank_page not implemented");
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
				case '[event_name]':
				default:
					dxLog("Event triggered: "+event_name+": "+JSON.stringify(parameters_obj));
			}
			// Let's pass the event to all sub components
			this.dom_component_obj.propagateEventTriggered(event_name,parameters_obj);
		}.bind(this);
		// Sub component config start
        this.sub_components =
            {"0":{"component_load_path":"ungrouped/imageviewer","parent_element":"PqCTk","loading_arguments":{"image_path":getRootPath()+"divblox/assets/images/divblox_logo.svg"}}};
        // Sub component config end
		// Custom functions and declarations to be added below
	    
        // RURmz_button Related functionality
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        getComponentElementById(this,"RURmz_btn").on("click", function() {
        	loadPageComponent("login");
        });
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        
	}
}
