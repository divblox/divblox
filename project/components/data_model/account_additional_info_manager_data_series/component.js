if (typeof(on_data_model_account_additional_info_manager_data_series_ready) === "undefined") {
	function on_data_model_account_additional_info_manager_data_series_ready(load_arguments) {
		// This is required for any component to be registered to the DOM as a divblox component
		this.dom_component_obj = new DivbloxDOMComponent(load_arguments);
		this.handleComponentError = function(ErrorMessage) {
			this.dom_component_obj.handleComponentError(this,ErrorMessage);
		}.bind(this);
		this.handleComponentSuccess = function() {
			this.dom_component_obj.handleComponentSuccess(this);
		}.bind(this);
		this.reset = function(inputs) {
			getComponentElementById(this,"DataList").html("");
			this.loadPage();
		}.bind(this);
		this.on_component_loaded = function() {
			this.dom_component_obj.on_component_loaded(this);
		}.bind(this);
		this.subComponentLoadedCallBack = function(component) {
			// Implement additional required functionality for sub components after load here
			// dxLog("Sub component loaded: "+JSON.stringify(component));
		}.bind(this);
		this.getSubComponents = function() {
			return this.dom_component_obj.getSubComponents(this);
		}.bind(this);
		this.getUid = function() {
			return this.dom_component_obj.getUid();
		}.bind(this);
		// Component specific code below
		// Empty array means ANY user role has access. NB! This is merely for UX purposes.
		// Do not rely on this as a security measure. User role security MUST be managed on the server's side
		this.allowedAccessArray = [];
		this.eventTriggered = function(event_name,parameters_obj) {
			// Handle specific events here. This is useful if the component needs to update because one of its
			// sub-components did something
			switch(event_name) {
				case '[event_name]':
				default:
					dxLog("Event triggered: "+event_name+": "+JSON.stringify(parameters_obj));
			}
			// Let's pass the event to all sub components
			this.dom_component_obj.propagateEventTriggered(event_name,parameters_obj);
		}.bind(this);
		// Sub component config start
		this.sub_components = {};
		// Sub component config end
		// Custom functions and declarations to be added below
		this.current_list_offset = 0;
		this.list_offset_increment = 10;
		this.current_page_array = [];
		this.total_items = 0;
		this.current_sort_column = ["Type",true];
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

		$(document).on("click",".data_list_item_"+this.dom_component_obj.uid, function() {
			let id_start = $(this).attr("id").indexOf("_row_item_");
			let clicked_id = $(this).attr("id").substring(id_start+10);
			let uid = $(this).attr("id").substring(0,id_start);
			let this_component = getRegisteredComponent(uid);
			this_component.on_item_clicked(clicked_id);
			return false;
		});
		this.loadPage = function() {
			let this_component = this;
			let uid = this_component.getUid();
			let search_text = getComponentElementById(this_component,"DataListSearchInput").val();
			getComponentElementById(this_component,"DataListLoading").html('<div class="dx-loading"></div>').show();
			dxRequestInternal(getComponentControllerPath(this),
				{f:"getPage",
					CurrentOffset:this_component.current_list_offset,
					ItemsPerPage:this_component.list_offset_increment,
					SearchText:search_text,
					SortOptions:JSON.stringify(this_component.current_sort_column)},
				function(data_obj) {
					data_obj.Page.forEach(function(item) {
						this_component.addRow(item);
					});
					this_component.total_items = data_obj.TotalCount;
					getComponentElementById(this_component,"DataListMoreButton").show();
					if (this_component.total_items <= this_component.current_list_offset) {
						getComponentElementById(this_component,"DataListMoreButton").hide();
					}
					if (this_component.current_page_array.length > 0) {
						getComponentElementById(this_component,"DataListLoading").hide();
					} else {
						getComponentElementById(this_component,"DataListLoading").html("No results").show();
						getComponentElementById(this_component,"DataListMoreButton").hide();
					}
				},
				function(data_obj) {
					getComponentElementById(this_component,"DataList").hide();
					this_component.handleComponentError('Could not retrieve data: '+data_obj.Message);
				});
		}.bind(this);
		this.addRow = function(row_data_obj) {
			let this_component = this;
			this_component.current_page_array.push(row_data_obj);
			let uid = this_component.dom_component_obj.uid;
			let row_id = row_data_obj["Id"];
			let html = '<span id="'+uid+'_row_item_'+row_id+'" class="list-group-item list-group-item-action' +
				' flex-column align-items-start data_list_item data_list_item_'+uid+' dx-data-list-row">' +
				'            <div class="d-flex w-100 justify-content-between">\n' +
				'                <h5 class="mb-1">'+row_data_obj['Label']+'</h5>\n' +
				'                <small>'+row_data_obj['Type']+'</small>\n' +
				'            </div>\n' +
				'            <p class="mb-1">'+row_data_obj['Value']+'</p>\n' +
				
				'</span>';
			getComponentElementById(this_component,"DataList").append(html);
		}.bind(this);
		// Data table functions to implement
		this.on_item_clicked = function(id) {
			pageEventTriggered("additional_account_information_clicked",{id:id});
		}.bind(this);
	}
}
