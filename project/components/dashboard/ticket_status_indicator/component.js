// import {CountUp} from "../../../../divblox/assets/js/countUp";

if (typeof component_classes['dashboard_ticket_status_indicator'] === "undefined") {
    class dashboard_ticket_status_indicator extends DivbloxDomBaseComponent {
        constructor(inputs, supports_native, requires_native) {
            super(inputs, supports_native, requires_native);
            // Sub component config start
            this.sub_component_definitions = [];
            // Sub component config end
        }

        reset(inputs) {
            super.reset(inputs);
            this.loadStatusTotals();
        }

        loadStatusTotals() {
            dxRequestInternal(getComponentControllerPath(this), {
                    f: "loadStatusTotals",
                    ticket_status: this.getLoadArgument("ticket_status")
                },
                function (data_obj) {
                    getComponentElementById(this,'StatusLabel').html('<p>' + this.getLoadArgument("ticket_status") + ':</p>');
                    // getComponentElementById(this,'StatusCount').html(data_obj.Count);
                    this.animateValue('StatusCount', 0, data_obj.Count, 2000);

                }.bind(this),
                function (data_obj) {
                    // Failure function

                }
            );
        }

        animateValue(id, start, end, duration) {
            let range = end - start;
            let current = start;
            let increment = end > start? 1 : 0;
            let stepTime = Math.abs(Math.floor(duration / range));
            let obj = getComponentElementById(this, id);
            let timer = setInterval(function() {
                current += increment;
                obj.html(current);
                if (current == end) {
                    clearInterval(timer);
                }
            }, stepTime);
        }
    }

    component_classes['dashboard_ticket_status_indicator'] = dashboard_ticket_status_indicator;
}