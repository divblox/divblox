<?php
require("../../divblox/divblox.php");
require(PROJECT_ROOT_STR.'/api/data_model/ApiCrudUserRole.class.php');
ApiCrudUserRole::initCrudApi("UserRole");
ApiCrudUserRole::doApiOperationDefinitions();
ApiCrudUserRole::executeCrudApi();

// Operations
function createUserRole() {
    ApiCrudUserRole::create();
}
function retrieveUserRole() {
    ApiCrudUserRole::retrieve();
}
function updateUserRole() {
    ApiCrudUserRole::update();
}
function deleteUserRole() {
    ApiCrudUserRole::delete();
}
function listUserRole() {
    ApiCrudUserRole::retrieveList();
}
?>
