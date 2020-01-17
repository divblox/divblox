<?php
require("../../divblox/divblox.php");
require(PROJECT_ROOT_STR.'/api/data_model/ApiCrudEmailMessage.class.php');
ApiCrudEmailMessage::initCrudApi("EmailMessage");
ApiCrudEmailMessage::doApiOperationDefinitions();
ApiCrudEmailMessage::executeCrudApi();

// Operations
function createEmailMessage() {
    ApiCrudEmailMessage::create();
}
function retrieveEmailMessage() {
    ApiCrudEmailMessage::retrieve();
}
function updateEmailMessage() {
    ApiCrudEmailMessage::update();
}
function deleteEmailMessage() {
    ApiCrudEmailMessage::delete();
}
function listEmailMessage() {
    ApiCrudEmailMessage::retrieveList();
}
?>
