<?php
require("../../divblox/divblox.php");
require(PROJECT_ROOT_STR.'/api/data_model/ApiCrudClientConnection.class.php');
ApiCrudClientConnection::initCrudApi("ClientConnection");
ApiCrudClientConnection::doApiOperationDefinitions();
ApiCrudClientConnection::executeCrudApi();

// Operations
function createClientConnection() {
    ApiCrudClientConnection::create();
}
function retrieveClientConnection() {
    ApiCrudClientConnection::retrieve();
}
function updateClientConnection() {
    ApiCrudClientConnection::update();
}
function deleteClientConnection() {
    ApiCrudClientConnection::delete();
}
function listClientConnection() {
    ApiCrudClientConnection::retrieveList();
}
?>
