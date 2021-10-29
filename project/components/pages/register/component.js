if (typeof componentClasses['pages_register'] === "undefined") {
    class Register extends DivbloxDomBaseComponent {
        constructor(inputs, supportsNative, requiresNative) {
            super(inputs, supportsNative, requiresNative);
            // Sub component config start
            this.subComponentDefinitions = [
                {
                    "component_load_path": "ungrouped/imageviewer",
                    "parent_element": "LogoWrapper",
                    "arguments": {"image_path": "project/assets/images/divblox_logo.svg"}
                },
                {
                    "component_load_path": "system/account_registration",
                    "parent_element": "RegistrationComponent",
                    "arguments": {"uid": "system_account_registration_1"}
                }];
            // Sub component config end
        }

        reset(inputs, propagate) {
            setActivePage("", "Register Account");
            getCurrentUserRole(function (role) {
                if (typeof role !== "undefined") {
                    if (role.toLowerCase() !== "dxadmin") {
                        if (role.toLowerCase() !== "anonymous") {
                            loadUserRoleLandingPage(role);
                        }
                    }
                }
            }.bind(this));
            super.reset(inputs, propagate);
            $('body').addClass('grey-area');
        }

        eventTriggered(event_name, parameters_obj) {
            // Handle specific events here. This is useful if the component needs to update because one of its
            // sub-components did something
            switch (event_name) {
                case 'account_registered':
                    loadUserRoleLandingPage(getCurrentUserRoleFromAppState());
                    break;
                default:
                    dxLog("Event triggered: " + event_name + ": " + JSON.stringify(parameters_obj));
            }
        }

        registerDomEvents() {
            getComponentElementById(this, "RegistrationComponent").keypress(function (event) {
                if (event.which == 13) {
                    event.preventDefault();
                    getRegisteredComponent("system_account_registration_1").saveAccount();
                }
            });
        }

        subComponentLoadedCallBack(component) {
            super.subComponentLoadedCallBack(component);
            if (component.getComponentName() === "ungrouped_imageviewer") {
                component.updateImage("project/assets/images/app_logo.png");
            }
        }
    }

    componentClasses['pages_register'] = Register;
}