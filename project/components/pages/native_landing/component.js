if (typeof componentClasses['pages_native_landing'] === "undefined") {
    class NativeLanding extends DivbloxDomBaseComponent {
        constructor(inputs, supportsNative, requiresNative) {
            super(inputs, supportsNative, requiresNative);
            // Sub component config start
            this.subComponentDefinitions = [{
                "component_load_path": "ungrouped/imageviewer",
                "parent_element": "PqCTk",
                "arguments": {"image_path": getRootPath() + "project/assets/images/divblox_logo.svg"}
            }];
            // Sub component config end
            let current_user_role = getCurrentUserRoleFromAppState();
            if (current_user_role !== null) {
                this.handleUserRolePageLoad(current_user_role);
            } else {
                getCurrentUserRole(function (current_user_role) {
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
            getComponentElementById(this, "RURmz_btn").on("click", function () {
                loadPageComponent("login");
            });
            getComponentElementById(this, "ExternalDemoButton").on("click", function () {
                redirectToExternalPath("https://divblox.com");
            });
        }

        reset(inputs, propagate) {
            setActivePage("", "dx Home");
            super.reset(inputs, propagate);
            alert("URL: " + window.location.href);
        }

        subComponentLoadedCallBack(component) {
            super.subComponentLoadedCallBack(component);
            if (component.getComponentName() === "ungrouped_imageviewer") {
                component.updateImage("project/assets/images/divblox_logo.svg");
            }
        }
    }

    componentClasses['pages_native_landing'] = NativeLanding;
}