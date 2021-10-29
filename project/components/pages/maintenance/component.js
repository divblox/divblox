if (typeof componentClasses['pages_maintenance'] === "undefined") {
    class Maintenance extends DivbloxDomBaseComponent {
        constructor(inputs, supportsNative, requiresNative) {
            super(inputs, supportsNative, requiresNative);
            // Sub component config start
            this.subComponentDefinitions =
                [{
                    "component_load_path": "ungrouped/imageviewer",
                    "parent_element": "imageViewerContainer",
                    "arguments": {
                        "image_path": "divblox/assets/images/divblox_logo.svg",
                        "uid": "ungrouped_imageviewer_1"
                    }
                }];
            // Sub component config end
        }

        reset(inputs, propagate) {
            setActivePage("maintenance", "Maintenance");
            super.reset(inputs, propagate);
            $('body').addClass('grey-area');
        }

        registerDomEvents() {
            getComponentElementById(this, "btnRefresh").on("click", function () {
                loadUserRoleLandingPage(getCurrentUserRoleFromAppState());
            });
        }
    }

    componentClasses['pages_maintenance'] = Maintenance;
}