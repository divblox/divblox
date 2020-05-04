if (typeof component_classes['navigation_top_navbar'] === "undefined") {
	class navigation_top_navbar extends DivbloxDomBaseComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
			this.sub_component_definitions = {};
			// Sub component config end
		}
		reset(inputs,propagate) {
			super.reset(inputs,propagate);
			let default_sub_menu_wrapper_template =
				'<li class="nav-item dropdown">\n' +
				'   <a class="nav-link navigation-activate-on-{item_active_class} dropdown-toggle"' +
				' href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
				'{item_label}</a>\n' +
				'<div class="dropdown-menu">\n'+
				'<ul class="nav flex-column">\n'+
				'{sub_menu}'+
				'</ul>\n'+
				'</div>\n' +
				'</li>\n';
			menu_manager.renderMenu('example-top-menu','default_top_nav',undefined,default_sub_menu_wrapper_template);
		}
		registerDomEvents() {
			getComponentElementById(this,'navigation_item_my_profile').on("click", function () {
				loadPageComponent("my_profile");
				return false;
			});
		}
	}
	component_classes['navigation_top_navbar'] = navigation_top_navbar;
}