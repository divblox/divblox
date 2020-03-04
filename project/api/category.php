<?php
require("../../divblox/divblox.php");
require(PROJECT_ROOT_STR.'/api/data_model/ApiCrudCategory.class.php');
ApiCrudCategory::initCrudApi("Category");
ApiCrudCategory::doApiOperationDefinitions();
ApiCrudCategory::executeCrudApi();

// Operations
function createCategory() {
    ApiCrudCategory::create();
}
function retrieveCategory() {
    ApiCrudCategory::retrieve();
}
function updateCategory() {
    ApiCrudCategory::update();
}
function deleteCategory() {
    ApiCrudCategory::delete();
}
function listCategory() {
    ApiCrudCategory::retrieveList();
}
?>
