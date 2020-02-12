case "[Attribute]":
                    // If validation is passed, this displays an optional message
                    toggleValidationState(this,attribute,"Custom passed validation text",true);
                    // TODO: implement the custom validation function. By default, we fail the validation
                    toggleValidationState(this,attribute,"Custom failed validation text",false);
                    return false;
                        break;