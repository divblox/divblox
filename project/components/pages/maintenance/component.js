if (typeof component_classes['pages_maintenance'] === "undefined") {
    class pages_maintenance extends DivbloxDomBaseComponent {
        constructor(inputs, supports_native, requires_native) {
            super(inputs, supports_native, requires_native);
            // Sub component config start
            this.sub_component_definitions =
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
            getComponentElementById(this, "btnRefresh").on("click", function() {
                loadUserRoleLandingPage(getCurrentUserRoleFromAppState());
            });
        }
    }

    component_classes['pages_maintenance'] = pages_maintenance;
}