if (typeof componentClasses['system_authentication'] === "undefined") {
    class Authentication extends DivbloxDomBaseComponent {
        constructor(inputs, supportsNative, requiresNative) {
            super(inputs, supportsNative, requiresNative);
            // Sub component config start
            this.subComponentDefinitions = [];
            // Sub component config end
        }

        reset(inputs, propagate) {
            getComponentElementById(this, 'InputUsername').focus();
            super.reset(inputs, propagate);
        }

        registerDomEvents() {
            getComponentElementById(this, "btnProcessAuthentication").on("click", function () {
                this.processAuthentication();
            }.bind(this));
        }

        processAuthentication() {
            dxRequestInternal(getComponentControllerPath(this), {
                    f: "doAuthentication",
                    Username: getComponentElementById(this, "InputUsername").val(),
                    Password: getComponentElementById(this, "InputPassword").val()
                },
                function (data) {
                    setGlobalConstrainById("Account", data.AccountId);
                    registerUserRole(data.UserRole);
                    pageEventTriggered("authenticated", data);
                }.bind(this),
                function (data) {
                    showAlert("Authentication failed! Please try again", "error");
                    pageEventTriggered("authentication_failed", data);
                }.bind(this), false, getComponentElementById(this, "btnProcessAuthentication"), "Authenticating");
        }

        initCustomFunctions() {

            // tLWyB_button Related functionality
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            getComponentElementById(this, "btnGoToRegister").on("click", function () {
                loadPageComponent('register');
            }.bind(this));
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        }
    }

    componentClasses['system_authentication'] = Authentication;
}