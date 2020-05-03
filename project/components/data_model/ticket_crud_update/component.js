if (typeof component_classes['data_model_ticket_crud_update'] === "undefined") {
    class data_model_ticket_crud_update extends DivbloxDomEntityInstanceComponent {
        constructor(inputs, supports_native, requires_native) {
            super(inputs, supports_native, requires_native);
            // Sub component config start
            this.sub_component_definitions =
                [{
                    "component_load_path": "data_model/subtask_crud",
                    "parent_element": "zsg8N",
                    "arguments": {"uid": "data_model_subtask_crud_1"}
                },
                    {"component_load_path": "data_model/note_crud", "parent_element": "YcX5f", "arguments": {}}];
            // Sub component config end
            this.included_attribute_array = ['TicketName', 'TicketDescription', 'TicketDueDate', 'TicketStatus', 'TicketUniqueId',];
            this.included_relationship_array = ['Category',];
            this.constrain_by_array = [];
            this.data_validation_array = [];
            this.custom_validation_array = [];
            this.required_validation_array = ['TicketName', 'TicketDescription', 'TicketDueDate', 'TicketStatus', 'TicketUniqueId', 'Category',];
            this.initCrudVariables("Ticket");
        }

        reset(inputs, propagate) {
            if (typeof inputs !== "undefined") {
                this.setEntityId(inputs);
            }
            super.reset(inputs, propagate);
        }

        initCustomFunctions() {
            // gPDeh_button Related functionality
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            getComponentElementById(this, "gPDeh_btn").on("click", function () {
                dxRequestInternal(
                    getComponentControllerPath(this),
                    {f: "getNewTicketUniqueId"},
                    function (data_obj) {
                        // Success function
                        getComponentElementById(this, "TicketUniqueId").val(data_obj.TicketId);
                    }.bind(this),
                    function (data_obj) {
                        // Fail function
                    }.bind(this)
                );
            }.bind(this));
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        }
    }

    component_classes['data_model_ticket_crud_update'] = data_model_ticket_crud_update;
}