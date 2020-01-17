<?php
require("../../divblox/divblox.php");
require(PROJECT_ROOT_STR.'/api/data_model/ApiCrudApiOperation.class.php');
ApiCrudApiOperation::initCrudApi("ApiOperation");
ApiCrudApiOperation::doApiOperationDefinitions();
ApiCrudApiOperation::executeCrudApi();

// Operations
function createApiOperation() {
    ApiCrudApiOperation::create();
}
function retrieveApiOperation() {
    ApiCrudApiOperation::retrieve();
}
function updateApiOperation() {
    ApiCrudApiOperation::update();
}
function deleteApiOperation() {
    ApiCrudApiOperation::delete();
}
function listApiOperation() {
    ApiCrudApiOperation::retrieveList();
}
?>
