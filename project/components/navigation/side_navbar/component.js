if (typeof component_classes['navigation_side_navbar'] === "undefined") {
	class navigation_side_navbar extends DivbloxDomBaseComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
			this.sub_component_definitions = {};
			// Sub component config end
		}
		reset(inputs) {
			if (isMobile()) {
				$(".component-wrapper-compact-sidebar").removeClass("sidebar_compact_left_visible");
				$(".sidebar-left").css("margin-left","-100px").addClass("slide-left");
			}
			super.reset(inputs);
		}
		registerDomEvents() {
			$(".sidebar_toggle_left").on("click", function() {
				let content_wrapper = $(".component-wrapper-compact-sidebar");
				if (content_wrapper.hasClass("sidebar_compact_left_visible")) {
					$(".sidebar-left").removeClass("slide-left").removeClass("slide-right").addClass("slide-left").css("margin-left","0px");
					$(".component-wrapper-compact-sidebar").removeClass("sidebar_compact_left_visible");
				} else {
					$(".sidebar-left").removeClass("slide-left").removeClass("slide-right").css("margin-left","-100px").addClass("slide-right");
					$(".component-wrapper-compact-sidebar").addClass("sidebar_compact_left_visible");
				}
			});
			getComponentElementById(this,'navigation_item_my_profile').on("click", function () {
				loadPageComponent("my_profile");
				return false;
			});
			getComponentElementById(this,'navigation_item_admin').on("click", function () {
				loadPageComponent("admin");
				return false;
			});
			getComponentElementById(this,'navigation_item_new_ticket').on("click", function () {
				loadPageComponent("new_ticket");
				return false;
			});
			getComponentElementById(this,'navigation_item_dashboard').on("click", function () {
				loadPageComponent("dashboard");
				return false;
			});
			registerEventHandler('.sidebar_toggle_left',"click");
		}
	}
	component_classes['navigation_side_navbar'] = navigation_side_navbar;
}