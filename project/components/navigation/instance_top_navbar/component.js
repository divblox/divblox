if (typeof componentClasses['navigation_instance_top_navbar'] === "undefined") {
    class InstanceTopNavbar extends DivbloxDomBaseComponent {
        constructor(inputs, supportsNative, requiresNative) {
            super(inputs, supportsNative, requiresNative);
            // Sub component config start
            this.subComponentDefinitions = {};
            // Sub component config end
        }

        registerDomEvents() {
            getComponentElementById(this, 'InstanceTopNavCancel').on("click", function () {
                this.cancelFunction();
                return false;
            }.bind(this));
            getComponentElementById(this, 'InstanceTopNavConfirm').on("click", function () {
                this.confirmFunction();
                return false;
            }.bind(this));
        }

        cancelFunction() {
            dxLog("No cancel function provided");
        }

        confirmFunction() {
            dxLog("No confirm function provided");
        }

        setConfirmFunction(confirm_function, confirm_button_text, hide) {
            if (typeof confirm_button_text === "undefined") {
                confirm_button_text = 'Confirm';
            }
            getComponentElementById(this, 'InstanceTopNavConfirm').html(confirm_button_text);
            if (typeof hide === "undefined") {
                hide = false;
            }
            if (typeof confirm_function !== "undefined") {
                this.confirmFunction = confirm_function;
            }
            if (hide) {
                getComponentElementById(this, 'InstanceTopNavConfirm').hide();
            }
        }

        setCancelFunction(cancel_function, cancel_button_text, hide) {
            if (typeof cancel_button_text === "undefined") {
                cancel_button_text = '<i class="fa fa-angle-left instance-navbar-back-icon"></i> Back';
            }
            getComponentElementById(this, 'InstanceTopNavCancel').html(cancel_button_text);
            if (typeof hide === "undefined") {
                hide = false;
            }
            if (typeof cancel_function !== "undefined") {
                this.cancelFunction = cancel_function;
            }
            if (hide) {
                getComponentElementById(this, 'InstanceTopNavCancel').hide();
            }
        }
    }

    componentClasses['navigation_instance_top_navbar'] = InstanceTopNavbar;
}