if (typeof componentClasses['pages_anonymous_landing_page'] === "undefined") {
    class AnonymousLandingPage extends DivbloxDomBaseComponent {
        constructor(inputs, supportsNative, requiresNative) {
            super(inputs, supportsNative, requiresNative);
            // Sub component config start
            this.subComponentDefinitions =
                [{
                    "component_load_path": "ungrouped/imageviewer",
                    "parent_element": "PqCTk",
                    "arguments": {
                        "image_path": "project/assets/images/divblox_logo.svg",
                        "uid": "ungrouped_imageviewer_1"
                    }
                }];
            // Sub component config end
        }

        registerDomEvents() {
            getComponentElementById(this, "RURmz_btn").on("click", function () {
                loadPageComponent("login");
            });
        }

        reset(inputs, propagate) {
            setActivePage("", "dx Home");
            super.reset(inputs, propagate);

            dxRequestInternal(getComponentControllerPath(this),
                {
                    f: "loadAnonymous",
                }, function (data) {

                }, function (data) {
                    dxLog("Error occurred: " + data.Message);
                }
            );
        }

        subComponentLoadedCallBack(component) {
            super.subComponentLoadedCallBack(component);
            if (component.getComponentName() === "ungrouped_imageviewer") {
                component.updateImage("project/assets/images/divblox_logo.svg");
            }
        }
    }

    componentClasses['pages_anonymous_landing_page'] = AnonymousLandingPage;
}