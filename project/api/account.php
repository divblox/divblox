<?php
require("../../divblox/divblox.php");
require(PROJECT_ROOT_STR.'/api/data_model/ApiCrudAccount.class.php');
ApiCrudAccount::initCrudApi("Account");
ApiCrudAccount::doApiOperationDefinitions();
ApiCrudAccount::executeCrudApi();

// Operations
function createAccount() {
    ApiCrudAccount::create();
}
function retrieveAccount() {
    ApiCrudAccount::retrieve();
}
function updateAccount() {
    ApiCrudAccount::update();
}
function deleteAccount() {
    ApiCrudAccount::delete();
}
function listAccount() {
    ApiCrudAccount::retrieveList();
}
function authenticateAccount() {
    ApiCrudAccount::authenticate();
}
