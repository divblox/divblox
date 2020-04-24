if (typeof component_classes['data_model_ticket_summary_list'] === "undefined") {
    class data_model_ticket_summary_list extends DivbloxDomEntityDataListComponent {
        constructor(inputs, supports_native, requires_native) {
            super(inputs, supports_native, requires_native);
            // Sub component config start
            this.sub_component_definitions = [];
            // Sub component config end
            this.included_attributes_object =
                {
                    "TicketName": "Normal",
                    "TicketDueDate": "Normal",
                    "TicketStatus": "Normal",
                    "TicketProgress": "Normal"
                };
            this.included_relationships_object =
                {"Account": "Normal", "Category": "Normal"};
            this.constrain_by_array = [];
            this.initDataListVariables("Ticket");
        }

        addRow(row_data_obj) {
            let current_item_keys = Object.keys(this.current_page_array);
            let must_add_row = true;
            current_item_keys.forEach(function (key) {
                if (this.current_page_array[key]["Id"] == row_data_obj["Id"]) {
                    must_add_row = false;
                }
            }.bind(this));
            if (!must_add_row) {
                return;
            }
            this.current_page_array.push(row_data_obj);
            let row_id = row_data_obj["Id"];
            let included_keys = Object.keys(this.included_all_object);
            let wrapping_html = '<a href="#" id="' + this.getUid() + '_row_item_' + row_id + '" class="list-group-item' +
                ' list-group-item-action flex-column align-items-start data_list_item data_list_item_' + this.getUid() + ' dx-data-list-row">';
            let header_wrapping_html = '<div class="d-flex w-100 justify-content-between">';

            let ticket_name_html = '<div class="col-4">';
            let account_name_html = '<div class="col-4">';
            let ticket_date_html = '<div class="col-4">';

            let account_names = row_data_obj["Account"].split(" ");
            let return_account_name = account_names[0].slice(0, 1) + ". " + account_names[1];
			ticket_name_html += row_data_obj["TicketName"] + '</div>';
            account_name_html += return_account_name + '</div>';
            ticket_date_html += row_data_obj["TicketDueDate"] + '</div>';

            // header_wrapping_html += header_components_html + subtle_components_html;
            // header_wrapping_html += '</div>';

            wrapping_html += '<div class="row">' + ticket_name_html + account_name_html + ticket_date_html + '</div>'+ '</div>';
            wrapping_html += '</a>';
            getComponentElementById(this, "DataList").append(wrapping_html);
        }
    }

    component_classes['data_model_ticket_summary_list'] = data_model_ticket_summary_list;
}