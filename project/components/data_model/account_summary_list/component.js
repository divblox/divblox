if (typeof component_classes['data_model_account_summary_list'] === "undefined") {
    class data_model_account_summary_list extends DivbloxDomEntityDataListComponent {
        constructor(inputs, supports_native, requires_native) {
            super(inputs, supports_native, requires_native);
            // Sub component config start
            this.sub_component_definitions = [];
            // Sub component config end
            this.included_attributes_object =
                {"FullName": "Normal", "ProfilePicturePath": "Normal", "Title": "Normal"};
            this.included_relationships_object =
                {"UserRole": "Normal"};
            this.constrain_by_array = [];
            this.initDataListVariables("Account");
        }

		loadPage() {
			let search_text = getComponentElementById(this, "DataListSearchInput").val();
			getComponentElementById(this, "DataListLoading").html('<div class="dx-loading"></div>').show();

			let parameters_obj = {
				f: "getPage",
				CurrentOffset: this.current_list_offset,
				ItemsPerPage: this.list_offset_increment,
				SearchText: search_text,
				SortOptions: JSON.stringify(this.current_sort_column)
			};

			if (this.constrain_by_array.length > 0) {
				this.constrain_by_array.forEach(function (relationship) {
					parameters_obj['Constraining' + relationship + 'Id'] = getGlobalConstrainById(relationship);
				})
			}

			dxRequestInternal(getComponentControllerPath(this),
				parameters_obj,
				function (data_obj) {
					data_obj.Page.forEach(function (item) {
						this.addRow(item);
					}.bind(this));

					this.total_items = data_obj.TotalCount;
					getComponentElementById(this, "DataListMoreButton").show();
					if (this.total_items <= this.current_list_offset) {
						getComponentElementById(this, "DataListMoreButton").hide();
					}
					if (this.current_page_array.length > 0) {
						getComponentElementById(this, "DataListLoading").hide();
					} else {
						getComponentElementById(this, "DataListLoading").html("No results").show();
						getComponentElementById(this, "DataListMoreButton").hide();
					}
				}.bind(this),
				function (data_obj) {
					getComponentElementById(this, "DataList").hide();
					this.handleComponentError('Could not retrieve data: ' + data_obj.Message);
				}.bind(this),
				false,
				false);
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
			let profile_picture_html = '<div class="col-1 dashboard-tile dashboard-tile-list dashboard-tile-profile-picture align-content-center">';
			let account_info_html = '<div class="col-3 account-summary-info dashboard-tile dashboard-tile-list text-center">';
			let status_summary_html = '<div class="col-8">';

			profile_picture_html += '<img class="account-summary-profile-picture" src="' + row_data_obj['ProfilePicturePath'] + '">' + '</div>';
			account_info_html += '<div class="row text-center">' + row_data_obj["Title"] + " " + row_data_obj['FullName'] + '</div>';
			account_info_html += '<div class="row text-center">' + row_data_obj["UserRole"] + '</div>';
			account_info_html += '</div>';
			status_summary_html += '<div class="row">';
			let status_array = ["New", "In Progress", "Due Soon", "Urgent", "Complete", "Overdue"];
			status_array.forEach(function(status) {
				status_summary_html += '<div class="col-2 dashboard-tile dashboard-tile-list dashboard-tile-' + status.replace(' ', '-').toLowerCase() + '">' +
					status + ': ' + row_data_obj["StatusCounts"][status] + '</div>';
			});
			status_summary_html += '</div>';
			// header_components_html = '<h5 class="mb-1">' + row_data_obj["Title"] + " " + row_data_obj['FullName'] + '</h5>';
			// subtle_components_html += '<small>' + row_data_obj["UserRole"] + '</small>';
			// normal_components_html += '<p>' + row_data_obj["ProfilePicturePath"] + '</p>';
			// footer_components_html += '<small>' + row_data_obj["StatusCounts"]["New"] + '</small>';
			// header_wrapping_html += header_components_html + subtle_components_html;
			// header_wrapping_html += '</div>';
			// wrapping_html += header_wrapping_html + normal_components_html + footer_components_html;
			// wrapping_html += '</a>';
			wrapping_html += '<div class="row">' + profile_picture_html + account_info_html + status_summary_html + '</div>' + '</div>';
			wrapping_html += '</a>';
			getComponentElementById(this, "DataList").append(wrapping_html);
		}
    }

    component_classes['data_model_account_summary_list'] = data_model_account_summary_list;
}