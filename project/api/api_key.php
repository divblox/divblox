<?php
require("../../divblox/divblox.php");
require(PROJECT_ROOT_STR.'/api/data_model/ApiCrudApiKey.class.php');
ApiCrudApiKey::initCrudApi("ApiKey");
ApiCrudApiKey::doApiOperationDefinitions();
ApiCrudApiKey::executeCrudApi();

// Operations
function createApiKey() {
    ApiCrudApiKey::create();
}
function retrieveApiKey() {
    ApiCrudApiKey::retrieve();
}
function updateApiKey() {
    ApiCrudApiKey::update();
}
function deleteApiKey() {
    ApiCrudApiKey::delete();
}
function listApiKey() {
    ApiCrudApiKey::retrieveList();
}
?>
