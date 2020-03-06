<?php
require("../../divblox/divblox.php");
require(PROJECT_ROOT_STR.'/api/data_model/ApiCrudTicket.class.php');
ApiCrudTicket::initCrudApi("Ticket");
ApiCrudTicket::doApiOperationDefinitions();
ApiCrudTicket::executeCrudApi();

// Operations
function createTicket() {
    ApiCrudTicket::create();
}
function retrieveTicket() {
    ApiCrudTicket::retrieve();
}
function updateTicket() {
    ApiCrudTicket::update();
}
function deleteTicket() {
    ApiCrudTicket::delete();
}
function listTicket() {
    ApiCrudTicket::retrieveList();
}
?>
