if (typeof component_classes['data_model_account_summary_list'] === "undefined") {
    class data_model_account_summary_list extends DivbloxDomEntityDataListComponent {
        constructor(inputs, supports_native, requires_native) {
            super(inputs, supports_native, requires_native);
            // Sub component config start
            this.sub_component_definitions = [];
            // Sub component config end
            this.included_attributes_object =
                {"FullName": "Normal", "FirstName": "Normal", "LastName": "Normal", "ProfilePicturePath": "Normal", "Title": "Normal"};
            this.included_relationships_object =
                {"UserRole": "Normal"};
            this.constrain_by_array = [];
            this.initDataListVariables("Account");
        }


		addRow(row_data_obj) {
			let current_item_keys = Object.keys(this.current_page_array);
			// dxLog(current_item_keys);
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
			dxLog(included_keys);
			let wrapping_html = '<a href="#" id="' + this.getUid() + '_row_item_' + row_id + '" class="list-group-item' +
				' list-group-item-action flex-column align-items-start data_list_item data_list_item_' + this.getUid() + ' dx-data-list-row">';
			let header_wrapping_html = '<div class="d-flex w-100 justify-content-between">';

			let header_components_html = '';
			let subtle_components_html = '';
			let normal_components_html = '';
			let footer_components_html = '';
			let profile_picture_html = '<div class="col-2">';
			let account_info_html = '<div class="col-4">';
			let status_summary_html = '<div class="col-6">';

			profile_picture_html += '<img class="dashboard-tile-profile-picture" src="' + row_data_obj['ProfilePicturePath'] + '" alt="Profile Picture">' + '</div>';
			account_info_html += '<div class="row"> <div class="col-12 dashboard-tile-list">' + row_data_obj['FirstName'] + '<br>' + row_data_obj["LastName"] + '</div>';
			account_info_html += '</div>' + '</div>';
			status_summary_html += '<div class="row">';
			let status_array = ["In Progress", "Overdue"];
			status_array.forEach(function(status) {
				status_summary_html += '<div class="col-6 dashboard-tile-list">' +
					status + ' <br> <strong>' + row_data_obj["StatusCounts"][status] + '</strong></div>';
			});
			status_summary_html += '</div>';

			wrapping_html += '<div class="row">' + profile_picture_html + account_info_html + status_summary_html + '</div>' + '</div>';
			wrapping_html += '</a>';
			getComponentElementById(this, "DataList").append(wrapping_html);
		}
    }

    component_classes['data_model_account_summary_list'] = data_model_account_summary_list;
}