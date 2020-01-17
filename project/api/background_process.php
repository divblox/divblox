<?php
require("../../divblox/divblox.php");
require(PROJECT_ROOT_STR.'/api/data_model/ApiCrudBackgroundProcess.class.php');
ApiCrudBackgroundProcess::initCrudApi("BackgroundProcess");
ApiCrudBackgroundProcess::doApiOperationDefinitions();
ApiCrudBackgroundProcess::executeCrudApi();

// Operations
function createBackgroundProcess() {
    ApiCrudBackgroundProcess::create();
}
function retrieveBackgroundProcess() {
    ApiCrudBackgroundProcess::retrieve();
}
function updateBackgroundProcess() {
    ApiCrudBackgroundProcess::update();
}
function deleteBackgroundProcess() {
    ApiCrudBackgroundProcess::delete();
}
function listBackgroundProcess() {
    ApiCrudBackgroundProcess::retrieveList();
}
?>
