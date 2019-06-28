if (typeof component_classes['data_model_account_additional_info_manager_data_series'] === "undefined") {
	class data_model_account_additional_info_manager_data_series extends DivbloxDomBaseComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
			this.sub_component_definitions = {};
			// Sub component config end
			this.current_list_offset = 0;
			this.list_offset_increment = 10;
			this.current_page_array = [];
			this.total_items = 0;
			this.current_sort_column = ["Type",true];
		}
		reset(inputs) {
			getComponentElementById(this,"DataList").html("");
			this.loadPage();
			super.reset(inputs);
		}
		registerDomEvents() {
			getComponentElementById(this,"DataListSearchInput").on("keyup", function() {
				let uid = getUidFromComponentElementId($(this).attr("id"),"DataListSearchInput");
				let this_component = getRegisteredComponent(uid);
				let search_text = getComponentElementById(this_component,"DataListSearchInput").val();
				setTimeout(function() {
					if (search_text == getComponentElementById(this_component,"DataListSearchInput").val()) {
						getComponentElementById(this_component,"DataList").html("");
						this_component.current_page_array = [];
						this_component.current_list_offset = 0;
						this_component.loadPage();
					}
				},500);
			});
			getComponentElementById(this,"btnResetSearch").on("click", function() {
				let uid = getUidFromComponentElementId($(this).attr("id"),"btnResetSearch");
				let this_component = getRegisteredComponent(uid);
				getComponentElementById(this_component,"DataListSearchInput").val("");
				getComponentElementById(this_component,"DataList").html("");
				this_component.current_page_array = [];
				this_component.current_list_offset = 0;
				this_component.loadPage();
			});
			getComponentElementById(this,"DataListMoreButton").on("click", function() {
				let uid = getUidFromComponentElementId($(this).attr("id"),"DataListMoreButton");
				let this_component = getRegisteredComponent(uid);
				this_component.current_list_offset += this_component.list_offset_increment;
				this_component.loadPage();
			});
			$(document).on("click",".data_list_item_"+this.uid, function() {
				let id_start = $(this).attr("id").indexOf("_row_item_");
				let clicked_id = $(this).attr("id").substring(id_start+10);
				let uid = $(this).attr("id").substring(0,id_start);
				let this_component = getRegisteredComponent(uid);
				this_component.on_item_clicked(clicked_id);
				return false;
			});
			registerEventHandler(document,"click","data_list_item_"+this.uid)
		}
		loadPage() {
			let search_text = getComponentElementById(this,"DataListSearchInput").val();
			getComponentElementById(this,"DataListLoading").html('<div class="dx-loading"></div>').show();
			dxRequestInternal(getComponentControllerPath(this),
				{f:"getPage",
					CurrentOffset:this.current_list_offset,
					ItemsPerPage:this.list_offset_increment,
					SearchText:search_text,
					SortOptions:JSON.stringify(this.current_sort_column)},
				function(data_obj) {
					data_obj.Page.forEach(function(item) {
						this.addRow(item);
					}.bind(this));
					this.total_items = data_obj.TotalCount;
					getComponentElementById(this,"DataListMoreButton").show();
					if (this.total_items <= this.current_list_offset) {
						getComponentElementById(this,"DataListMoreButton").hide();
					}
					if (this.current_page_array.length > 0) {
						getComponentElementById(this,"DataListLoading").hide();
					} else {
						getComponentElementById(this,"DataListLoading").html("No results").show();
						getComponentElementById(this,"DataListMoreButton").hide();
					}
				}.bind(this),
				function(data_obj) {
					getComponentElementById(this,"DataList").hide();
					this.handleComponentError('Could not retrieve data: '+data_obj.Message);
				}.bind(this));
		}
		addRow(row_data_obj) {
			this.current_page_array.push(row_data_obj);
			let row_id = row_data_obj["Id"];
			let html = '<span id="'+this.uid+'_row_item_'+row_id+'" class="list-group-item list-group-item-action' +
				' flex-column align-items-start data_list_item data_list_item_'+this.uid+' dx-data-list-row">' +
				'            <div class="d-flex w-100 justify-content-between">\n' +
				'                <h5 class="mb-1">'+row_data_obj['Label']+'</h5>\n' +
				'                <small>'+row_data_obj['Type']+'</small>\n' +
				'            </div>\n' +
				'            <p class="mb-1">'+row_data_obj['Value']+'</p>\n' +
				
				'</span>';
			getComponentElementById(this,"DataList").append(html);
		}
		// Data table functions to implement
		on_item_clicked(id) {
			pageEventTriggered("additional_account_information_clicked",{id:id});
		}
	}
	component_classes['data_model_account_additional_info_manager_data_series'] = data_model_account_additional_info_manager_data_series;
}