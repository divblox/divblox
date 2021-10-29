if (typeof componentClasses['data_model_account_account_additional_info_manager'] === "undefined") {
    class AccountAdditionalInfoManager extends DivbloxDomBaseComponent {
        constructor(inputs, supportsNative, requiresNative) {
            super(inputs, supportsNative, requiresNative);
            // Sub component config start
            // this.subComponentDefinitions = [];
            this.subComponentDefinitions = [
                {
                    "component_load_path": "data_model/account/account_additional_info_manager_data_series",
                    "parent_element": "data_series",
                    "arguments": {"uid": this.getUid() + "_account_additional_info_manager_data_series_component"}
                },
                {
                    "component_load_path": "data_model/account/account_additional_info_manager_update",
                    "parent_element": "data_update",
                    "arguments": {"uid": this.getUid() + "_account_additional_info_manager_update_component"}
                },
                {
                    "component_load_path": "data_model/account/account_additional_info_manager_create",
                    "parent_element": "data_create",
                    "arguments": {"uid": this.getUid() + "_account_additional_info_manager_create_component"}
                }];
            // Sub component config end
        }

        reset(inputs, propagate) {
            super.reset(inputs, propagate);
            this.toggleSubView("data_series_wrapper");
        }

        eventTriggered(event_name, parameters_obj) {
            // Handle specific events here. This is useful if the component needs to update because one of its
            // sub-components did something
            switch (event_name) {
                case 'account_additional_info_manager_create_clicked':
                    this.toggleSubView("data_create_wrapper");
                    getRegisteredComponent(this.getUid() + "_account_additional_info_manager_create_component").reset();
                    break;
                case 'additional_account_information_clicked':
                    this.toggleSubView("data_update_wrapper");
                    getRegisteredComponent(this.getUid() + "_account_additional_info_manager_update_component").reset(parameters_obj.id, true);
                    break;
                case 'additional_account_information_created':
                case 'additional_account_information_deleted':
                case 'additional_account_information_updated':
                case 'account_additional_info_manager_back_clicked':
                    this.toggleSubView("data_series_wrapper");
                    getRegisteredComponent(this.getUid() + "_account_additional_info_manager_data_series_component").reset();
                    break;
                default:
                    dxLog("Event triggered: " + event_name + ": " + JSON.stringify(parameters_obj));
            }
            // Let's pass the event to all sub components
            this.propagateEventTriggered(event_name, parameters_obj);
        }

        registerDomEvents() {
            getComponentElementById(this, "button_create").on("click", function () {
                pageEventTriggered("account_additional_info_manager_create_clicked", {});
            });
            getComponentElementById(this, "button_create_back").on("click", function () {
                pageEventTriggered("account_additional_info_manager_back_clicked", {});
            });
            getComponentElementById(this, "button_update_back").on("click", function () {
                pageEventTriggered("account_additional_info_manager_back_clicked", {});
            });
        }

        toggleSubView(view_element_id) {
            let view_array = ["data_series_wrapper", "data_update_wrapper", "data_create_wrapper"];
            getComponentElementById(this, view_element_id).fadeIn("slow");
            view_array.forEach(function (item) {
                if (item !== view_element_id) {
                    getComponentElementById(this, item).hide();
                }
            }.bind(this));
        }
    }

    componentClasses['data_model_account_account_additional_info_manager'] = AccountAdditionalInfoManager;
}