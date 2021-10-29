if (typeof componentClasses['pages_my_profile'] === "undefined") {
    class MyProfile extends DivbloxDomBaseComponent {
        constructor(inputs, supportsNative, requiresNative) {
            super(inputs, supportsNative, requiresNative);
            // Sub component config start
            this.subComponentDefinitions = [
                {
                    "component_load_path": "navigation/side_navbar",
                    "parent_element": "s8gjT",
                    "arguments": {"uid": "navigation_side_navbar_1"}
                },
                {
                    "component_load_path": "data_model/account/current_user_profile_manager",
                    "parent_element": "crXWF",
                    "arguments": {}
                }];
            // Sub component config end
        }

        reset(inputs, propagate) {
            setActivePage("profile", "My Profile");
            super.reset(inputs, propagate);
        }
    }

    componentClasses['pages_my_profile'] = MyProfile;
}