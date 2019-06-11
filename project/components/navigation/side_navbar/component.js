if (typeof(on_navigation_side_navbar_ready) === "undefined") {
	function on_navigation_side_navbar_ready(load_arguments) {
		this.dom_component_obj = new DivbloxDOMComponent(load_arguments);
		this.handleComponentError = function(ErrorMessage) {
			this.dom_component_obj.handleComponentError(this,ErrorMessage);
		}.bind(this);
		this.handleComponentSuccess = function() {
			this.dom_component_obj.handleComponentSuccess(this);
		}.bind(this);
		this.reset = function(inputs) {
			loadCurrentUserProfilePicture();
		}.bind(this);
		this.on_component_loaded = function() {
			this.dom_component_obj.on_component_loaded(this);
			if (isMobile()) {
				$(".component-wrapper-compact-sidebar").removeClass("sidebar_compact_left_visible");
				$(".sidebar-left").css("margin-left","-100px");
				$(".sidebar-left").addClass("slide-left");
			}
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
		$(".sidebar_toggle_left").on("click", function() {
			let content_wrapper = $(".component-wrapper-compact-sidebar");
			if (content_wrapper.hasClass("sidebar_compact_left_visible")) {
				$(".sidebar-left").removeClass("slide-left").removeClass("slide-right");
				$(".sidebar-left").addClass("slide-left");
				$(".sidebar-left").css("margin-left","0px");
				$(".component-wrapper-compact-sidebar").removeClass("sidebar_compact_left_visible");
			} else {
				$(".sidebar-left").removeClass("slide-left").removeClass("slide-right");
				$(".sidebar-left").css("margin-left","-100px");
				$(".sidebar-left").addClass("slide-right");
				$(".component-wrapper-compact-sidebar").addClass("sidebar_compact_left_visible");
			}
		});
		getComponentElementById(this,'navigation_item_my_profile').on("click", function () {
			loadPageComponent("my_profile");
			return false;
		});
	}
}
