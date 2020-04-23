if (typeof component_classes['pages_native_landing'] === "undefined") {
	class pages_native_landing extends DivbloxDomBaseComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
			this.sub_component_definitions = [{"component_load_path":"ungrouped/imageviewer","parent_element":"PqCTk","arguments":{"image_path":getRootPath()+"project/assets/images/divblox_logo.svg"}}];
			// Sub component config end
			let current_user_role = getCurrentUserRoleFromAppState();
			if (current_user_role !== null) {
				this.handleUserRolePageLoad(current_user_role);
			} else {
				getCurrentUserRole(function(current_user_role) {
					this.handleUserRolePageLoad(current_user_role);
				}.bind(this));
			}
			
		}
		handleUserRolePageLoad(current_user_role) {
			if (current_user_role.toLowerCase() !== 'anonymous') {
				loadUserRoleLandingPage(current_user_role);
			}
		}
		registerDomEvents() {
			getComponentElementById(this,"RURmz_btn").on("click", function() {
				loadPageComponent("login");
			});
		}
		reset(inputs) {
			setActivePage("","dx Home");
			super.reset(inputs);
		}
		subComponentLoadedCallBack(component) {
            super.subComponentLoadedCallBack(component);
            if (component.getComponentName() === "ungrouped_imageviewer") {
                component.updateImage("project/assets/images/divblox_logo.svg");
            }
        }
   	}
	component_classes['pages_native_landing'] = pages_native_landing;
}