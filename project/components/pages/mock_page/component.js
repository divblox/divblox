if (typeof component_classes['pages_mock_page'] === "undefined") {
    class pages_mock_page extends DivbloxDomBaseComponent {
        constructor(inputs, supports_native, requires_native) {
            super(inputs, supports_native, requires_native);
            // Sub component config start
            this.sub_component_definitions = 
                [{"component_load_path":"navigation/side_navbar","parent_element":"s8gjT","arguments":{"uid":"navigation_side_navbar_1"}},
            {"component_load_path":"dashboard/mock_tile","parent_element":"V4nNa","arguments":{"uid":"dashboard_mock_tile_1","title":"ORDERS","value":"302","perc":"+3.80%","time":"Since last month"}},
            {"component_load_path":"dashboard/mock_tile","parent_element":"6vlFm","arguments":{"uid":"dashboard_mock_tile_2","title":"CUSTOMERS","value":"72","perc":"-9.24%","time":"Since last month"}},
            {"component_load_path":"dashboard/mock_tile","parent_element":"QMBUY","arguments":{"uid":"dashboard_mock_tile_3","title":"SALES","value":"52,375","perc":"+24.90%","time":"Since last month"}},
            {"component_load_path":"dashboard/mock_tile","parent_element":"jCbTt","arguments":{"uid":"dashboard_mock_tile_4","title":"PERFORMANCE","value":"+72.50%","perc":"+50.00%","time":"Since last month"}}];
            // Sub component config end
        }

        reset(inputs) {
            setActivePage("page_component_name", "Page Title");
            super.reset(inputs);
        }
    }

    component_classes['pages_mock_page'] = pages_mock_page;
}