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
                    getComponentElementById(this,'StatusLabel').html(this.getLoadArgument("ticket_status"));
                    getComponentElementById(this,'StatusCount').html(data_obj.Count);
                    
                    /*let html = "";
                    dxLog("Returned: " + data_obj.ReturnData[0]);
                    dxLog("ReturnData array length:  " + data_obj.ReturnData.length);

                    // data_obj.ReturnData = ["Default_Status", 7];

                    let wrapping_html = '<a href="#" id="' + data_obj.ReturnData[0] + '" class="list-group-item' +
                        ' list-group-item-action flex-column align-items-start data_list_item dx-data-list-row">';
                    let header_wrapping_html = '<div class="d-flex w-100 justify-content-between">';
                    let content_html = '<h3>' + data_obj.ReturnData[0] + '</h3>';
                    content_html += '<p> Nr. of Tickets: ' + data_obj.ReturnData[1] + '</p>';
                    html = header_wrapping_html + wrapping_html + content_html + "</a></div>";
                    getComponentElementById(this, "StatusWrapper").html(html);*/
                }.bind(this),
                function (data_obj) {
                    // Failure function

                }
            );
        }
    }

    component_classes['dashboard_ticket_status_indicator'] = dashboard_ticket_status_indicator;
}