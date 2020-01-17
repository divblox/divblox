<?php
require("../../divblox/divblox.php");
require(PROJECT_ROOT_STR.'/api/data_model/ApiCrudPageView.class.php');
ApiCrudPageView::initCrudApi("PageView");
ApiCrudPageView::doApiOperationDefinitions();
ApiCrudPageView::executeCrudApi();

// Operations
function createPageView() {
    ApiCrudPageView::create();
}
function retrievePageView() {
    ApiCrudPageView::retrieve();
}
function updatePageView() {
    ApiCrudPageView::update();
}
function deletePageView() {
    ApiCrudPageView::delete();
}
function listPageView() {
    ApiCrudPageView::retrieveList();
}
?>
