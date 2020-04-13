if (typeof component_classes['pages_dashboard'] === "undefined") {
    class pages_dashboard extends DivbloxDomBaseComponent {
        constructor(inputs, supports_native, requires_native) {
            super(inputs, supports_native, requires_native);
            // Sub component config start
            this.sub_component_definitions = 
                [{"component_load_path":"navigation/side_navbar","parent_element":"s8gjT","arguments":{"uid":"navigation_side_navbar_1"}},
            {"component_load_path":"dashboard/ticket_status_indicator","parent_element":"FVKl3","arguments":{"uid":"dashboard_ticket_status_indicator_1","ticket_status":"New"}},
            {"component_load_path":"dashboard/ticket_status_indicator","parent_element":"5i4hs","arguments":{"uid":"dashboard_ticket_status_indicator_2","ticket_status":"In Progress"}},
            {"component_load_path":"dashboard/ticket_status_indicator","parent_element":"GXoTk","arguments":{"uid":"dashboard_ticket_status_indicator_3","ticket_status":"Due Soon"}},
            {"component_load_path":"dashboard/ticket_status_indicator","parent_element":"hCQoB","arguments":{"uid":"dashboard_ticket_status_indicator_4","ticket_status":"Urgent"}},
            {"component_load_path":"dashboard/ticket_status_indicator","parent_element":"Vfrds","arguments":{"uid":"dashboard_ticket_status_indicator_5","ticket_status":"Complete"}},
            {"component_load_path":"dashboard/ticket_status_indicator","parent_element":"zo9n7","arguments":{"uid":"dashboard_ticket_status_indicator_6","ticket_status":"Overdue"}},
            {"component_load_path":"data_model/account_summary_list","parent_element":"Z0fDv","arguments":{"uid":"data_model_account_summary_list_1"}},];
            // Sub component config end
        }

        reset(inputs) {
            setActivePage("dashboard", "Dashboard");
            super.reset(inputs);
        }

    }

    component_classes['pages_dashboard'] = pages_dashboard;
}