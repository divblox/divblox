<?php
require("../../divblox/divblox.php");
require(PROJECT_ROOT_STR.'/api/data_model/ApiCrudFileDocument.class.php');
ApiCrudFileDocument::initCrudApi("FileDocument");
ApiCrudFileDocument::doApiOperationDefinitions();
ApiCrudFileDocument::executeCrudApi();

// Operations
function createFileDocument() {
    ApiCrudFileDocument::create();
}
function retrieveFileDocument() {
    ApiCrudFileDocument::retrieve();
}
function updateFileDocument() {
    ApiCrudFileDocument::update();
}
function deleteFileDocument() {
    ApiCrudFileDocument::delete();
}
function listFileDocument() {
    ApiCrudFileDocument::retrieveList();
}
?>
