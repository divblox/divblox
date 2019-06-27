if (typeof component_classes['pages_blank_page'] === "undefined") {
	class pages_blank_page extends DivbloxDomBaseComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
			this.sub_component_definitions = {};
			// Sub component config end
		}
		reset(inputs) {
			setActivePage("page_component_name","Page Title");
		}
	}
	component_classes['pages_blank_page'] = pages_blank_page;
}