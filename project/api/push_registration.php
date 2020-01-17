<?php
require("../../divblox/divblox.php");
require(PROJECT_ROOT_STR.'/api/data_model/ApiCrudPushRegistration.class.php');
ApiCrudPushRegistration::initCrudApi("PushRegistration");
ApiCrudPushRegistration::doApiOperationDefinitions();
ApiCrudPushRegistration::executeCrudApi();

// Operations
function createPushRegistration() {
    ApiCrudPushRegistration::create();
}
function retrievePushRegistration() {
    ApiCrudPushRegistration::retrieve();
}
function updatePushRegistration() {
    ApiCrudPushRegistration::update();
}
function deletePushRegistration() {
    ApiCrudPushRegistration::delete();
}
function listPushRegistration() {
    ApiCrudPushRegistration::retrieveList();
}
?>
