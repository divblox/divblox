if (typeof componentClasses['pages_system_account_management'] === "undefined") {
    class SystemAccountManagement extends DivbloxDomBaseComponent {
        constructor(inputs, supportsNative, requiresNative) {
            super(inputs, supportsNative, requiresNative);
            // Sub component config start
            this.subComponentDefinitions =
                [{
                    "component_load_path": "navigation/side_navbar",
                    "parent_element": "SideNavAnchor",
                    "arguments": {"uid": "navigation_side_navbar_1"}
                },
                    {
                        "component_load_path": "data_model/account/account_administration",
                        "parent_element": "BD4wR",
                        "arguments": {"uid": "data_model_account_account_administration_1"}
                    }];
            // Sub component config end
        }

        reset(inputs, propagate) {
            setActivePage("system_account_management", "Account Management");
            super.reset(inputs, propagate);
        }
    }

    componentClasses['pages_system_account_management'] = SystemAccountManagement;
}