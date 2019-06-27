if (typeof component_classes['navigation_bottom_navbar'] === "undefined") {
	class navigation_bottom_navbar extends DivbloxDomBaseComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
			this.sub_component_definitions = {};
			// Sub component config end
		}
		registerDomEvents() {
			getComponentElementById(this,'navigation_item_my_profile').on("click", function () {
				loadPageComponent("my_profile");
				return false;
			});
		}
	}
	component_classes['navigation_bottom_navbar'] = navigation_bottom_navbar;
}