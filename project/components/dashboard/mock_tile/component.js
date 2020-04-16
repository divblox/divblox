// import {CountUp} from "../../../../divblox/assets/js/countUp";

if (typeof component_classes['dashboard_mock_tile'] === "undefined") {
    class dashboard_mock_tile extends DivbloxDomBaseComponent {
        constructor(inputs, supports_native, requires_native) {
            super(inputs, supports_native, requires_native);
            // Sub component config start
            this.sub_component_definitions = [];
            // Sub component config end
        }

        reset(inputs) {
            super.reset(inputs);
            this.applyCssStyles();
            this.loadStatusTotals();
        }

        loadStatusTotals() {
            let uid = this.getLoadArgument("uid");
            let title = this.getLoadArgument("title");
            let value = this.getLoadArgument("value");
            let perc = this.getLoadArgument("perc");
            let time = this.getLoadArgument("time");
            let html_title = uid + "_StatusTitle";
            let html_value = uid + "_StatusValue";
            let html_perc = uid + "_StatusPercentage";
            let html_time = uid + "_StatusTime";
            $("#" + html_title).html(title);
            $("#" + html_value).html(value);
            $("#" + html_perc).html(perc);
            $("#" + html_time).html(time);
        }
        applyCssStyles() {
            let status = this.getLoadArgument("title").toLowerCase();
            dxLog("title: " + status);
            getComponentElementById(this,"StatusWrapper").addClass("mock-tile-" + status);
        }

    }

    component_classes['dashboard_mock_tile'] = dashboard_mock_tile;
}