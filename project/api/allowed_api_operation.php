<?php
require("../../divblox/divblox.php");
require(PROJECT_ROOT_STR.'/api/data_model/ApiCrudAllowedApiOperation.class.php');
ApiCrudAllowedApiOperation::initCrudApi("AllowedApiOperation");
ApiCrudAllowedApiOperation::doApiOperationDefinitions();
ApiCrudAllowedApiOperation::executeCrudApi();

// Operations
function createAllowedApiOperation() {
    ApiCrudAllowedApiOperation::create();
}
function retrieveAllowedApiOperation() {
    ApiCrudAllowedApiOperation::retrieve();
}
function updateAllowedApiOperation() {
    ApiCrudAllowedApiOperation::update();
}
function deleteAllowedApiOperation() {
    ApiCrudAllowedApiOperation::delete();
}
function listAllowedApiOperation() {
    ApiCrudAllowedApiOperation::retrieveList();
}
?>
