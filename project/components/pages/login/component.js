if (typeof component_classes['pages_login'] === "undefined") {
	class pages_login extends DivbloxDomBaseComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
			this.sub_component_definitions = [
				{"component_load_path":"ungrouped/imageviewer","parent_element":"LogoWrapper","arguments":{"image_path":"divblox/assets/images/divblox_logo.svg","uid":"ungrouped_imageviewer_1"}},
				{"component_load_path":"system/authentication","parent_element":"AuthenticationComponent","arguments":{"uid":"system_authentication_1"}}];
			// Sub component config end
		}
		reset(inputs,propagate) {
			setActivePage("login","Login");
			getCurrentUserRole(function(role) {
				if (typeof role !== "undefined") {
					if (role.toLowerCase() !== "dxadmin") {
						if (role.toLowerCase() !== "anonymous") {
							loadUserRoleLandingPage(role);
						}
					}
				}
			}.bind(this));
			super.reset(inputs,propagate);
		}
		registerDomEvents() {
			getComponentElementById(this,"AuthenticationComponent").keypress(function( event ) {
				if (event.which == 13) {
					event.preventDefault();
					getRegisteredComponent("system_authentication_1").processAuthentication();
				}
			}.bind(this));
		}
		eventTriggered(event_name,parameters_obj) {
			// Handle specific events here. This is useful if the component needs to update because one of its
			// sub-components did something
			switch(event_name) {
				case 'authenticated': loadUserRoleLandingPage(parameters_obj.UserRole);
					break;
				default:
					dxLog("Event triggered: "+event_name+": "+JSON.stringify(parameters_obj));
			}
		}
		subComponentLoadedCallBack(component) {
			super.subComponentLoadedCallBack(component);
			if (component.getComponentName() === "ungrouped_imageviewer") {
				component.updateImage("project/assets/images/divblox_logo.svg");
			}
		}
		postPageLoadActions() {
			// We do not want the standard post page load actions here
		}
	}
	component_classes['pages_login'] = pages_login;
}