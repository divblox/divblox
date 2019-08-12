if (typeof component_classes['pages_anonymous_landing_page'] === "undefined") {
	class pages_anonymous_landing_page extends DivbloxDomBaseComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
			this.sub_component_definitions = [{"component_load_path":"ungrouped/imageviewer","parent_element":"PqCTk","arguments":{"image_path":getRootPath()+"project/assets/images/divblox_logo.svg"}}];
			// Sub component config end
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
                component.updateImage("project/assets/images/app_logo.png");
            }
        }
	    initCustomFunctions() {
	        // L3Oms_button Related functionality
	        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	        getComponentElementById(this,"L3Oms_btn").on("click", function() {
	            loadPageComponent("register");
	        }.bind(this));
	        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        }
   	}
	component_classes['pages_anonymous_landing_page'] = pages_anonymous_landing_page;
}