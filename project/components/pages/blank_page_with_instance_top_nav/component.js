if (typeof component_classes['pages_blank_page_with_instance_top_nav'] === "undefined") {
	class pages_blank_page_with_instance_top_nav extends DivbloxDomBaseComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
			this.sub_component_definitions = [{"component_load_path":"navigation/instance_top_navbar","parent_element":"Vb2NB","arguments":{"uid":"navigation_instance_top_navbar_1"}}];
			// Sub component config end
		}
		subComponentLoadedCallBack(component) {
			super.subComponentLoadedCallBack(component);
			if (component.getUid() === 'navigation_instance_top_navbar_1') {
				component.setCancelFunction(function() {
					dxLog("Cancel called");
				});
				component.setConfirmFunction(function() {
					dxLog("Confirm called");
				});
			}
		}
		reset(inputs) {
			setActivePage("page_component_name","Page Title");
			super.reset(inputs);
		}
	}
	component_classes['pages_blank_page_with_instance_top_nav'] = pages_blank_page_with_instance_top_nav;
}