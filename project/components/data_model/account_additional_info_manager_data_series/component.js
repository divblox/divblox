if (typeof component_classes['data_model_account_additional_info_manager_data_series'] === "undefined") {
	class data_model_account_additional_info_manager_data_series extends DivbloxDomBaseComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
			this.sub_component_definitions = [];
			// Sub component config end
			this.current_list_offset = 0;
			this.list_offset_increment = 10;
			this.current_page_array = [];
			this.total_items = 0;
			this.current_sort_column = ["Type",true];
		}
		reset(inputs) {
			this.current_page_array = [];
			getComponentElementById(this,"DataList").html("");
			this.loadPage();
			super.reset(inputs);
		}
		registerDomEvents() {
			getComponentElementById(this,"DataListSearchInput").on("keyup", function() {
				let search_text = getComponentElementById(this,"DataListSearchInput").val();
				setTimeout(function() {
					if (search_text == getComponentElementById(this,"DataListSearchInput").val()) {
						getComponentElementById(this,"DataList").html("");
						this.current_page_array = [];
						this.current_list_offset = 0;
						this.loadPage();
					}
				}.bind(this),500);
			}.bind(this));
			getComponentElementById(this,"btnResetSearch").on("click", function() {
				getComponentElementById(this,"DataListSearchInput").val("");
				getComponentElementById(this,"DataList").html("");
				this.current_page_array = [];
				this.current_list_offset = 0;
				this.loadPage();
			}.bind(this));
			getComponentElementById(this,"DataListMoreButton").on("click", function() {
				this.current_list_offset += this.list_offset_increment;
				this.loadPage();
			}.bind(this));
			$(document).on("click",".data_list_item_"+this.uid, function() {
				let id_start = $(this).attr("id").indexOf("_row_item_");
				let clicked_id = $(this).attr("id").substring(id_start+10);
				let uid = $(this).attr("id").substring(0,id_start);
				let this_component = getRegisteredComponent(uid);
				this_component.on_item_clicked(clicked_id);
				return false;
			});
			registerEventHandler(document,"click",undefined,".data_list_item_"+this.uid);
		}
		loadPage() {
			let search_text = getComponentElementById(this,"DataListSearchInput").val();
			getComponentElementById(this,"DataListLoading").html('<div class="dx-loading"></div>').show();
			dxRequestInternal(getComponentControllerPath(this),
				{f:"getPage",
					CurrentOffset:this.current_list_offset,
					ItemsPerPage:this.list_offset_increment,
					SearchText:search_text,
					SortOptions:JSON.stringify(this.current_sort_column),
                    ConstrainingAccountId:getGlobalConstrainById('Account')},
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
				}.bind(this),false,false);
		}
		addRow(row_data_obj) {
			let current_item_keys = Object.keys(this.current_page_array);
			let must_add_row = true;
			current_item_keys.forEach(function(key) {
				if (this.current_page_array[key]["Id"] == row_data_obj["Id"]) {must_add_row = false;}
			}.bind(this));
			if (!must_add_row) {return;}
			this.current_page_array.push(row_data_obj);
			let row_id = row_data_obj["Id"];
			let html = '<a href="#" id="'+this.uid+'_row_item_'+row_id+'" class="list-group-item list-group-item-action' +
				' flex-column align-items-start data_list_item data_list_item_'+this.uid+' dx-data-list-row">' +
				'            <div class="d-flex w-100 justify-content-between">\n' +
				'                <h5 class="mb-1">'+row_data_obj['Label']+'</h5>\n' +
					'                <small>'+row_data_obj['Type']+'</small>\n' +
			'            </div>\n' +
			'            <p class="mb-1">'+row_data_obj['Value']+'</p>\n' +
				
			'</a>';
			getComponentElementById(this,"DataList").append(html);
		}
		// Data table functions to implement
		on_item_clicked(id) {
			setGlobalConstrainById("AdditionalAccountInformation",id);
			pageEventTriggered("additional_account_information_clicked",{id:id});
		}
	}
	component_classes['data_model_account_additional_info_manager_data_series'] = data_model_account_additional_info_manager_data_series;
}