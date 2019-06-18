if (typeof(on_pages_register_ready) === "undefined") {
	function on_pages_register_ready(load_arguments) {
		// This is required for any component to be registered to the DOM as a divblox component
		this.dom_component_obj = new DivbloxDOMComponent(load_arguments);
		this.handleComponentError = function(ErrorMessage) {
			this.dom_component_obj.handleComponentError(this,ErrorMessage);
		}.bind(this);
		this.handleComponentSuccess = function() {
			this.dom_component_obj.handleComponentSuccess(this);
		}.bind(this);
		this.reset = function(inputs) {}.bind(this);
		this.on_component_loaded = function() {
			let this_component = this;
			getCurrentUserRole(function(role) {
				if (typeof role !== "undefined") {
					if (role.toLowerCase() !== "dxadmin") {
						if (role.toLowerCase() !== "anonymous") {
							loadUserRoleLandingPage(role);
							return;
						}
					}
				}
				this_component.dom_component_obj.on_component_loaded(this_component);
			});
			setActivePage("","Register Account");
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
				case 'account_registered': loadPageComponent('login');
					break;
				default:
					dxLog("Event triggered: "+event_name+": "+JSON.stringify(parameters_obj));
			}
		}.bind(this);
		// Sub component config start
        this.sub_components =
            {"0":{"component_load_path":"ungrouped/imageviewer","parent_element":"LogoWrapper","loading_arguments":{"image_path":"http://localhost/divblox_local/divblox/assets/images/divblox_logo.svg","url_parameters":{"component":"pages/register"},
            "component_name":"ungrouped_imageviewer","component_load_name":"ungrouped/imageviewer","parent_element":"#main_page_LogoWrapper","parent_uid":"main_page","component_path":"http://localhost/divblox_local/project/components/ungrouped/imageviewer","dom_index":1,"uid":"ungrouped_imageviewer_1"}},
            "1":{"component_load_path":"system/account_registration","parent_element":"RegistrationComponent","loading_arguments":{"uid":"system_account_registration_1"}}};
        // Sub component config end
		// Custom functions and declarations to be added below
		getComponentElementById(this,"RegistrationComponent").keypress(function( event ) {
			if (event.which == 13) {
				event.preventDefault();
				getRegisteredComponent("system_account_registration_1").saveAccount();
			}
		});
	}
}