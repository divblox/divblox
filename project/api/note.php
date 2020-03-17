<?php
require("../../divblox/divblox.php");
require(PROJECT_ROOT_STR.'/api/data_model/ApiCrudNote.class.php');
ApiCrudNote::initCrudApi("Note");
ApiCrudNote::doApiOperationDefinitions();
ApiCrudNote::executeCrudApi();

// Operations
function createNote() {
    ApiCrudNote::create();
}
function retrieveNote() {
    ApiCrudNote::retrieve();
}
function updateNote() {
    ApiCrudNote::update();
}
function deleteNote() {
    ApiCrudNote::delete();
}
function listNote() {
    ApiCrudNote::retrieveList();
}
?>
