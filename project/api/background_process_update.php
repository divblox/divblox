<?php
require("../../divblox/divblox.php");
require(PROJECT_ROOT_STR.'/api/data_model/ApiCrudBackgroundProcessUpdate.class.php');
ApiCrudBackgroundProcessUpdate::initCrudApi("BackgroundProcessUpdate");
ApiCrudBackgroundProcessUpdate::doApiOperationDefinitions();
ApiCrudBackgroundProcessUpdate::executeCrudApi();

// Operations
function createBackgroundProcessUpdate() {
    ApiCrudBackgroundProcessUpdate::create();
}
function retrieveBackgroundProcessUpdate() {
    ApiCrudBackgroundProcessUpdate::retrieve();
}
function updateBackgroundProcessUpdate() {
    ApiCrudBackgroundProcessUpdate::update();
}
function deleteBackgroundProcessUpdate() {
    ApiCrudBackgroundProcessUpdate::delete();
}
function listBackgroundProcessUpdate() {
    ApiCrudBackgroundProcessUpdate::retrieveList();
}
?>
