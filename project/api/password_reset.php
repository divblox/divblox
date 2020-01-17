<?php
require("../../divblox/divblox.php");
require(PROJECT_ROOT_STR.'/api/data_model/ApiCrudPasswordReset.class.php');
ApiCrudPasswordReset::initCrudApi("PasswordReset");
ApiCrudPasswordReset::doApiOperationDefinitions();
ApiCrudPasswordReset::executeCrudApi();

// Operations
function createPasswordReset() {
    ApiCrudPasswordReset::create();
}
function retrievePasswordReset() {
    ApiCrudPasswordReset::retrieve();
}
function updatePasswordReset() {
    ApiCrudPasswordReset::update();
}
function deletePasswordReset() {
    ApiCrudPasswordReset::delete();
}
function listPasswordReset() {
    ApiCrudPasswordReset::retrieveList();
}
?>
