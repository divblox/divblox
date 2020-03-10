<?php
require("../../divblox/divblox.php");
require(PROJECT_ROOT_STR.'/api/data_model/ApiCrudExpense.class.php');
ApiCrudExpense::initCrudApi("Expense");
ApiCrudExpense::doApiOperationDefinitions();
ApiCrudExpense::executeCrudApi();

// Operations
function createExpense() {
    ApiCrudExpense::create();
}
function retrieveExpense() {
    ApiCrudExpense::retrieve();
}
function updateExpense() {
    ApiCrudExpense::update();
}
function deleteExpense() {
    ApiCrudExpense::delete();
}
function listExpense() {
    ApiCrudExpense::retrieveList();
}
?>
