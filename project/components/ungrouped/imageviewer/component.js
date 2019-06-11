if (typeof(on_ungrouped_imageviewer_ready) === "undefined") {
	function on_ungrouped_imageviewer_ready(load_arguments) {
		// This is required for any component to be registered to the DOM as a divblox component
		this.dom_component_obj = new DivbloxDOMComponent(load_arguments);
		this.handleComponentError = function(ErrorMessage) {
			this.dom_component_obj.handleComponentError(this,ErrorMessage);
		}.bind(this);
		this.handleComponentSuccess = function() {
			this.dom_component_obj.handleComponentSuccess(this);
		}.bind(this);
		this.reset = function(inputs) {
			let this_component = this;
			if (typeof(this_component.getImagePath()) !== "undefined") {
				$("#"+this_component.getUid()+"_image").attr("src",this_component.getImagePath());
			}
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
		this.sub_components = {};
		// Sub component config end
		// Custom functions and declarations to be added below
		this.getImagePath = function() {
			return this.dom_component_obj.arguments["image_path"];
		}.bind(this);
		this.setImagePath = function(image_path) {
			if (typeof image_path === "undefined") {
				this.dom_component_obj.arguments["image_path"] = 'divblox/assets/images/no_image.svg';
			} else {
				this.dom_component_obj.arguments["image_path"] = image_path;
			}
		}.bind(this);
		this.updateImage = function(image_path) {
			this.setImagePath(image_path);
			let this_component = this;
			if (typeof(this_component.getImagePath()) !== "undefined") {
				$("#"+this_component.getUid()+"_image").attr("src",this_component.getImagePath());
			}
		}.bind(this);
	}
}
