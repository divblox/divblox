if (typeof componentClasses['system_account_registration'] === "undefined") {
    class AccountRegistration extends DivbloxDomBaseComponent {
        constructor(inputs, supportsNative, requiresNative) {
            super(inputs, supportsNative, requiresNative);
            // Sub component config start
            this.subComponentDefinitions = [];
            // Sub component config end
            this.dataValidations = [];
            this.customValidations = ['EmailAddress'];
            this.requiredValidations = ['FirstName', 'LastName', 'Password'].concat(this.dataValidations).concat(this.customValidations);
        }

        reset(inputs, propagate) {
            this.loadAccount();
            super.reset(inputs, propagate);
        }

        registerDomEvents() {
            getComponentElementById(this, "btnSave").on("click", function () {
                this.saveAccount();
            }.bind(this));
        }

        loadAccount() {
            dxRequestInternal(getComponentControllerPath(this), {f: "getObjectData"}, function (data) {
                this.componentObj = {
                    "FirstName": "",
                    "LastName": "",
                    "EmailAddress": "",
                    "Password": "",
                };
                this.elementMapping = {
                    "FirstName": "#" + this.uid + "_FirstName",
                    "LastName": "#" + this.uid + "_LastName",
                    "EmailAddress": "#" + this.uid + "_EmailAddress",
                    "Password": "#" + this.uid + "_Password",
                };

                this.setValues();
            }.bind(this), function (data) {
                this.handleComponentError(data.Message);
            }.bind(this));
        }

        setValues() {
            getComponentElementById(this, "FirstName").val("");
            getComponentElementById(this, "LastName").val("");
            getComponentElementById(this, "EmailAddress").val("");
            getComponentElementById(this, "Password").val("");
        }

        updateValues() {
            let keys = Object.keys(this.elementMapping);
            keys.forEach(function (item) {
                if ($(this.elementMapping[item]).attr("type") == "checkbox") {
                    this.componentObj[item] = $(this.elementMapping[item]).is(':checked') ? 1 : 0;
                } else {
                    this.componentObj[item] = $(this.elementMapping[item]).val();
                }
            }.bind(this));
            return this.componentObj;
        }

        saveAccount() {
            let current_component_obj = this.updateValues();
            this.resetValidation();
            if (!this.validateAccount()) {
                return;
            }
            dxRequestInternal(getComponentControllerPath(this), {
                f: "saveObjectData",
                ObjectData: JSON.stringify(current_component_obj)
            }, function (data) {
                registerUserRole(data.UserRole);
                pageEventTriggered("account_registered", {"account_id": data.Id});
                this.loadAccount();
                this.resetValidation();
            }.bind(this), function (data) {
                showAlert("Error saving account: " + data.Message, "error", "OK", false);
            });
        }

        validateAccount() {
            let validation_succeeded = true;
            this.requiredValidations.forEach(function (item) {
                if (getComponentElementById(this, item).attr("type") !== "checkbox") {
                    if (getComponentElementById(this, item).val() == "") {
                        validation_succeeded = false;
                        toggleValidationState(this, item, "", false);
                    } else {
                        toggleValidationState(this, item, "", true);
                    }
                }
            }.bind(this));
            this.dataValidations.forEach(function (item) {
                if (!getComponentElementById(this, item).hasClass("is-invalid")) {
                    if (getComponentElementById(this, item).hasClass("validate-number")) {
                        if (isNaN(getComponentElementById(this, item).val())) {
                            validation_succeeded = false;
                            toggleValidationState(this, item, "", false);
                        } else {
                            toggleValidationState(this, item, "", true);
                        }
                    }
                }
            }.bind(this));
            this.customValidations.forEach(function (item) {
                if (checkValidationState(this, item)) {
                    validation_succeeded &= this.doCustomValidation(item);
                }
            }.bind(this));
            if (getComponentElementById(this, 'Password').val() != getComponentElementById(this, 'PasswordConfirm').val()) {
                toggleValidationState(this, 'PasswordConfirm', "Passwords do not match", false);
                validation_succeeded = false;
            } else {
                toggleValidationState(this, 'PasswordConfirm', "", true);
            }
            return validation_succeeded;
        }

        doCustomValidation(attribute) {
            switch (attribute) {
                case 'EmailAddress':
                    let valid = validateEmail(getComponentElementById(this, 'EmailAddress').val());
                    toggleValidationState(this, 'EmailAddress', "Please" +
                        " provide a" +
                        " valid email address", valid);
                    return valid;
                    break;
                default:
                    break;
            }
        }

        resetValidation() {
            this.requiredValidations.forEach(function (item) {
                toggleValidationState(this, item, "", true, true);
            }.bind(this));
        }

        initCustomFunctions() {

            // FS7vu_button Related functionality
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            getComponentElementById(this, "btnBackToLogin").on("click", function () {
                loadPageComponent('login');
            }.bind(this));
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        }
    }

    componentClasses['system_account_registration'] = AccountRegistration;
}