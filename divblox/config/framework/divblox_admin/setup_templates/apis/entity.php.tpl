<?php
require("../../divblox/divblox.php");
require(PROJECT_ROOT_STR.'/api/data_model/ApiCrud[entity_name].class.php');
ApiCrud[entity_name]::initCrudApi("[entity_name]");
ApiCrud[entity_name]::doApiOperationDefinitions();
ApiCrud[entity_name]::executeCrudApi();

// Operations
function create[entity_name]() {
    ApiCrud[entity_name]::create();
}
function retrieve[entity_name]() {
    ApiCrud[entity_name]::retrieve();
}
function update[entity_name]() {
    ApiCrud[entity_name]::update();
}
function delete[entity_name]() {
    ApiCrud[entity_name]::delete();
}
function list[entity_name]() {
    ApiCrud[entity_name]::retrieveList();
}
?>
