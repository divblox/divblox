if (typeof component_classes['data_model_account_administration_data_series'] === "undefined") {
	class data_model_account_administration_data_series extends DivbloxDomBaseComponent {
		constructor(inputs,supports_native,requires_native) {
			super(inputs,supports_native,requires_native);
			// Sub component config start
			this.sub_component_definitions = [];
			// Sub component config end
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
			this.current_items_per_page = $("#"+this.uid+"_PaginationItemsPerPage").val();
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
		}
		reset(inputs) {
			this.loadPage();
		}
		loadPrerequisites(success_callback,fail_callback) {
			dxGetScript(getRootPath()+'project/assets/js/tableexport/xlsx.core.min.js',function() {
				dxGetScript(getRootPath()+'project/assets/js/tableexport/FileSaver.min.js',function() {
					dxGetScript(getRootPath()+'project/assets/js/tableexport/tableexport.min.js',function() {
						success_callback();
					}.bind(this))
				}.bind(this))
			}.bind(this));
		}
		registerDomEvents = function() {
			getComponentElementById(this,"BulkActionExportXlsx").on("click", function() {
				let uid = this.getUid();
				this.table_exporter = getComponentElementById(this,"DataTableTableHtml").tableExport({
					exportButtons: false,
					formats: ['xlsx'],
					filename: "dx_xlsx_export_"+moment().format("YYYY-MM-DD_h_mm_ss"),
				});
				this.exportData(this.table_exporter.getExportData()[uid+'_DataTableTableHtml']['xlsx']);
			}.bind(this));
			getComponentElementById(this,"BulkActionExportCsv").on("click", function() {
				let uid = this.getUid();
				this.table_exporter = getComponentElementById(this,"DataTableTableHtml").tableExport({
					exportButtons: false,
					formats: ['csv'],
					filename: "dx_csv_export_"+moment().format("YYYY-MM-DD_h_mm_ss"),
				});
				this.exportData(this.table_exporter.getExportData()[uid+'_DataTableTableHtml']['csv']);
			}.bind(this));
			getComponentElementById(this,"BulkActionExportTxt").on("click", function() {
				let uid = this.getUid();
				this.table_exporter = getComponentElementById(this,"DataTableTableHtml").tableExport({
					exportButtons: false,
					formats: ['txt'],
					filename: "dx_txt_export_"+moment().format("YYYY-MM-DD_h_mm_ss"),
				});
				this.exportData(this.table_exporter.getExportData()[uid+'_DataTableTableHtml']['txt']);
			}.bind(this));
			getComponentElementById(this,"DataTableSearchInput").on("keyup", function() {
				let search_text = getComponentElementById(this,"DataTableSearchInput").val();
				setTimeout(function() {
					if (search_text == getComponentElementById(this,"DataTableSearchInput").val()) {
						this.current_page = 1;
						this.loadPage();
					}
				}.bind(this),500);
			}.bind(this));
			getComponentElementById(this,"btnResetSearch").on("click", function() {
				getComponentElementById(this,"DataTableSearchInput").val("");
				this.loadPage();
			}.bind(this));
			getComponentElementById(this,"PaginationItemsPerPage").on("change", function() {
				this.current_items_per_page = $(this).val();
				this.loadPage();
			}.bind(this));
			getComponentElementById(this,"PaginationResetButton").on("click", function() {
				if ($(this).hasClass("disabled")) {
					return;
				}
				this.current_page = 1;
				this.loadPage();
			}.bind(this));
			getComponentElementById(this,"PaginationFinalPageButton").on("click", function() {
				if ($(this).hasClass("disabled")) {
					return;
				}
				this.current_page = this.total_pages;
				this.loadPage();
			}.bind(this));
			getComponentElementById(this,"PaginationJumpBack").on("click", function() {
				if ($(this).hasClass("disabled")) {
					return;
				}
				this.current_page = this.current_page - 3;
				if (this.current_page < 1) {
					this.current_page = 1;
				}
				this.loadPage();
			}.bind(this));
			getComponentElementById(this,"PaginationJumpForward").on("click", function() {
				if ($(this).hasClass("disabled")) {
					return;
				}
				this.current_page = this.current_page + 3;
				if (this.current_page > this.total_pages) {
					this.current_page = this.total_pages;
				}
				this.loadPage();
			}.bind(this));
			getComponentElementById(this,"PaginationNextItem").on("click", function() {
				this.current_page = this.current_page + 1;
				if (this.current_page > this.total_pages) {
					this.current_page = this.total_pages;
				}
				this.loadPage();
			}.bind(this));
			getComponentElementById(this,"PaginationNextNextItem").on("click", function() {
				this.current_page = this.current_page + 2;
				if (this.current_page > this.total_pages) {
					this.current_page = this.total_pages;
				}
				this.loadPage();
			}.bind(this));
			getComponentElementById(this,"MultiSelectAll").on("click", function() {
				let uid = this.getUid();
				if ($(this).is(":checked")) {
					this.selected_items_array = [];
					$('.select_item_'+uid).each(function () {
						let id_start = $(this).attr("id").indexOf("_select_item_");
						let object_id = $(this).attr("id").substring(id_start+13);
						this.selected_items_array.push(object_id);
						$(this).prop("checked",true);
					}.bind(this));
					getComponentElementById(this,"MultiSelectOptionsButton").show().addClass("d-inline-flex");
				} else {
					this.selected_items_array = [];
					$('.select_item_'+uid).each(function () {
						$(this).prop("checked",false);
					});
					getComponentElementById(this,"MultiSelectOptionsButton").hide().removeClass("d-inline-flex");
				}
			}.bind(this));
			getComponentElementById(this,"BulkActionDelete").on("click", function() {
				showAlert("Are you sure?","warning",["Cancel","Delete"],false,0,this.deleteSelected.bind(this),this.doNothing);
			}.bind(this));
			$(document).on("click",".first-column_"+this.getUid(), function() {
				let id_start = $(this).attr("id").indexOf("_row_item_");
				let clicked_id = $(this).attr("id").substring(id_start+10);
				let uid = $(this).attr("id").substring(0,id_start);
				let this_component = getRegisteredComponent(uid);
				this_component.on_item_clicked(clicked_id);
				return false;
			});
			registerEventHandler(document,"click",undefined,".first-column_"+this.getUid());
			$(document).on("click",".select_item_"+this.getUid(), function() {
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
					getComponentElementById(this_component,"MultiSelectOptionsButton").show().addClass("d-inline-flex");
				} else {
					getComponentElementById(this_component,"MultiSelectOptionsButton").hide().removeClass("d-inline-flex");
				}
			});
			registerEventHandler(document,"click",undefined,".select_item_"+this.getUid());
			this.column_name_array.forEach(function(item) {
				let uid = this.getUid();
				let column_name_array = Object.keys(this.column_name_obj);
				$("#"+uid+"_SortBy"+item).on("click", function() {
					if (typeof this.current_sort_column[1] !== "undefined") {
						let sort_down = !this.current_sort_column[1];
						this.current_sort_column = [item,sort_down];
					} else {
						this.current_sort_column = [item,true];
					}
					this.column_name_array.forEach(function(item_to_update) {
						if (item_to_update == item) {
							if (this.current_sort_column[1]) {
								$("#"+uid+"_SortBy"+item_to_update).html(this.column_name_obj[item_to_update]+' <small><i class="fa' +
									' fa-sort-alpha-asc" aria-hidden="true"></i></small>');
							} else {
								$("#"+uid+"_SortBy"+item_to_update).html(this.column_name_obj[item_to_update]+' <small><i class="fa' +
									' fa-sort-alpha-desc" aria-hidden="true"></i></small>');
							}
						} else {
							$("#"+uid+"_SortBy"+item_to_update).html(this.column_name_obj[item_to_update]);
						}
					}.bind(this));
					this.loadPage();
				}.bind(this));
			}.bind(this));
		}
		doNothing = function() {
			//Just a helper function to reference on cancel of confirmation
		};
		exportData = function(table_exporter_data) {
			this.table_exporter.export2file(
				table_exporter_data.data,
				table_exporter_data.mimeType,
				table_exporter_data.filename,
				table_exporter_data.fileExtension);
		}
		deleteSelected = function() {
			dxRequestInternal(getComponentControllerPath(this),
				{f:"deleteSelection",
					SelectedItemArray:JSON.stringify(this.selected_items_array)},
				function(data_obj) {
					getComponentElementById(this,"MultiSelectAll").prop("checked",false);
					this.selected_items_array = [];
					this.current_page = 1;
					this.loadPage();
					pageEventTriggered("account_selection_deleted",{});
				}.bind(this),
				function(data_obj) {
					showAlert("Error deleting items: "+data_obj.Message,"error","OK",false);
				}.bind(this));
		}
		loadPage = function() {
			let uid = this.getUid();
			let search_text = getComponentElementById(this,"DataTableSearchInput").val();
			getComponentElementById(this,"DataTableBody").html('<tr id="'+uid+'_DataTableLoading"><td colspan="5"' +
				'><div class="dx-loading"></div></td></tr>');
			dxRequestInternal(getComponentControllerPath(this),
				{f:"getPage",
					CurrentPage:this.current_page,
					ItemsPerPage:this.current_items_per_page,
					SearchText:search_text,
					SortOptions:JSON.stringify(this.current_sort_column)},
				function(data_obj) {
					getComponentElementById(this,"DataTableBody").html("");
					data_obj.Page.forEach(function(item) {
						this.addRow(item);
					}.bind(this));
					this.total_items = data_obj.TotalCount;
					this.total_pages = 1+ Math.round(this.total_items / this.current_items_per_page);
					this.remaining_pages = this.total_pages - this.current_page;
					if (this.current_page_array.length > 0) {
						getComponentElementById(this,"DataTableLoading").hide();
					} else {
						getComponentElementById(this,"DataTableBody").html('<tr id="#'+uid+'_DataTableLoading"><td colspan="5"' +
							' style="text-align: center;">No results</td></tr>');
					}
					if (this.current_page == 1) {
						getComponentElementById(this,"PaginationResetButton").addClass("disabled");
						getComponentElementById(this,"PaginationJumpBack").addClass("disabled");
					} else {
						getComponentElementById(this,"PaginationResetButton").removeClass("disabled");
						getComponentElementById(this,"PaginationJumpBack").removeClass("disabled");
					}
					if (this.current_page == this.total_pages) {
						getComponentElementById(this,"PaginationFinalPageButton").addClass("disabled");
						getComponentElementById(this,"PaginationJumpForward").addClass("disabled");
					} else {
						getComponentElementById(this,"PaginationFinalPageButton").removeClass("disabled");
						getComponentElementById(this,"PaginationJumpForward").removeClass("disabled");
					}
					if (this.remaining_pages > 0) {
						getComponentElementById(this,"PaginationNextItem").show();
					} else {
						getComponentElementById(this,"PaginationNextItem").hide();
					}
					if (this.remaining_pages > 1) {
						getComponentElementById(this,"PaginationNextNextItem").show();
					} else {
						getComponentElementById(this,"PaginationNextNextItem").hide();
					}
					let next_page = this.current_page+1;
					let next_next_page = next_page+1;
					getComponentElementById(this,"PaginationCurrentItem").html('<span class="page-link">'+this.current_page+'</span>');
					getComponentElementById(this,"PaginationNextItem").html('<span class="page-link">'+next_page+'</span>');
					getComponentElementById(this,"PaginationNextNextItem").html('<span class="page-link">'+next_next_page+'</span>');
				}.bind(this),
				function(data_obj) {
					getComponentElementById(this,"DataTable").hide();
					this.handleComponentError('Could not retrieve data: '+data_obj.Message);
				}.bind(this),false,false);
		}
		addRow = function(row_data_obj) {
			this.current_page_array.push(row_data_obj);
			let uid = this.getUid();
			let row_id = row_data_obj["Id"];
			let checked_html = '';
			// Doing it this way since indexOf and includes does not identify the items as being in the array...
			this.selected_items_array.forEach(function(item) {if (item == row_id) {checked_html = ' checked';}});
			if (this.selected_items_array.length > 0) {
				getComponentElementById(this,"MultiSelectOptionsButton").show().addClass("d-inline-flex");
			} else {
				getComponentElementById(this,"MultiSelectOptionsButton").hide().removeClass("d-inline-flex");
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
			getComponentElementById(this,"DataTableBody").append(html);
		}
		// Data table functions to implement
		on_item_clicked = function(id) {
			setGlobalConstrainById("Account",id);
			pageEventTriggered("account_clicked",{id:id});
		}
	}
	component_classes['data_model_account_administration_data_series'] = data_model_account_administration_data_series;
}