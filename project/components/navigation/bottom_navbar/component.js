if (typeof componentClasses['navigation_bottom_navbar'] === "undefined") {
    class BottomNavbar extends DivbloxDomBaseComponent {
        constructor(inputs, supportsNative, requiresNative) {
            super(inputs, supportsNative, requiresNative);
            // Sub component config start
            this.subComponentDefinitions = {};
            // Sub component config end
        }

        registerDomEvents() {
            getComponentElementById(this, 'navigation_item_my_profile').on("click", function () {
                loadPageComponent("my_profile");
                return false;
            });
        }
    }

    componentClasses['navigation_bottom_navbar'] = BottomNavbar;
}