if (typeof(on_data_model_account_administration_data_series_ready) === "undefined") {
	function on_data_model_account_administration_data_series_ready(load_arguments) {
		// This is required for any component to be registered to the DOM as a divblox component
		this.dom_component_obj = new DivbloxDOMComponent(load_arguments);
		this.handleComponentError = function(ErrorMessage) {
			this.dom_component_obj.handleComponentError(this,ErrorMessage);
		}.bind(this);
		this.handleComponentSuccess = function() {
			this.dom_component_obj.handleComponentSuccess(this);
		}.bind(this);
		this.reset = function(inputs) {
			this.loadPage();
		}.bind(this);
		this.on_component_loaded = function() {
			let this_component = this;
			this_component.dom_component_obj.on_component_loaded(this,false);
			dxGetScript(getRootPath()+'project/assets/js/tableexport/xlsx.core.min.js',function() {
				dxGetScript(getRootPath()+'project/assets/js/tableexport/FileSaver.min.js',function() {
					dxGetScript(getRootPath()+'project/assets/js/tableexport/tableexport.min.js',function() {
						this_component.handleComponentSuccess();
					})
				})
			});
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
		this.table_exporter = undefined;
		// Data table export functionality provided by TableExport plugin. Documentation here:
		// https://tableexport.v5.travismclarke.com/#tableexport
		// Default properties:
		/*
		TableExport(document.getElementsByTagName("table"), {
		headers: true,                      // (Boolean), display table headers (th or td elements) in the <thead>, (default: true)
		footers: true,                      // (Boolean), display table footers (th or td elements) in the <tfoot>, (default: false)
		formats: ["xlsx", "csv", "txt"],    // (String[]), filetype(s) for the export, (default: ['xlsx', 'csv', 'txt'])
		filename: "id",                     // (id, String), filename for the downloaded file, (default: 'id')
		bootstrap: false,                   // (Boolean), style buttons using bootstrap, (default: true)
		exportButtons: true,                // (Boolean), automatically generate the built-in export buttons for each of the specified formats (default: true)
		position: "bottom",                 // (top, bottom), position of the caption element relative to table, (default: 'bottom')
		ignoreRows: null,                   // (Number, Number[]), row indices to exclude from the exported file(s) (default: null)
		ignoreCols: null,                   // (Number, Number[]), column indices to exclude from the exported file(s) (default: null)
		trimWhitespace: true,               // (Boolean), remove all leading/trailing newlines, spaces, and tabs from cell text in the exported file(s) (default: false)
		RTL: false,                         // (Boolean), set direction of the worksheet to right-to-left (default: false)
		sheetname: "id"                     // (id, String), sheet name for the exported spreadsheet, (default: 'id')
		});
		*/
		this.current_page = 1;
		this.current_page_array = [];
		this.current_items_per_page = $("#"+this.dom_component_obj.uid+"_PaginationItemsPerPage").val();
		this.total_items = 0;
		this.total_pages = 0;
		this.remaining_pages = 0;
		this.column_name_obj = {
			"FullName":"Full Name",
            "EmailAddress":"Email Address",
            "AccessBlocked":"Access Blocked",
            "UserRole":"User Role"};
		this.column_name_array = Object.keys(this.column_name_obj);
		this.current_sort_column = [this.column_name_array[0],true]; // Sort on first column, desc
		this.selected_items_array = [];
		getComponentElementById(this,"BulkActionExportXlsx").on("click", function() {
			let uid = getUidFromComponentElementId($(this).attr("id"),"BulkActionExportXlsx");
			let this_component = getRegisteredComponent(uid);
			this_component.table_exporter = getComponentElementById(this_component,"DataTableTableHtml").tableExport({
				exportButtons: false,
				formats: ['xlsx'],
				filename: "dx_xlsx_export_"+moment().format("YYYY-MM-DD_h_mm_ss"),
			});
			this_component.exportData(this_component.table_exporter.getExportData()[uid+'_DataTableTableHtml']['xlsx']);
		});
		getComponentElementById(this,"BulkActionExportCsv").on("click", function() {
			let uid = getUidFromComponentElementId($(this).attr("id"),"BulkActionExportCsv");
			let this_component = getRegisteredComponent(uid);
			this_component.table_exporter = getComponentElementById(this_component,"DataTableTableHtml").tableExport({
				exportButtons: false,
				formats: ['csv'],
				filename: "dx_csv_export_"+moment().format("YYYY-MM-DD_h_mm_ss"),
			});
			this_component.exportData(this_component.table_exporter.getExportData()[uid+'_DataTableTableHtml']['csv']);
		});
		getComponentElementById(this,"BulkActionExportTxt").on("click", function() {
			let uid = getUidFromComponentElementId($(this).attr("id"),"BulkActionExportTxt");
			let this_component = getRegisteredComponent(uid);
			this_component.table_exporter = getComponentElementById(this_component,"DataTableTableHtml").tableExport({
				exportButtons: false,
				formats: ['txt'],
				filename: "dx_txt_export_"+moment().format("YYYY-MM-DD_h_mm_ss"),
			});
			this_component.exportData(this_component.table_exporter.getExportData()[uid+'_DataTableTableHtml']['txt']);
		});
		getComponentElementById(this,"DataTableSearchInput").on("keyup", function() {
			let uid = getUidFromComponentElementId($(this).attr("id"),"DataTableSearchInput");
			let this_component = getRegisteredComponent(uid);
			let search_text = getComponentElementById(this_component,"DataTableSearchInput").val();
			setTimeout(function() {
				if (search_text == getComponentElementById(this_component,"DataTableSearchInput").val()) {
					this_component.current_page = 1;
					this_component.loadPage();
				}
			},500);
		});
		getComponentElementById(this,"btnResetSearch").on("click", function() {
			let uid = getUidFromComponentElementId($(this).attr("id"),"btnResetSearch");
			let this_component = getRegisteredComponent(uid);
			getComponentElementById(this_component,"DataTableSearchInput").val("");
			this_component.loadPage();
		});
		getComponentElementById(this,"PaginationItemsPerPage").on("change", function() {
			let uid = getUidFromComponentElementId($(this).attr("id"),"PaginationItemsPerPage");
			let this_component = getRegisteredComponent(uid);
			this_component.current_items_per_page = $(this).val();
			this_component.loadPage();
		});
		getComponentElementById(this,"PaginationResetButton").on("click", function() {
			if ($(this).hasClass("disabled")) {
				return;
			}
			let uid = getUidFromComponentElementId($(this).attr("id"),"PaginationResetButton");
			let this_component = getRegisteredComponent(uid);
			this_component.current_page = 1;
			this_component.loadPage();
		});
		getComponentElementById(this,"PaginationFinalPageButton").on("click", function() {
			if ($(this).hasClass("disabled")) {
				return;
			}
			let uid = getUidFromComponentElementId($(this).attr("id"),"PaginationFinalPageButton");
			let this_component = getRegisteredComponent(uid);
			this_component.current_page = this_component.total_pages;
			this_component.loadPage();
		});
		getComponentElementById(this,"PaginationJumpBack").on("click", function() {
			if ($(this).hasClass("disabled")) {
				return;
			}
			let uid = getUidFromComponentElementId($(this).attr("id"),"PaginationJumpBack");
			let this_component = getRegisteredComponent(uid);
			this_component.current_page = this_component.current_page - 3;
			if (this_component.current_page < 1) {
				this_component.current_page = 1;
			}
			this_component.loadPage();
		});
		getComponentElementById(this,"PaginationJumpForward").on("click", function() {
			if ($(this).hasClass("disabled")) {
				return;
			}
			let uid = getUidFromComponentElementId($(this).attr("id"),"PaginationJumpForward");
			let this_component = getRegisteredComponent(uid);
			this_component.current_page = this_component.current_page + 3;
			if (this_component.current_page > this_component.total_pages) {
				this_component.current_page = this_component.total_pages;
			}
			this_component.loadPage();
		});
		getComponentElementById(this,"PaginationNextItem").on("click", function() {
			let uid = getUidFromComponentElementId($(this).attr("id"),"PaginationNextItem");
			let this_component = getRegisteredComponent(uid);
			this_component.current_page = this_component.current_page + 1;
			if (this_component.current_page > this_component.total_pages) {
				this_component.current_page = this_component.total_pages;
			}
			this_component.loadPage();
		});
		getComponentElementById(this,"PaginationNextNextItem").on("click", function() {
			let uid = getUidFromComponentElementId($(this).attr("id"),"PaginationNextNextItem");
			let this_component = getRegisteredComponent(uid);
			this_component.current_page = this_component.current_page + 2;
			if (this_component.current_page > this_component.total_pages) {
				this_component.current_page = this_component.total_pages;
			}
			this_component.loadPage();
		});
		getComponentElementById(this,"MultiSelectAll").on("click", function() {
			let uid = getUidFromComponentElementId($(this).attr("id"),"MultiSelectAll");
			let this_component = getRegisteredComponent(uid);
			if ($(this).is(":checked")) {
				this_component.selected_items_array = [];
				$('.select_item_'+uid).each(function () {
					let id_start = $(this).attr("id").indexOf("_select_item_");
					let object_id = $(this).attr("id").substring(id_start+13);
					this_component.selected_items_array.push(object_id);
					$(this).prop("checked",true);
				});
				getComponentElementById(this_component,"MultiSelectOptionsButton").show();
			} else {
				this_component.selected_items_array = [];
				$('.select_item_'+uid).each(function () {
					$(this).prop("checked",false);
				});
				getComponentElementById(this_component,"MultiSelectOptionsButton").hide();
			}
		});
		getComponentElementById(this,"BulkActionDelete").on("click", function() {
			let uid = getUidFromComponentElementId($(this).attr("id"),"BulkActionDelete");
			let this_component = getRegisteredComponent(uid);
			showAlert("Are you sure?","warning",["Cancel","Delete"],false,0,this_component.deleteSelected,this_component.doNothing);
		});
		$(document).on("click",".first-column_"+this.dom_component_obj.uid, function() {
			let id_start = $(this).attr("id").indexOf("_row_item_");
			let clicked_id = $(this).attr("id").substring(id_start+10);
			let uid = $(this).attr("id").substring(0,id_start);
			let this_component = getRegisteredComponent(uid);
			this_component.on_item_clicked(clicked_id);
			return false;
		});
		$(document).on("click",".select_item_"+this.dom_component_obj.uid, function() {
			let id_start = $(this).attr("id").indexOf("_select_item_");
			let clicked_id = $(this).attr("id").substring(id_start+13);
			let uid = $(this).attr("id").substring(0,id_start);
			let this_component = getRegisteredComponent(uid);
			if (this_component.selected_items_array.indexOf(clicked_id) != -1) {
				this_component.selected_items_array.splice(this_component.selected_items_array.indexOf(clicked_id),1);
			} else {
				this_component.selected_items_array.push(clicked_id);
			}
			if (this_component.selected_items_array.length > 0) {
				getComponentElementById(this_component,"MultiSelectOptionsButton").show();
			} else {
				getComponentElementById(this_component,"MultiSelectOptionsButton").hide();
			}
		});
		this.column_name_array.forEach(function(item) {
			let uid = this.dom_component_obj.uid;
			let this_component = this;
			let column_name_array = Object.keys(this.column_name_obj);
			$("#"+uid+"_SortBy"+item).on("click", function() {
				if (typeof this_component.current_sort_column[1] !== "undefined") {
					let sort_down = !this_component.current_sort_column[1];
					this_component.current_sort_column = [item,sort_down];
				} else {
					this_component.current_sort_column = [item,true];
				}
				this_component.column_name_array.forEach(function(item_to_update) {
					if (item_to_update == item) {
						if (this_component.current_sort_column[1]) {
							$("#"+uid+"_SortBy"+item_to_update).html(this_component.column_name_obj[item_to_update]+' <small><i class="fa' +
								' fa-sort-alpha-asc" aria-hidden="true"></i></small>');
						} else {
							$("#"+uid+"_SortBy"+item_to_update).html(this_component.column_name_obj[item_to_update]+' <small><i class="fa' +
								' fa-sort-alpha-desc" aria-hidden="true"></i></small>');
						}
					} else {
						$("#"+uid+"_SortBy"+item_to_update).html(this_component.column_name_obj[item_to_update]);
					}
				});
				this_component.loadPage();
			});
		}.bind(this));
		this.doNothing = function() {
			//Just a helper function to reference on cancel of confirmation
		};
		this.exportData = function(table_exporter_data) {
			let this_component = this;
			this_component.table_exporter.export2file(
				table_exporter_data.data,
				table_exporter_data.mimeType,
				table_exporter_data.filename,
				table_exporter_data.fileExtension);
		}.bind(this);
		this.deleteSelected = function() {
			let this_component = this;
			let uid = this_component.dom_component_obj.uid;
			dxRequestInternal(getComponentControllerPath(this),
				{f:"deleteSelection",
					SelectedItemArray:JSON.stringify(this_component.selected_items_array)},
				function(data_obj) {
					getComponentElementById(this_component,"MultiSelectAll").prop("checked",false);
					this_component.selected_items_array = [];
					this_component.current_page = 1;
					this_component.loadPage();
					pageEventTriggered("account_selection_deleted",{});
				},
				function(data_obj) {
					showAlert("Error deleting items: "+data_obj.Message,"error","OK",false);
				});
		}.bind(this);
		this.loadPage = function() {
			let this_component = this;
			let uid = this_component.dom_component_obj.uid;
			this_component.current_page_array = [];
			let search_text = getComponentElementById(this_component,"DataTableSearchInput").val();
			getComponentElementById(this_component,"DataTableBody").html('<tr id="'+uid+'_DataTableLoading"><td colspan="5"' +
				'><div class="dx-loading"></div></td></tr>');
			dxRequestInternal(getComponentControllerPath(this),
				{f:"getPage",
					CurrentPage:this_component.current_page,
					ItemsPerPage:this_component.current_items_per_page,
					SearchText:search_text,
					SortOptions:JSON.stringify(this_component.current_sort_column)},
				function(data_obj) {
					getComponentElementById(this_component,"DataTableBody").html("");
					data_obj.Page.forEach(function(item) {
						this_component.addRow(item);
					});
					this_component.total_items = data_obj.TotalCount;
					this_component.total_pages = 1+ Math.round(this_component.total_items / this_component.current_items_per_page);
					this_component.remaining_pages = this_component.total_pages - this_component.current_page;
					if (this_component.current_page_array.length > 0) {
						getComponentElementById(this_component,"DataTableLoading").hide();
					} else {
						getComponentElementById(this_component,"DataTableBody").html('<tr id="#'+uid+'_DataTableLoading"><td colspan="5"' +
							' style="text-align: center;">No results</td></tr>');
					}
					if (this_component.current_page == 1) {
						getComponentElementById(this_component,"PaginationResetButton").addClass("disabled");
						getComponentElementById(this_component,"PaginationJumpBack").addClass("disabled");
					} else {
						getComponentElementById(this_component,"PaginationResetButton").removeClass("disabled");
						getComponentElementById(this_component,"PaginationJumpBack").removeClass("disabled");
					}
					if (this_component.current_page == this_component.total_pages) {
						getComponentElementById(this_component,"PaginationFinalPageButton").addClass("disabled");
						getComponentElementById(this_component,"PaginationJumpForward").addClass("disabled");
					} else {
						getComponentElementById(this_component,"PaginationFinalPageButton").removeClass("disabled");
						getComponentElementById(this_component,"PaginationJumpForward").removeClass("disabled");
					}
					if (this_component.remaining_pages > 0) {
						getComponentElementById(this_component,"PaginationNextItem").show();
					} else {
						getComponentElementById(this_component,"PaginationNextItem").hide();
					}
					if (this_component.remaining_pages > 1) {
						getComponentElementById(this_component,"PaginationNextNextItem").show();
					} else {
						getComponentElementById(this_component,"PaginationNextNextItem").hide();
					}
					let next_page = this_component.current_page+1;
					let next_next_page = next_page+1;
					getComponentElementById(this_component,"PaginationCurrentItem").html('<span class="page-link">'+this_component.current_page+'</span>');
					getComponentElementById(this_component,"PaginationNextItem").html('<span class="page-link">'+next_page+'</span>');
					getComponentElementById(this_component,"PaginationNextNextItem").html('<span class="page-link">'+next_next_page+'</span>');
				},
				function(data_obj) {
					getComponentElementById(this_component,"DataTable").hide();
					this_component.handleComponentError('Could not retrieve data: '+data_obj.Message);
				});
		}.bind(this);
		this.addRow = function(row_data_obj) {
			let this_component = this;
			this_component.current_page_array.push(row_data_obj);
			let uid = this_component.dom_component_obj.uid;
			let row_id = row_data_obj["Id"];
			let checked_html = '';
			// Doing it this way since indexOf and includes does not identify the items as being in the array...
			this_component.selected_items_array.forEach(function(item) {if (item == row_id) {checked_html = ' checked';}});
			if (this_component.selected_items_array.length > 0) {
				getComponentElementById(this_component,"MultiSelectOptionsButton").show();
			} else {
				getComponentElementById(this_component,"MultiSelectOptionsButton").hide();
			}
			let html = '<tr class="'+uid+'_row_item_'+row_id+' dx-data-table-row">';
			let row_keys = Object.keys(row_data_obj);
			let is_first = true;
			row_keys.forEach(function(key) {
				if (key != "Id") {
					if (is_first) {
						html += '<th scope="row"><a href="#" id="'+uid+'_row_item_'+row_id+'" class="data-table-first-column first-column_'+uid+'">'+row_data_obj[key]+'</a></th>';
					} else {
						html += '<td>'+row_data_obj[key]+'</td>';
					}
					is_first = false;
				} else {
					html += '<td><input id="'+uid+'_select_item_'+row_id+'" type="checkbox"' +
						' class="select_item_'+uid+'" name="'+uid+'_select_item_'+row_id+'" value="'+uid+'_select_item_'+row_id+'"'+checked_html+'></td>';
				}
			});
			html += '</tr>';
			getComponentElementById(this_component,"DataTableBody").append(html);
		}.bind(this);
		// Data table functions to implement
		this.on_item_clicked = function(id) {
			pageEventTriggered("account_clicked",{id:id});
		}.bind(this);
	}
}
