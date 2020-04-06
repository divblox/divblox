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
            this.applyStatusCssClass();
            this.loadStatusTotals();
        }

        applyStatusCssClass() {
            let status = this.getLoadArgument("ticket_status").replace(' ', '-').toLowerCase();
            getComponentElementById(this, "StatusWrapper").addClass("dashboard-tile-" + status);
            getComponentElementById(this, "StatusCount").addClass("status-count-" + status);
            getComponentElementById(this, "StatusPercentage").addClass("status-percentage-" + status);
        }

        loadStatusTotals() {
            dxRequestInternal(getComponentControllerPath(this), {
                    f: "loadStatusTotals",
                    ticket_status: this.getLoadArgument("ticket_status")
                },
                function (data_obj) {
                    getComponentElementById(this, 'StatusLabel').html('<p>' + this.getLoadArgument("ticket_status") + ':</p>');
                    let status = this.getLoadArgument('ticket_status').replace(' ', '-').toLowerCase();
                    // Using jquery-easing plugin
                    $({Counter: 0}).animate({
                        Counter: data_obj.Count
                    }, {
                        duration: 1500,
                        easing: 'easeInOutExpo',
                        step: function () {
                            $('.status-count-'+status).html(Math.ceil(this.Counter));
                        }
                    });
                    dxLog(data_obj.Percentage);
                    $({Counter: 0}).animate({
                        Counter: data_obj.Percentage
                    }, {
                        duration: 2000,
                        easing: 'easeInOutExpo',
                        step: function () {
                            $('.status-percentage-'+status).html((this.Counter*100).toFixed(2)+'%');
                        }
                    });
                }.bind(this),
                function (data_obj) {
                    // Failure function
                    dxLog("dxRequestInternal Failure. Data Object returned: " + JSON.stringify(data_obj));
                }
            );
        }

    }

    component_classes['dashboard_ticket_status_indicator'] = dashboard_ticket_status_indicator;
}