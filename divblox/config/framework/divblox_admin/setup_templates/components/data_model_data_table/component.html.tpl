<div id="ComponentWrapper" class="component-wrapper">
    <div id="ComponentPlaceholder" class="component_placeholder component_placeholder_data_table">
        <div id="ComponentFeedback"></div>
    </div>
    <div id="ComponentContent" class="component-content" style="display:none">
        <div class="container-fluid container-no-gutters">
            <div class="row">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" id="DataTableSearchInput" class="form-control data_table_search_icon" placeholder="Search..." aria-label="Search" aria-describedby="btnResetSearch"/>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="btnResetSearch"><i class="fa fa-times" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div id="ExportOptionsButton" class="dropdown d-inline-flex">
                        <button class="btn btn-outline-success dropdown-toggle" type="button" id="dropdownMenuForExport" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Export
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuForExport">
                            <button id="BulkActionExportXlsx" class="dropdown-item" type="button"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel</button>
                            <button id="BulkActionExportCsv" class="dropdown-item" type="button"><i class="fa fa-file-text-o" aria-hidden="true"></i> .csv</button>
                            <button id="BulkActionExportTxt" class="dropdown-item" type="button"><i class="fa fa-file-text" aria-hidden="true"></i> .txt</button>
                        </div>
                    </div>
                    <div id="MultiSelectOptionsButton" class="dropdown" style="display: none;">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Bulk Actions
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                            <button id="BulkActionDelete" class="dropdown-item" type="button"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div id="ResultCountWrapper" class="mt-2"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div id="DataTable" class="table-responsive mt-2">
                        <table id="DataTableTableHtml" class="table">
                            <thead class="thead-light">
                                <tr id="DataTableHeaderHtml"></tr>
                            </thead>
                            <tbody id="DataTableBody">
                                <tr id="DataTableLoading"></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-2">
                    <select id="PaginationItemsPerPage" class="form-control">
                        <option value="5">5 per page</option>
                        <option value="10">10 per page</option>
                        <option value="25" selected="selected">25 per page</option>
                        <option value="50">50 per page</option>
                        <option value="100">100 per page</option>
                        <option value="500">500 per page</option>
                    </select>
                </div>
                <div class="col-md-9">
                    <nav aria-label="Data Table Navigation">
                        <ul class="pagination">
                            <li id="PaginationResetButton" class="page-item">
                            <span class="page-link" aria-label="Previous">
                                <i class="fa fa-chevron-left" aria-hidden="true"></i>
                                <span class="sr-only">Previous</span>
                            </span>
                            </li>
                            <li id="PaginationJumpBack" class="page-item"><span class="page-link">...</span></li>
                            <li id="PaginationCurrentItem" class="page-item active"><span class="page-link">1</span></li>
                            <li id="PaginationNextItem" class="page-item"><span class="page-link">2</span></li>
                            <li id="PaginationNextNextItem" class="page-item"><span class="page-link">3</span></li>
                            <li id="PaginationJumpForward" class="page-item"><span class="page-link">...</span></li>
                            <li id="PaginationFinalPageButton" class="page-item">
                            <span class="page-link" aria-label="Next">
                                <i class="fa fa-chevron-right" aria-hidden="true"></i>
                                <span class="sr-only">Next</span>
                            </span>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>