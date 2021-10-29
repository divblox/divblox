if (typeof componentClasses['data_model_account_current_user_profile_manager'] === "undefined") {
    class CurrentUserProfileManager extends DivbloxDomEntityInstanceComponent {
        constructor(inputs, supportsNative, requiresNative) {
            super(inputs, supportsNative, requiresNative);
            // Sub component config start
            this.subComponentDefinitions = [
                {
                    "component_load_path": "system/profile_picture_uploader",
                    "parent_element": "jY5DR",
                    "loading_arguments": {"uid": "system_profile_picture_uploader_1"}
                },
                {
                    "component_load_path": "data_model/account/account_additional_info_manager",
                    "parent_element": "DOCwu",
                    "loading_arguments": {}
                }];
            // Sub component config end
            this.includedAttributes = ["FirstName", "MiddleNames", "LastName", "EmailAddress", "Username", "Password", "MaidenName", "ProfilePicturePath", "MainContactNumber", "Title", "DateOfBirth", "PhysicalAddressLineOne", "PhysicalAddressLineTwo", "PhysicalAddressPostalCode", "PhysicalAddressCountry", "PostalAddressLineOne", "PostalAddressLineTwo", "PostalAddressPostalCode", "PostalAddressCountry", "IdentificationNumber", "Nickname", "Status", "Gender", "AccessBlocked", "BlockedReason"];
            this.includedRelationships = [];
            this.constrainedByEntities = [];

            this.dataValidations = [];
            this.customValidations = [];
            this.requiredValidations = ['EmailAddress', 'Username',].concat(this.dataValidations).concat(this.customValidations);
            this.initCrudVariables("Account");
        }

        reset(inputs, propagate) {
            getCurrentUserAttribute("Id", function (attribute) {
                if (attribute == null) {
                    loadPageComponent("login");
                } else if (attribute < 1) {
                    loadPageComponent("login");
                } else {
                    this.setEntityId(attribute);
                    this.loadEntity();
                }
            }.bind(this));
            super.reset(inputs, propagate);
        }

        registerDomEvents() {
            super.registerDomEvents();
            getComponentElementById(this, "btnLogout").on("click", function () {
                logout();
            });
        }

        initCustomFunctions() {
            super.initCustomFunctions();
            getComponentElementById(this, 'ProfilePictureModal').on('hidden.bs.modal', function (e) {
                setTimeout(function () {
                    loadCurrentUserProfilePicture(function (path) {
                        getComponentElementById(this, "ProfilePictureRender").attr("src", path);
                    }.bind(this));
                }.bind(this), 1000);
            }.bind(this));
        }
    }

    componentClasses['data_model_account_current_user_profile_manager'] = CurrentUserProfileManager;
}