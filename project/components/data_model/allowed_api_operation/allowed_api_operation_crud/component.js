if (typeof componentClasses['data_model_allowed_api_operation_allowed_api_operation_crud'] === "undefined") {
    class AllowedApiOperationCrud extends DivbloxDomBaseComponent {
        constructor(inputs, supportsNative, requiresNative) {
            super(inputs, supportsNative, requiresNative);
            // Sub component config start
            this.subComponentDefinitions = [
                {
                    "component_load_path": "data_model/allowed_api_operation/allowed_api_operation_crud_data_series",
                    "parent_element": "data_series",
                    "arguments": {"uid": this.getUid() + "_allowed_api_operation_crud_data_series_component"}
                },
                {
                    "component_load_path": "data_model/allowed_api_operation/allowed_api_operation_crud_update",
                    "parent_element": "data_update",
                    "arguments": {"uid": this.getUid() + "_allowed_api_operation_crud_update_component"}
                },
                {
                    "component_load_path": "data_model/allowed_api_operation/allowed_api_operation_crud_create",
                    "parent_element": "data_create",
                    "arguments": {"uid": this.getUid() + "_allowed_api_operation_crud_create_component"}
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
                case 'allowed_api_operation_crud_create_clicked':
                    this.toggleSubView("data_create_wrapper");
                    getRegisteredComponent(this.getUid() + "_allowed_api_operation_crud_create_component").reset();
                    break;
                case 'allowed_api_operation_clicked':
                    this.toggleSubView("data_update_wrapper");
                    getRegisteredComponent(this.getUid() + "_allowed_api_operation_crud_update_component").reset(parameters_obj.id);
                    break;
                case 'allowed_api_operation_created':
                case 'allowed_api_operation_deleted':
                case 'allowed_api_operation_updated':
                case 'allowed_api_operation_crud_back_clicked':
                    this.toggleSubView("data_series_wrapper");
                    getRegisteredComponent(this.getUid() + "_allowed_api_operation_crud_data_series_component").reset();
                    break;
                default:
                    dxLog("Event triggered: " + event_name + ": " + JSON.stringify(parameters_obj));
            }
            // Let's pass the event to all sub components
            this.propagateEventTriggered(event_name, parameters_obj);
        }

        registerDomEvents() {
            getComponentElementById(this, "button_create").on("click", function () {
                pageEventTriggered("allowed_api_operation_crud_create_clicked", {});
            });
            getComponentElementById(this, "button_create_back").on("click", function () {
                pageEventTriggered("allowed_api_operation_crud_back_clicked", {});
            });
            getComponentElementById(this, "button_update_back").on("click", function () {
                pageEventTriggered("allowed_api_operation_crud_back_clicked", {});
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

    componentClasses['data_model_allowed_api_operation_allowed_api_operation_crud'] = AllowedApiOperationCrud;
}