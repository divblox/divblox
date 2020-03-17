<?php
require("../../divblox/divblox.php");
require(PROJECT_ROOT_STR.'/api/data_model/ApiCrudSubTask.class.php');
ApiCrudSubTask::initCrudApi("SubTask");
ApiCrudSubTask::doApiOperationDefinitions();
ApiCrudSubTask::executeCrudApi();

// Operations
function createSubTask() {
    ApiCrudSubTask::create();
}
function retrieveSubTask() {
    ApiCrudSubTask::retrieve();
}
function updateSubTask() {
    ApiCrudSubTask::update();
}
function deleteSubTask() {
    ApiCrudSubTask::delete();
}
function listSubTask() {
    ApiCrudSubTask::retrieveList();
}
?>
